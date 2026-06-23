<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\BannerService;
use App\Services\ConfigService;
use Illuminate\Support\ServiceProvider;
use JeffersonGoncalves\LaravelZero\SelfUpdate\PharUpdater;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->app->singleton(ConfigService::class);
        $this->app->singleton(BannerService::class);

        $this->app->singleton(PharUpdater::class, fn () => new PharUpdater(
            githubRepo: 'jeffersongoncalves/banners-cli',
            assetName: 'banners.phar',
            tempPrefix: 'banners_',
            currentVersion: (string) config('app.version', 'unreleased'),
        ));
    }
}
