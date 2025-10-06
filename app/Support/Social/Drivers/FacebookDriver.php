<?php

namespace App\Support\Social\Drivers;

use Alihesari\Larasap\Services\Facebook\Api as Facebook;
use App\Support\Social\Contracts\SocialDriver;
use App\Support\Social\{PostData, PublishResult};

class FacebookDriver implements SocialDriver
{
    public function publish(PostData $data): PublishResult
    {
        try {
            if (!empty($data->link)) {
                $res = Facebook::sendLink($data->link, $data->text);
            } elseif (!empty($data->media)) {
                $res = Facebook::sendPhoto($data->media[0], $data->text);
            } else {
                throw new \RuntimeException('Facebook: provide link or media (no text-only endpoint in v2).');
            }

            $ok       = $res !== false && $res !== null;
            $remoteId = is_string($res) ? $res : null;
            $raw      = is_array($res) ? $res : ['result' => $res];

            return new PublishResult($ok, $remoteId, $raw);
        } catch (\Throwable $e) {
            return new PublishResult(false, null, null, $e->getMessage());
        }
    }
}
