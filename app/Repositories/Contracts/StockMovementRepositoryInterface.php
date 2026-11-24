<?php

namespace App\Repositories\Contracts;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StockMovementRepositoryInterface
{
    /**
     * Create and record a stock movement.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): StockMovement;

    /**
     * Get paginated movement history for a specific item.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<StockMovement>
     */
    public function historyForItem(Item $item, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get the latest balance snapshot recorded for an item.
     */
    public function latestBalance(Item $item): float;
}
