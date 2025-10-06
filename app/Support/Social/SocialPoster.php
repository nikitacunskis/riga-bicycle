<?php
namespace App\Support\Social;
use App\Support\Social\Contracts\SocialDriver;
use InvalidArgumentException;

class SocialPoster
{
    /** @param array<string,SocialDriver> $drivers */
    public function __construct(private array $drivers) {}

    public function post(string $channel, PostData $data): PublishResult
    {
        $key = strtolower($channel);
        if (! isset($this->drivers[$key])) {
            throw new InvalidArgumentException("Unsupported channel: {$channel}");
        }
        return $this->drivers[$key]->publish($data);
    }
}
