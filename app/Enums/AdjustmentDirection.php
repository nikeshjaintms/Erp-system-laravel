<?php

namespace App\Enums;

enum AdjustmentDirection: string
{
    case Increase = 'increase';
    case Decrease = 'decrease';

    public function label(): string
    {
        return match ($this) {
            self::Increase => 'Stock Addition (+)',
            self::Decrease => 'Stock Deduction (-)',
        };
    }
}
