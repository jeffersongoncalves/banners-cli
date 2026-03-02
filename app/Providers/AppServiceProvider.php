<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\BannerService;
use App\Services\ConfigService;
use App\Services\SelfUpdateService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(SelfUpdateService::class);
    }
}
