<?php

namespace App\Services;

use App\DTOs\ItemDTO;
use App\Enums\StockMovementType;
use App\Models\Item;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ItemService
{
    public function __construct(
        protected ItemRepositoryInterface $itemRepository,
        protected StockService $stockService
    ) {}

    /**
     * Create a new item and record initial opening stock movement if applicable.
     */
    public function createItem(ItemDTO $dto, ?int $userId = null): Item
    {
        return DB::transaction(function () use ($dto, $userId) {
            $data = $dto->toArray();
            // Start current stock at 0 so postMovement accurately increases it to opening_stock
            $data['current_stock'] = 0;

            $item = $this->itemRepository->create($data);

            if ($dto->opening_stock > 0) {
                $this->stockService->postMovement(
                    item: $item,
                    type: StockMovementType::Received,
                    quantity: $dto->opening_stock,
                    remarks: 'Opening Stock',
                    reference: null,
                    userId: $userId
                );
            }

            activity()
                ->performedOn($item)
                ->causedBy($userId ?? auth()->user())
                ->withProperties([
                    'item_code' => $item->item_code,
                    'opening_stock' => $dto->opening_stock,
                    'unit' => $item->unit,
                ])
                ->log("Registered new item {$item->name} ({$item->item_code}) with opening stock {$dto->opening_stock}");

            return $item;
        });
    }

    /**
     * Update an item's details.
     */
    public function updateItem(Item $item, ItemDTO $dto, ?int $userId = null): Item
    {
        $updateData = [
            'item_code' => $dto->item_code,
            'name' => $dto->name,
            'description' => $dto->description,
            'unit' => $dto->unit,
            'category' => $dto->category,
            'reorder_level' => $dto->reorder_level,
            'cost_price' => $dto->cost_price,
            'unit_price' => $dto->unit_price,
            'is_active' => $dto->is_active,
        ];

        $this->itemRepository->update($item, $updateData);

        activity()
            ->performedOn($item)
            ->causedBy($userId ?? auth()->user())
            ->withProperties($updateData)
            ->log("Updated item master details for {$item->item_code} ({$item->name})");

        return $item->fresh();
    }

    /**
     * Toggle active/inactive status of an item.
     */
    public function toggleActive(Item $item, ?int $userId = null): Item
    {
        $newStatus = ! $item->is_active;
        $this->itemRepository->update($item, ['is_active' => $newStatus]);

        $statusText = $newStatus ? 'activated' : 'deactivated';

        activity()
            ->performedOn($item)
            ->causedBy($userId ?? auth()->user())
            ->withProperties(['is_active' => $newStatus])
            ->log("Item {$item->item_code} {$statusText}");

        return $item->fresh();
    }

    /**
     * Find item by ID.
     */
    public function getItem(int $id): ?Item
    {
        return $this->itemRepository->find($id);
    }

    /**
     * Find item by item code.
     */
    public function getItemByCode(string $code): ?Item
    {
        return $this->itemRepository->findByCode($code);
    }

    /**
     * Retrieve paginated items with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getPaginatedItems(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->itemRepository->paginateWithFilters($filters, $perPage);
    }

    /**
     * Get aggregate inventory metrics.
     *
     * @return array<string, mixed>
     */
    public function getInventoryMetrics(): array
    {
        return [
            'total_skus' => $this->itemRepository->count(),
            'total_stock' => $this->itemRepository->totalCurrentStock(),
            'low_stock_count' => $this->itemRepository->lowStockCount(),
            'out_of_stock_count' => $this->itemRepository->outOfStockCount(),
        ];
    }
}
