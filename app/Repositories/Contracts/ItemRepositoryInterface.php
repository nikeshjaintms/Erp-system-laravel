<?php

namespace App\Repositories\Contracts;

use App\Models\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ItemRepositoryInterface
{
    /**
     * Get all active items.
     *
     * @return Collection<int, Item>
     */
    public function all(): Collection;

    /**
     * Find item by ID.
     */
    public function find(int $id): ?Item;

    /**
     * Find item by ID with pessimistic write lock (FOR UPDATE).
     */
    public function findForUpdate(int $id): ?Item;

    /**
     * Find item by item code.
     */
    public function findByCode(string $code): ?Item;

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Item;

    /**
     * Update an existing item.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(Item $item, array $attributes): bool;

    /**
     * Paginate items with optional filters.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<Item>
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all items currently at or below reorder level.
     *
     * @return Collection<int, Item>
     */
    public function lowStock(): Collection;

    /**
     * Total count of all items.
     */
    public function count(): int;

    /**
     * Sum of total current stock units in inventory.
     */
    public function totalCurrentStock(): float;

    /**
     * Count of items with low stock.
     */
    public function lowStockCount(): int;

    /**
     * Count of items completely out of stock.
     */
    public function outOfStockCount(): int;
}
