<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ItemManagementTest extends TestCase
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

    public function test_items_index_page_can_be_rendered(): void
    {
        Item::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('admin.items.index'));

        $response->assertOk();
        $response->assertViewIs('admin.items.index');
        $response->assertSee('Item Master');
    }

    public function test_creating_an_item_posts_an_opening_stock_movement_with_correct_balance(): void
    {
        $payload = [
            'item_code' => 'ITM-TEST-001',
            'name' => 'Industrial Hydraulic Filter',
            'description' => 'High quality test filter',
            'unit' => 'PCS',
            'category' => 'Hydraulics',
            'opening_stock' => 50,
            'opening_stock_date' => now()->toDateString(),
            'reorder_level' => 10,
            'cost_price' => 100.00,
            'unit_price' => 150.00,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->user)->post(route('admin.items.store'), $payload);

        $response->assertRedirect(route('admin.items.index'));
        $response->assertSessionHas('success');

        $item = Item::where('item_code', 'ITM-TEST-001')->firstOrFail();
        $this->assertEquals(50.0, (float) $item->current_stock);
        $this->assertEquals('PCS', $item->unit);

        // Verify opening stock movement was posted
        $movement = StockMovement::where('item_id', $item->id)->first();
        $this->assertNotNull($movement);
        $this->assertEquals('received', $movement->type->value);
        $this->assertEquals(50.0, (float) $movement->quantity);
        $this->assertEquals(50.0, (float) $movement->balance_after);
        $this->assertEquals('Opening Stock', $movement->remarks);
    }

    public function test_duplicate_item_code_fails_validation(): void
    {
        Item::factory()->create(['item_code' => 'ITM-DUPLICATE']);

        $payload = [
            'item_code' => 'ITM-DUPLICATE',
            'name' => 'Another Item',
            'unit' => 'PCS',
            'opening_stock' => 10,
            'reorder_level' => 5,
            'cost_price' => 20,
            'unit_price' => 40,
        ];

        $response = $this->actingAs($this->user)->post(route('admin.items.store'), $payload);

        $response->assertSessionHasErrors('item_code');
    }

    public function test_updating_an_item_details(): void
    {
        $item = Item::factory()->create([
            'item_code' => 'ITM-EDIT-01',
            'name' => 'Original Name',
            'unit' => 'PCS',
        ]);

        $payload = [
            'item_code' => 'ITM-EDIT-01',
            'name' => 'Updated Name',
            'unit' => 'KG',
            'category' => 'Mechanical Parts',
            'reorder_level' => 25,
            'cost_price' => 60,
            'unit_price' => 120,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->user)->put(route('admin.items.update', $item), $payload);

        $response->assertRedirect(route('admin.items.index'));
        $item->refresh();
        $this->assertEquals('Updated Name', $item->name);
        $this->assertEquals('KG', $item->unit);
    }

    public function test_soft_deactivating_an_item(): void
    {
        $item = Item::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->user)->delete(route('admin.items.destroy', $item));

        $response->assertRedirect(route('admin.items.index'));
        $item->refresh();
        $this->assertFalse($item->is_active);
    }

    public function test_user_without_permission_cannot_access_items(): void
    {
        $unauthorizedUser = User::factory()->create();

        $response = $this->actingAs($unauthorizedUser)->get(route('admin.items.index'));
        $response->assertForbidden();
    }
}
