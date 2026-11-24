<?php

namespace App\Exceptions;

use DomainException;

class InsufficientStockException extends DomainException
{
    public function __construct(
        string $itemName,
        float|string $available,
        float|string $requested
    ) {
        parent::__construct(
            "Insufficient stock for '{$itemName}'. Available: {$available}, Requested: {$requested}."
        );
    }
}
