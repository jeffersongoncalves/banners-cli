<?php

use App\Services\ConfigService;

beforeEach(function () {
    $this->tempDir = sys_get_temp_dir().'/banners-cli-test-'.uniqid();
    mkdir($this->tempDir, 0755, true);
    $this->service = new ConfigService($this->tempDir);
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

describe('ConfigService', function () {
    it('returns config path', function () {
        expect($this->service->configPath())->toContain('config.json');
        expect($this->service->configPath())->toContain('banners-cli-test-');
    });

    it('reports not exists when no config file', function () {
        expect($this->service->exists())->toBeFalse();
    });

    it('returns empty array when no config', function () {
        expect($this->service->all())->toBe([]);
    });

    it('initializes config', function () {
        $this->service->init(['theme' => 'dark', 'fontSize' => '72px']);

        expect($this->service->exists())->toBeTrue();
        expect($this->service->all())->toBe(['theme' => 'dark', 'fontSize' => '72px']);
    });

    it('gets a value', function () {
        $this->service->init(['theme' => 'dark']);

        expect($this->service->get('theme'))->toBe('dark');
        expect($this->service->get('missing'))->toBeNull();
        expect($this->service->get('missing', 'default'))->toBe('default');
    });

    it('sets a value', function () {
        $this->service->init(['theme' => 'light']);
        $this->service->set('theme', 'dark');

        expect($this->service->get('theme'))->toBe('dark');
    });

    it('adds new key via set', function () {
        $this->service->init([]);
        $this->service->set('fontSize', '48px');

        expect($this->service->get('fontSize'))->toBe('48px');
    });

    it('validates keys', function () {
        expect($this->service->isValidKey('theme'))->toBeTrue();
        expect($this->service->isValidKey('style'))->toBeTrue();
        expect($this->service->isValidKey('pattern'))->toBeTrue();
        expect($this->service->isValidKey('fontSize'))->toBeTrue();
        expect($this->service->isValidKey('invalid'))->toBeFalse();
        expect($this->service->isValidKey(''))->toBeFalse();
    });

    it('returns valid keys list', function () {
        $keys = $this->service->validKeys();

        expect($keys)->toContain('theme');
        expect($keys)->toContain('style');
        expect($keys)->toContain('pattern');
        expect($keys)->toContain('fontSize');
        expect($keys)->toContain('packageManager');
        expect($keys)->toContain('packageName');
        expect($keys)->toContain('description');
        expect($keys)->toContain('md');
        expect($keys)->toContain('showWatermark');
        expect($keys)->toContain('images');
        expect($keys)->toContain('fileType');
    });

    it('creates directory if not exists', function () {
        $nestedDir = $this->tempDir.'/nested/config';
        $service = new ConfigService($nestedDir);
        $service->init(['theme' => 'dark']);

        expect(is_dir($nestedDir))->toBeTrue();
        expect($service->exists())->toBeTrue();

        // Cleanup
        unlink($nestedDir.DIRECTORY_SEPARATOR.'config.json');
        rmdir($nestedDir);
        rmdir($this->tempDir.'/nested');
    });
});
