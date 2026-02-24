<?php

use App\Enums\FileType;
use App\Enums\Pattern;
use App\Enums\Style;
use App\Enums\Theme;

describe('Theme', function () {
    it('has correct cases', function () {
        expect(Theme::cases())->toHaveCount(2);
        expect(Theme::Light->value)->toBe('light');
        expect(Theme::Dark->value)->toBe('dark');
    });

    it('returns labels', function () {
        expect(Theme::Light->label())->toBe('Light');
        expect(Theme::Dark->label())->toBe('Dark');
    });

    it('creates from value', function () {
        expect(Theme::from('light'))->toBe(Theme::Light);
        expect(Theme::from('dark'))->toBe(Theme::Dark);
    });

    it('throws for invalid value', function () {
        Theme::from('invalid');
    })->throws(ValueError::class);
});

describe('Style', function () {
    it('has correct cases', function () {
        expect(Style::cases())->toHaveCount(2);
        expect(Style::Style1->value)->toBe('style_1');
        expect(Style::Style2->value)->toBe('style_2');
    });

    it('returns labels', function () {
        expect(Style::Style1->label())->toBe('Style 1');
        expect(Style::Style2->label())->toBe('Style 2');
    });
});

describe('Pattern', function () {
    it('has many cases', function () {
        expect(count(Pattern::cases()))->toBeGreaterThan(20);
    });

    it('can create from common values', function () {
        expect(Pattern::from('circuitBoard'))->toBe(Pattern::CircuitBoard);
        expect(Pattern::from('texture'))->toBe(Pattern::Texture);
        expect(Pattern::from('topography'))->toBe(Pattern::Topography);
    });

    it('returns labels', function () {
        expect(Pattern::CircuitBoard->label())->toBe('Circuit Board');
        expect(Pattern::Texture->label())->toBe('Texture');
    });
});

describe('FileType', function () {
    it('has correct cases', function () {
        expect(FileType::cases())->toHaveCount(2);
        expect(FileType::Png->value)->toBe('png');
        expect(FileType::Jpeg->value)->toBe('jpeg');
    });

    it('returns labels', function () {
        expect(FileType::Png->label())->toBe('PNG');
        expect(FileType::Jpeg->label())->toBe('JPEG');
    });

    it('returns content types', function () {
        expect(FileType::Png->contentType())->toBe('image/png');
        expect(FileType::Jpeg->contentType())->toBe('image/jpeg');
    });
});
