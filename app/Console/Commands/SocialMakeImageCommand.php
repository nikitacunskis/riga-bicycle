<?php

// app/Console/Commands/SocialMakeImageCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SocialCardGenerator;
use Illuminate\Support\Facades\Storage;

class SocialMakeImageCommand extends Command
{
    protected $signature = 'social:image
        {--title= : Main title (required)}
        {--subtitle= : Subtitle}
        {--bg= : Background path or URL}
        {--logo= : Logo path}
        {--primary=#0ea5e9 : Primary color hex}
        {--secondary=#0b1220 : Secondary color hex}
        {--watermark= : Small text bottom-right}
        {--print : Print absolute path only (for scripting)}';

    protected $description = 'Generate a social image and save to storage/app/public/social/...';

    public function handle(SocialCardGenerator $cards): int
    {
        $title = (string)($this->option('title') ?? '');
        if ($title === '') {
            $this->error('Missing --title');
            return self::FAILURE;
        }
        $subtitle = str_replace('\n', "\n", $this->option('subtitle') ?? '');

        $abs = $cards->make([
            'title' => $title,
            'subtitle' => $subtitle,
            'bg' => $this->option('bg'),
            'logo' => $this->option('logo'),
            'primary' => $this->option('primary'),
            'secondary' => $this->option('secondary'),
            'watermark' => $this->option('watermark'),
        ]);

        $relative = str_replace(Storage::disk('public')->path(''), '', $abs);
        $url = Storage::disk('public')->url($relative);

        if ($this->option('print')) {
            $this->line($abs);
            return self::SUCCESS;
        }

        $this->info('OK ✅ image generated');
        $this->line("path:     {$abs}");
        $this->line("relative: {$relative}");
        $this->line("url:      {$url}");

        return self::SUCCESS;
    }
}
