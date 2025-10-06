<?php
namespace App\Support\Social\Drivers;
use Alihesari\Larasap\Services\Telegram\Api as Telegram;
use App\Support\Social\Contracts\SocialDriver;
use App\Support\Social\{PostData, PublishResult};

class TelegramDriver implements SocialDriver
{
    public function publish(PostData $data): PublishResult
    {
        try {
            if ($data->media) {
                $res = Telegram::sendPhoto(null, $data->media, $data->text);
            } else {
                $res = Telegram::sendMessage(null, $data->text);
            }
            return new PublishResult(true, (string)($res['message_id'] ?? null), $res);
        } catch (\Throwable $e) {
            return new PublishResult(false, null, null, $e->getMessage());
        }
    }
}
