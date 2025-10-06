<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\Social\SocialPoster;
use App\Support\Social\PostData;

class SocialPostCommand extends Command
{
    protected $signature = 'social:post
        {channel : facebook|x|telegram|instagram}
        {--text= : Text/caption}
        {--link= : Link URL (Facebook link posts)}
        {--media=* : Media path(s) or URL(s), repeatable}
        {--chat_id= : (Telegram) override chat id}
        {--dry-run : Don\'t call remote APIs, just print payload}';

    protected $description = 'Post a test message to a social channel via SocialPoster';

    public function handle(SocialPoster $poster): int
    {
        $channel = strtolower($this->argument('channel'));
        $text    = (string)($this->option('text') ?? '');
        $link    = $this->option('link') ?: null;
        $media   = (array)($this->option('media') ?: []);
        $meta    = [];

        if ($channel === 'telegram' && $this->option('chat_id')) {
            $meta['chat_id'] = $this->option('chat_id');
        }

        $data = new PostData(text: $text, link: $link, media: $media, meta: $meta);

        if ($this->option('dry-run')) {
            $this->info('[DRY RUN] Would post:');
            $this->line(json_encode([
                'channel' => $channel,
                'text'    => $text,
                'link'    => $link,
                'media'   => $media,
                'meta'    => $meta,
            ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
            return Command::SUCCESS;
        }

        $res = $poster->post($channel, $data);

        if ($res->ok) {
            $this->info("OK ✅ channel={$channel} remoteId=" . ($res->remoteId ?? 'null'));
            $this->line('[raw]');
            $this->line(is_array($res->raw) ? json_encode($res->raw, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) : var_export($res->raw, true));
            return Command::SUCCESS;
        }

        $this->error("FAILED ❌ channel={$channel}");
        $this->error($res->error ?? 'unknown error');
        return Command::FAILURE;
    }
}
