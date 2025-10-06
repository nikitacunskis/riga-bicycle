<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Social\Drivers\XDriver;
use App\Support\Social\Drivers\FacebookDriver;
use App\Support\Social\Drivers\TelegramDriver;
use App\Support\Social\SocialPoster;
use App\Services\SocialCardGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SocialPoster::class, function ($app) {
            return new SocialPoster([
                'x'         => new XDriver(),
                'facebook'  => new FacebookDriver(),
                'telegram'  => new TelegramDriver(),
            ]);
        });
        $this->app->singleton(SocialCardGenerator::class, fn() => new SocialCardGenerator());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
