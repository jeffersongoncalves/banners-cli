<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\FileType;
use App\Enums\Pattern;
use App\Enums\Style;
use App\Enums\Theme;

final readonly class BannerOptions
{
    public function __construct(
        public string $name,
        public Theme $theme = Theme::Light,
        public Style $style = Style::Style1,
        public Pattern $pattern = Pattern::CircuitBoard,
        public string $fontSize = '96px',
        public string $packageManager = '',
        public string $packageName = '',
        public string $description = '',
        public bool $md = false,
        public bool $showWatermark = false,
        public array $images = [],
        public FileType $fileType = FileType::Png,
        public ?int $width = null,
        public ?int $height = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            theme: isset($data['theme']) ? Theme::from($data['theme']) : Theme::Light,
            style: isset($data['style']) ? Style::from($data['style']) : Style::Style1,
            pattern: isset($data['pattern']) ? Pattern::from($data['pattern']) : Pattern::CircuitBoard,
            fontSize: $data['fontSize'] ?? '96px',
            packageManager: $data['packageManager'] ?? '',
            packageName: $data['packageName'] ?? '',
            description: $data['description'] ?? '',
            md: filter_var($data['md'] ?? false, FILTER_VALIDATE_BOOLEAN),
            showWatermark: filter_var($data['showWatermark'] ?? false, FILTER_VALIDATE_BOOLEAN),
            images: (array) ($data['images'] ?? []),
            fileType: isset($data['fileType']) ? FileType::from($data['fileType']) : FileType::Png,
            width: isset($data['width']) ? (int) $data['width'] : null,
            height: isset($data['height']) ? (int) $data['height'] : null,
        );
    }

    public function mergeWithConfig(array $config): self
    {
        return new self(
            name: $this->name,
            theme: isset($config['theme']) ? Theme::from($config['theme']) : $this->theme,
            style: isset($config['style']) ? Style::from($config['style']) : $this->style,
            pattern: isset($config['pattern']) ? Pattern::from($config['pattern']) : $this->pattern,
            fontSize: $config['fontSize'] ?? $this->fontSize,
            packageManager: $config['packageManager'] ?? $this->packageManager,
            packageName: $config['packageName'] ?? $this->packageName,
            description: $config['description'] ?? $this->description,
            md: isset($config['md']) ? filter_var($config['md'], FILTER_VALIDATE_BOOLEAN) : $this->md,
            showWatermark: isset($config['showWatermark']) ? filter_var($config['showWatermark'], FILTER_VALIDATE_BOOLEAN) : $this->showWatermark,
            images: ! empty($config['images']) ? (array) $config['images'] : $this->images,
            fileType: isset($config['fileType']) ? FileType::from($config['fileType']) : $this->fileType,
            width: isset($config['width']) ? (int) $config['width'] : $this->width,
            height: isset($config['height']) ? (int) $config['height'] : $this->height,
        );
    }

    public function toQueryString(): string
    {
        $params = [
            'theme' => $this->theme->value,
            'style' => $this->style->value,
            'pattern' => $this->pattern->value,
            'fontSize' => $this->fontSize,
            'packageManager' => $this->packageManager,
            'packageName' => $this->packageName,
            'description' => $this->description,
            'md' => $this->md ? '1' : '0',
            'showWatermark' => $this->showWatermark ? '1' : '0',
        ];

        foreach ($this->images as $image) {
            $params['images'] = $image;
        }

        return http_build_query($params);
    }

    public function needsResize(): bool
    {
        return $this->width !== null || $this->height !== null;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'theme' => $this->theme->value,
            'style' => $this->style->value,
            'pattern' => $this->pattern->value,
            'fontSize' => $this->fontSize,
            'packageManager' => $this->packageManager,
            'packageName' => $this->packageName,
            'description' => $this->description,
            'md' => $this->md,
            'showWatermark' => $this->showWatermark,
            'images' => $this->images,
            'fileType' => $this->fileType->value,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
