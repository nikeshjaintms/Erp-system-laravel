<?php

namespace App\Enums;

enum StockMovementType: string
{
    case Received = 'received';
    case Issued = 'issued';
    case Returned = 'returned';
    case Adjustment = 'adjustment';

    /**
     * Check if the movement increases stock.
     */
    public function isInbound(?AdjustmentDirection $direction = null): bool
    {
        return match ($this) {
            self::Received, self::Returned => true,
            self::Issued => false,
            self::Adjustment => $direction === AdjustmentDirection::Increase,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Received',
            self::Issued => 'Issued',
            self::Returned => 'Returned',
            self::Adjustment => 'Adjustment',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Received => 'success',
            self::Issued => 'danger',
            self::Returned => 'info',
            self::Adjustment => 'warning',
        };
    }
}
