<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Social\SocialPoster;
use App\Support\Social\Drivers\{FacebookDriver, TelegramDriver, XDriver, InstagramDriver};
use Facebook\Facebook;

class SocialPosterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SocialPoster::class, function ($app) {
            $drivers = [
                'facebook' => $app->make(FacebookDriver::class),
                'telegram' => $app->make(TelegramDriver::class),
                'x'        => $app->make(XDriver::class),
            ];

            return new SocialPoster($drivers);
        });
    }
}
