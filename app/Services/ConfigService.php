<?php

declare(strict_types=1);

namespace App\Services;

final class ConfigService
{
    private string $configDir;

    private string $configFile;

    public function __construct(?string $configDir = null)
    {
        $this->configDir = $configDir ?? $this->defaultConfigDir();
        $this->configFile = $this->configDir.DIRECTORY_SEPARATOR.'config.json';
    }

    public function configPath(): string
    {
        return $this->configFile;
    }

    public function exists(): bool
    {
        return file_exists($this->configFile);
    }

    public function all(): array
    {
        if (! $this->exists()) {
            return [];
        }

        $content = file_get_contents($this->configFile);

        if ($content === false) {
            return [];
        }

        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $config = $this->all();

        return $config[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $config = $this->all();
        $config[$key] = $value;

        $this->save($config);
    }

    public function init(array $defaults): void
    {
        $this->save($defaults);
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

    private function save(array $config): void
    {
        if (! is_dir($this->configDir)) {
            mkdir($this->configDir, 0755, true);
        }

        file_put_contents(
            $this->configFile,
            json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
        );
    }

    private function defaultConfigDir(): string
    {
        $home = PHP_OS_FAMILY === 'Windows'
            ? ($_SERVER['USERPROFILE'] ?? $_SERVER['HOMEDRIVE'].$_SERVER['HOMEPATH'])
            : ($_SERVER['HOME'] ?? '~');

        return $home.DIRECTORY_SEPARATOR.'.banners-cli';
    }
}
