<?php

use App\Services\ConfigService;

it('shows warning when no config exists', function () {
    $tempDir = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true);
    @mkdir($tempDir, 0755, true);
    $this->app->instance(ConfigService::class, new ConfigService($tempDir));

    $this->artisan('config:show')
        ->expectsOutputToContain('No configuration found')
        ->assertExitCode(0);

    rmdir($tempDir);
});

it('shows config table', function () {
    $tempDir = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true);
    @mkdir($tempDir, 0755, true);
    $service = new ConfigService($tempDir);
    $service->init(['theme' => 'dark', 'fontSize' => '72px', 'md' => true]);
    $this->app->instance(ConfigService::class, $service);

    $this->artisan('config:show')
        ->expectsOutputToContain('dark')
        ->expectsOutputToContain('72px')
        ->assertExitCode(0);

    unlink($tempDir.DIRECTORY_SEPARATOR.'config.json');
    rmdir($tempDir);
});
