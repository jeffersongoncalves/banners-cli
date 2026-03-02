<?php

use App\Services\ConfigService;

it('shows wizard title', function () {
    $tempDir = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true);
    @mkdir($tempDir, 0755, true);
    $this->app->instance(ConfigService::class, new ConfigService($tempDir));

    // ConfigInit uses Laravel Prompts which require interactive terminal.
    // We test that the command is registered and can be called.
    $this->artisan('config:init')
        ->expectsOutputToContain('Configuration Wizard');

    // Cleanup
    $configFile = $tempDir.DIRECTORY_SEPARATOR.'config.json';
    if (file_exists($configFile)) {
        unlink($configFile);
    }
    if (is_dir($tempDir)) {
        rmdir($tempDir);
    }
})->skip(PHP_OS_FAMILY === 'Windows', 'Interactive prompts require TTY');
