<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\AdjustStockRequest;
use App\Models\Item;
use App\Services\ItemService;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use InvalidArgumentException;

class StockController extends Controller
{
    public function __construct(
        protected StockService $stockService,
        protected ItemService $itemService
    ) {}

    /**
     * Display current stock balances across all items.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Item::class);

        $filters = $request->only(['search', 'category', 'status', 'sort_by', 'sort_direction']);
        $items = $this->itemService->getPaginatedItems($filters, 15);
        $metrics = $this->itemService->getInventoryMetrics();
        $categories = ['Mechanical Parts', 'Electronics & Motors', 'Hydraulics', 'Pneumatics', 'Hardware & Fasteners', 'Sensors & Automation'];

        return view('admin.stock.index', compact('items', 'metrics', 'categories', 'filters'));
    }

    /**
     * Display full movement history for a specific item.
     */
    public function history(Request $request, Item $item): View
    {
        Gate::authorize('view', $item);

        $filters = $request->only(['type', 'date_from', 'date_to']);
        $movements = $this->stockService->getMovementHistory($item, $filters);

        return view('admin.stock.history', compact('item', 'movements', 'filters'));
    }

    /**
     * Show the stock adjustment form for an item.
     */
    public function adjust(Item $item): View
    {
        Gate::authorize('adjustStock', $item);

        return view('admin.stock.adjust', compact('item'));
    }

    /**
     * Process stock adjustment.
     */
    public function storeAdjustment(AdjustStockRequest $request, Item $item): RedirectResponse
    {
        Gate::authorize('adjustStock', $item);

        try {
            $this->stockService->adjustStock(
                item: $item,
                newQuantity: (float) $request->validated('new_quantity'),
                reason: (string) $request->validated('reason')
            );

            return redirect()
                ->route('admin.stock.history', $item)
                ->with('success', "Stock balance for {$item->item_code} adjusted successfully to {$request->validated('new_quantity')} {$item->unit}.");
        } catch (InsufficientStockException|InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['new_quantity' => $e->getMessage()]);
        }
    }
}
