<?php

namespace App\Enums;

enum UserStatus: string
{
    //
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'active',
            self::INACTIVE => 'inactive',
            self::SUSPENDED => 'suspended',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'badge bg-success-subtle text-success border border-success-subtle',
            self::INACTIVE => 'badge bg-secondary-subtle text-secondary border border-secondary-subtle',
            self::SUSPENDED => 'badge bg-danger-subtle  text-danger border border-danger-subtle',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
