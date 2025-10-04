<?php
namespace App\Support\Social;

class PostData
{
    public function __construct(
        public string $text,
        public ?string $link = null,         // for FB link posts
        public array $media = [],            // paths|urls
        public array $meta = []              // per-channel opts
    ) {}
}
