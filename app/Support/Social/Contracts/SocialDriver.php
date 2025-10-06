<?php
namespace App\Support\Social\Contracts;
use App\Support\Social\PostData;
use App\Support\Social\PublishResult;

interface SocialDriver
{
    public function publish(PostData $data): PublishResult;
}
