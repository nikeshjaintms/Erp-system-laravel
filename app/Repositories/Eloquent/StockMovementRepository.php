<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Models\StockMovement;
use App\Repositories\Contracts\StockMovementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    /**
     * Create and record a stock movement.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): StockMovement
    {
        return StockMovement::query()->create($attributes);
    }

    /**
     * Get paginated movement history for a specific item.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<StockMovement>
     */
    public function historyForItem(Item $item, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $item->stockMovements()
            ->with(['creator', 'reference'])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get the latest balance snapshot recorded for an item.
     */
    public function latestBalance(Item $item): float
    {
        $latest = $item->stockMovements()->latest('id')->first();

        return $latest ? (float) $latest->balance_after : (float) $item->current_stock;
    }
}
