<?php

namespace App\Console\Commands;

use App\Services\MetaPublisherService;
use Illuminate\Console\Command;

class MetaPostCommand extends Command
{
    protected $signature = 'meta:post
        {--message= : Message text (defaults to "test")}
        {--image= : Public image URL (if set, posts photo with caption)}
        {--link= : Optional link for FB text post}
        {--dry : Dry run (no network)}';

    protected $description = 'Create a Facebook Page post via Meta Graph API (text or photo).';

    public function handle(MetaPublisherService $svc): int
    {
        $dry     = (bool)$this->option('dry');
        $message = (string)($this->option('message') ?? 'test');
        $image   = $this->option('image');
        $link    = $this->option('link');

        try {
            if ($image) {
                $id = $svc->publishToFacebookPhoto((string)$image, $message, $dry);
                $this->info("FB photo post ID: {$id}");
                return self::SUCCESS;
            }

            $id = $svc->publishToFacebookFeed($message, $link, $dry);
            $this->info("FB feed post ID: {$id}");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Publish failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
