<?php

declare(strict_types=1);

namespace App\Enums;

enum Style: string
{
    case Style1 = 'style_1';
    case Style2 = 'style_2';

    public function label(): string
    {
        return match ($this) {
            self::Style1 => 'Style 1',
            self::Style2 => 'Style 2',
        };
    }
}
