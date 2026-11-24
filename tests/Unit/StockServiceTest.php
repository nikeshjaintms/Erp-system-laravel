<?php

namespace Tests\Unit;

use App\Enums\AdjustmentDirection;
use App\Enums\StockMovementType;
use App\Exceptions\InsufficientStockException;
use App\Models\Item;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StockService $stockService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stockService = app(StockService::class);
    }

    public function test_post_movement_received_increases_balance(): void
    {
        $item = Item::factory()->create(['current_stock' => 10]);

        $movement = $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Received,
            quantity: 15,
            remarks: 'PO Delivery Receipt'
        );

        $this->assertEquals(25.0, (float) $movement->balance_after);
        $this->assertEquals(25.0, (float) $item->refresh()->current_stock);
    }

    public function test_post_movement_returned_increases_balance(): void
    {
        $item = Item::factory()->create(['current_stock' => 20]);

        $movement = $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Returned,
            quantity: 5,
            remarks: 'Customer Return'
        );

        $this->assertEquals(25.0, (float) $movement->balance_after);
        $this->assertEquals(25.0, (float) $item->refresh()->current_stock);
    }

    public function test_post_movement_issued_decreases_balance(): void
    {
        $item = Item::factory()->create(['current_stock' => 50]);

        $movement = $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Issued,
            quantity: 20,
            remarks: 'Challan Dispatch'
        );

        $this->assertEquals(30.0, (float) $movement->balance_after);
        $this->assertEquals(30.0, (float) $item->refresh()->current_stock);
    }

    public function test_post_movement_adjustment_increase(): void
    {
        $item = Item::factory()->create(['current_stock' => 40]);

        $movement = $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Adjustment,
            quantity: 10,
            remarks: 'Audit surplus',
            adjustmentDirection: AdjustmentDirection::Increase
        );

        $this->assertEquals(50.0, (float) $movement->balance_after);
        $this->assertEquals(50.0, (float) $item->refresh()->current_stock);
    }

    public function test_post_movement_adjustment_decrease(): void
    {
        $item = Item::factory()->create(['current_stock' => 40]);

        $movement = $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Adjustment,
            quantity: 15,
            remarks: 'Damaged item deduction',
            adjustmentDirection: AdjustmentDirection::Decrease
        );

        $this->assertEquals(25.0, (float) $movement->balance_after);
        $this->assertEquals(25.0, (float) $item->refresh()->current_stock);
    }

    public function test_issuing_more_than_current_stock_throws_insufficient_stock_exception(): void
    {
        $item = Item::factory()->create(['current_stock' => 10, 'name' => 'Hydraulic Valve']);

        $this->expectException(InsufficientStockException::class);
        $this->expectExceptionMessage("Insufficient stock for 'Hydraulic Valve'. Available: 10, Requested: 15.");

        $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Issued,
            quantity: 15
        );
    }

    public function test_post_movement_with_zero_or_negative_quantity_throws_invalid_argument_exception(): void
    {
        $item = Item::factory()->create(['current_stock' => 10]);

        $this->expectException(InvalidArgumentException::class);

        $this->stockService->postMovement(
            item: $item,
            type: StockMovementType::Received,
            quantity: 0
        );
    }

    public function test_adjust_stock_delta_computation(): void
    {
        $item = Item::factory()->create(['current_stock' => 100]);

        $this->stockService->adjustStock(
            item: $item,
            newQuantity: 85,
            reason: 'Audit correction'
        );

        $this->assertEquals(85.0, (float) $item->refresh()->current_stock);
    }
}
