<?php

namespace App\Services;

use App\Enums\AdjustmentDirection;
use App\Enums\StockMovementType;
use App\Exceptions\InsufficientStockException;
use App\Models\Item;
use App\Models\StockMovement;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Repositories\Contracts\StockMovementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockService
{
    public function __construct(
        protected ItemRepositoryInterface $itemRepository,
        protected StockMovementRepositoryInterface $stockMovementRepository
    ) {}

    /**
     * Post an atomic stock movement with concurrency protection.
     *
     * @throws InsufficientStockException
     * @throws InvalidArgumentException
     */
    public function postMovement(
        Item $item,
        StockMovementType $type,
        float $quantity,
        ?string $remarks = null,
        ?Model $reference = null,
        ?int $userId = null,
        ?AdjustmentDirection $adjustmentDirection = null
    ): StockMovement {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Stock movement quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($item, $type, $quantity, $remarks, $reference, $userId, $adjustmentDirection) {
            /** @var Item $lockedItem */
            $lockedItem = $this->itemRepository->findForUpdate($item->id);

            if (! $lockedItem) {
                throw new InvalidArgumentException("Item #{$item->id} could not be locked for stock transaction.");
            }

            $currentBalance = (float) $lockedItem->current_stock;
            $isInbound = $type->isInbound($adjustmentDirection);

            if (! $isInbound) {
                if ($quantity > $currentBalance) {
                    throw new InsufficientStockException($lockedItem->name, $currentBalance, $quantity);
                }
                $newBalance = $currentBalance - $quantity;
            } else {
                $newBalance = $currentBalance + $quantity;
            }

            // Atomically update current stock on items table
            $this->itemRepository->update($lockedItem, [
                'current_stock' => $newBalance,
            ]);

            // Create movement record with snapshot balance_after
            $movement = $this->stockMovementRepository->create([
                'item_id' => $lockedItem->id,
                'type' => $type,
                'adjustment_direction' => $adjustmentDirection,
                'quantity' => $quantity,
                'balance_after' => $newBalance,
                'reference_type' => $reference ? $reference->getMorphClass() : null,
                'reference_id' => $reference?->getKey(),
                'remarks' => $remarks,
                'created_by' => $userId ?? auth()->id(),
            ]);

            // Log activity via Spatie Activitylog
            activity()
                ->performedOn($lockedItem)
                ->causedBy($userId ?? auth()->user())
                ->withProperties([
                    'type' => $type->value,
                    'direction' => $adjustmentDirection?->value,
                    'quantity' => $quantity,
                    'balance_after' => $newBalance,
                    'remarks' => $remarks,
                ])
                ->log("Posted {$type->value} stock movement of {$quantity} {$lockedItem->unit} for item {$lockedItem->item_code}");

            $item->current_stock = $newBalance;

            return $movement;
        });
    }

    /**
     * Convenience wrapper to adjust stock balance to a specific target quantity.
     *
     * @throws InsufficientStockException
     * @throws InvalidArgumentException
     */
    public function adjustStock(Item $item, float $newQuantity, string $reason, ?int $userId = null): StockMovement
    {
        if ($newQuantity < 0) {
            throw new InvalidArgumentException('New stock quantity cannot be negative.');
        }

        $currentStock = (float) $item->current_stock;
        $delta = round($newQuantity - $currentStock, 4);

        if ($delta == 0.0) {
            throw new InvalidArgumentException("New quantity ({$newQuantity}) is identical to current stock level.");
        }

        if ($delta > 0) {
            return $this->postMovement(
                item: $item,
                type: StockMovementType::Adjustment,
                quantity: abs($delta),
                remarks: $reason,
                reference: null,
                userId: $userId,
                adjustmentDirection: AdjustmentDirection::Increase
            );
        }

        return $this->postMovement(
            item: $item,
            type: StockMovementType::Adjustment,
            quantity: abs($delta),
            remarks: $reason,
            reference: null,
            userId: $userId,
            adjustmentDirection: AdjustmentDirection::Decrease
        );
    }

    /**
     * Retrieve paginated movement history for an item.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getMovementHistory(Item $item, array $filters = []): LengthAwarePaginator
    {
        return $this->stockMovementRepository->historyForItem($item, $filters);
    }
}
