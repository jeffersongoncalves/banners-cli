<?php

use App\DTOs\BannerOptions;
use App\Enums\FileType;
use App\Enums\Pattern;
use App\Enums\Style;
use App\Enums\Theme;

describe('BannerOptions', function () {
    it('creates with defaults', function () {
        $options = new BannerOptions(name: 'Test');

        expect($options->name)->toBe('Test');
        expect($options->theme)->toBe(Theme::Light);
        expect($options->style)->toBe(Style::Style1);
        expect($options->pattern)->toBe(Pattern::CircuitBoard);
        expect($options->fontSize)->toBe('96px');
        expect($options->packageManager)->toBe('');
        expect($options->packageName)->toBe('');
        expect($options->description)->toBe('');
        expect($options->md)->toBeFalse();
        expect($options->showWatermark)->toBeFalse();
        expect($options->images)->toBe([]);
        expect($options->fileType)->toBe(FileType::Png);
    });

    it('creates from array', function () {
        $options = BannerOptions::fromArray([
            'name' => 'My Banner',
            'theme' => 'dark',
            'style' => 'style_2',
            'pattern' => 'texture',
            'fontSize' => '72px',
            'packageManager' => 'composer require',
            'packageName' => 'vendor/pkg',
            'description' => 'A description',
            'md' => '1',
            'showWatermark' => true,
            'images' => ['heroicon-s-code-bracket'],
            'fileType' => 'jpeg',
        ]);

        expect($options->name)->toBe('My Banner');
        expect($options->theme)->toBe(Theme::Dark);
        expect($options->style)->toBe(Style::Style2);
        expect($options->pattern)->toBe(Pattern::Texture);
        expect($options->fontSize)->toBe('72px');
        expect($options->packageManager)->toBe('composer require');
        expect($options->packageName)->toBe('vendor/pkg');
        expect($options->description)->toBe('A description');
        expect($options->md)->toBeTrue();
        expect($options->showWatermark)->toBeTrue();
        expect($options->images)->toBe(['heroicon-s-code-bracket']);
        expect($options->fileType)->toBe(FileType::Jpeg);
    });

    it('creates from array with defaults for missing keys', function () {
        $options = BannerOptions::fromArray(['name' => 'Test']);

        expect($options->name)->toBe('Test');
        expect($options->theme)->toBe(Theme::Light);
        expect($options->fileType)->toBe(FileType::Png);
    });

    it('merges with config', function () {
        $options = new BannerOptions(name: 'Test');

        $merged = $options->mergeWithConfig([
            'theme' => 'dark',
            'pattern' => 'topography',
            'fontSize' => '48px',
        ]);

        expect($merged->name)->toBe('Test');
        expect($merged->theme)->toBe(Theme::Dark);
        expect($merged->pattern)->toBe(Pattern::Topography);
        expect($merged->fontSize)->toBe('48px');
        expect($merged->style)->toBe(Style::Style1);
    });

    it('generates query string', function () {
        $options = new BannerOptions(
            name: 'Test',
            theme: Theme::Dark,
            description: 'Hello World',
        );

        $qs = $options->toQueryString();

        expect($qs)->toContain('theme=dark');
        expect($qs)->toContain('description=Hello+World');
        expect($qs)->toContain('md=0');
        expect($qs)->toContain('showWatermark=0');
    });

    it('converts to array', function () {
        $options = new BannerOptions(name: 'Test', theme: Theme::Dark);
        $array = $options->toArray();

        expect($array)->toBeArray();
        expect($array['name'])->toBe('Test');
        expect($array['theme'])->toBe('dark');
        expect($array['style'])->toBe('style_1');
        expect($array['pattern'])->toBe('circuitBoard');
        expect($array['md'])->toBeFalse();
        expect($array['showWatermark'])->toBeFalse();
        expect($array['images'])->toBe([]);
        expect($array['fileType'])->toBe('png');
    });
});
