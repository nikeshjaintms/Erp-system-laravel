<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::findOrCreate('manage-inventory', 'web');

        $this->user = User::factory()->create();
        $this->user->givePermissionTo('manage-inventory');
    }

    public function test_stock_ledger_index_renders_successfully(): void
    {
        Item::factory()->count(4)->create();

        $response = $this->actingAs($this->user)->get(route('admin.stock.index'));

        $response->assertOk();
        $response->assertViewIs('admin.stock.index');
        $response->assertSee('Stock Ledger');
    }

    public function test_stock_movement_history_page_renders(): void
    {
        $item = Item::factory()->create(['opening_stock' => 10, 'current_stock' => 10]);

        $response = $this->actingAs($this->user)->get(route('admin.stock.history', $item));

        $response->assertOk();
        $response->assertViewIs('admin.stock.history');
        $response->assertSee($item->item_code);
    }

    public function test_adjusting_stock_upward_updates_balance_and_creates_movement(): void
    {
        $item = Item::factory()->create(['current_stock' => 20]);

        $response = $this->actingAs($this->user)->post(route('admin.stock.adjust.store', $item), [
            'new_quantity' => 35,
            'reason' => 'Annual physical audit surplus',
        ]);

        $response->assertRedirect(route('admin.stock.history', $item));
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals(35.0, (float) $item->current_stock);

        $movement = StockMovement::where('item_id', $item->id)->latest('id')->first();
        $this->assertNotNull($movement);
        $this->assertEquals('adjustment', $movement->type->value);
        $this->assertEquals('increase', $movement->adjustment_direction->value);
        $this->assertEquals(15.0, (float) $movement->quantity);
        $this->assertEquals(35.0, (float) $movement->balance_after);
    }

    public function test_adjusting_stock_downward_updates_balance_and_creates_movement(): void
    {
        $item = Item::factory()->create(['current_stock' => 50]);

        $response = $this->actingAs($this->user)->post(route('admin.stock.adjust.store', $item), [
            'new_quantity' => 40,
            'reason' => 'Damaged boxes write-off',
        ]);

        $response->assertRedirect(route('admin.stock.history', $item));
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals(40.0, (float) $item->current_stock);

        $movement = StockMovement::where('item_id', $item->id)->latest('id')->first();
        $this->assertNotNull($movement);
        $this->assertEquals('adjustment', $movement->type->value);
        $this->assertEquals('decrease', $movement->adjustment_direction->value);
        $this->assertEquals(10.0, (float) $movement->quantity);
        $this->assertEquals(40.0, (float) $movement->balance_after);
    }

    public function test_adjusting_stock_with_negative_quantity_fails_validation(): void
    {
        $item = Item::factory()->create(['current_stock' => 50]);

        $response = $this->actingAs($this->user)->post(route('admin.stock.adjust.store', $item), [
            'new_quantity' => -5,
            'reason' => 'Invalid negative count',
        ]);

        $response->assertSessionHasErrors('new_quantity');
    }
}
