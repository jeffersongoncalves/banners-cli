<?php

declare(strict_types=1);

namespace App\Services;

use JeffersonGoncalves\LaravelZero\JsonConfig\JsonConfigService;
use JeffersonGoncalves\LaravelZero\JsonConfig\Scopes\GlobalScope;
use JeffersonGoncalves\LaravelZero\JsonConfig\Scopes\PerProjectScope;
use JeffersonGoncalves\LaravelZero\Support\Filesystem;

final class ConfigService
{
    private readonly JsonConfigService $config;

    public function __construct(?string $configDir = null)
    {
        $scope = $configDir === null
            ? new GlobalScope('banners-cli')
            : new PerProjectScope($configDir, 'config.json');

        $this->config = new JsonConfigService($scope);
    }

    public function configPath(): string
    {
        return $this->config->path();
    }

    public function exists(): bool
    {
        return file_exists($this->configPath());
    }

    public function all(): array
    {
        return $this->config->all();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config->get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        $this->config->set($key, $value);
    }

    public function init(array $defaults): void
    {
        Filesystem::writeJsonSecure($this->configPath(), $defaults);
    }

    public function validKeys(): array
    {
        return [
            'theme',
            'style',
            'pattern',
            'fontSize',
            'packageManager',
            'packageName',
            'description',
            'md',
            'showWatermark',
            'images',
            'fileType',
            'width',
            'height',
        ];
    }

    public function isValidKey(string $key): bool
    {
        return in_array($key, $this->validKeys(), true);
    }
}
