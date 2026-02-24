<?php

declare(strict_types=1);

namespace App\Enums;

enum FileType: string
{
    case Png = 'png';
    case Jpeg = 'jpeg';

    public function label(): string
    {
        return match ($this) {
            self::Png => 'PNG',
            self::Jpeg => 'JPEG',
        };
    }

    public function contentType(): string
    {
        return match ($this) {
            self::Png => 'image/png',
            self::Jpeg => 'image/jpeg',
        };
    }
}
