<?php

use App\Services\ConfigService;

beforeEach(function () {
    $this->tempDir = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true);
    @mkdir($this->tempDir, 0755, true);
    $this->configService = new ConfigService($this->tempDir);
    $this->configService->init(['theme' => 'light']);
    $this->app->instance(ConfigService::class, $this->configService);
});

afterEach(function () {
    $configFile = $this->tempDir.DIRECTORY_SEPARATOR.'config.json';
    if (file_exists($configFile)) {
        unlink($configFile);
    }
    if (is_dir($this->tempDir)) {
        rmdir($this->tempDir);
    }
});

it('sets a valid config key', function () {
    $this->artisan('config:set', ['key' => 'theme', 'value' => 'dark'])
        ->expectsOutputToContain('Set theme')
        ->assertExitCode(0);

    expect($this->configService->get('theme'))->toBe('dark');
});

it('sets boolean config keys', function () {
    $this->artisan('config:set', ['key' => 'md', 'value' => 'true'])
        ->assertExitCode(0);

    expect($this->configService->get('md'))->toBeTrue();
});

it('rejects invalid config key', function () {
    $this->artisan('config:set', ['key' => 'invalidKey', 'value' => 'test'])
        ->expectsOutputToContain('Invalid configuration key')
        ->assertExitCode(1);
});
