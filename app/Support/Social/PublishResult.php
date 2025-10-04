<?php
namespace App\Support\Social;

class PublishResult
{
    public function __construct(
        public bool $ok,
        public ?string $remoteId = null,
        public ?array $raw = null,
        public ?string $error = null
    ) {}
}
