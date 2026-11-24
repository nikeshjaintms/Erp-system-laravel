<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository implements ItemRepositoryInterface
{
    /**
     * Get all active items.
     *
     * @return Collection<int, Item>
     */
    public function all(): Collection
    {
        return Item::query()
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * Find item by ID.
     */
    public function find(int $id): ?Item
    {
        return Item::query()->find($id);
    }

    /**
     * Find item by ID with pessimistic write lock (FOR UPDATE).
     */
    public function findForUpdate(int $id): ?Item
    {
        return Item::query()->where('id', $id)->lockForUpdate()->first();
    }

    /**
     * Find item by item code.
     */
    public function findByCode(string $code): ?Item
    {
        return Item::query()->where('item_code', $code)->first();
    }

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Item
    {
        return Item::query()->create($attributes);
    }

    /**
     * Update an existing item.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(Item $item, array $attributes): bool
    {
        return $item->update($attributes);
    }

    /**
     * Paginate items with optional filters.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<Item>
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Item::query();

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', (string) $filters['category']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            if ($filters['status'] === 'low_stock') {
                $query->lowStock()->where('current_stock', '>', 0);
            } elseif ($filters['status'] === 'out_of_stock') {
                $query->where('current_stock', '<=', 0);
            } elseif ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $sortField = $filters['sort_by'] ?? 'id';
        $sortDirection = ($filters['sort_direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if (in_array($sortField, ['id', 'item_code', 'name', 'current_stock', 'reorder_level', 'cost_price', 'unit_price', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('id');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get all items currently at or below reorder level.
     *
     * @return Collection<int, Item>
     */
    public function lowStock(): Collection
    {
        return Item::query()
            ->active()
            ->lowStock()
            ->orderBy('current_stock', 'asc')
            ->get();
    }

    /**
     * Total count of all items.
     */
    public function count(): int
    {
        return Item::query()->count();
    }

    /**
     * Sum of total current stock units in inventory.
     */
    public function totalCurrentStock(): float
    {
        return (float) Item::query()->sum('current_stock');
    }

    /**
     * Count of items with low stock.
     */
    public function lowStockCount(): int
    {
        return Item::query()
            ->active()
            ->where('current_stock', '>', 0)
            ->whereColumn('current_stock', '<=', 'reorder_level')
            ->count();
    }

    /**
     * Count of items completely out of stock.
     */
    public function outOfStockCount(): int
    {
        return Item::query()
            ->active()
            ->where('current_stock', '<=', 0)
            ->count();
    }
}
