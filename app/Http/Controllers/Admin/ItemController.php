<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\ItemDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function __construct(
        protected ItemService $itemService
    ) {}

    /**
     * Display a listing of items.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Item::class);

        $filters = $request->only(['search', 'category', 'status', 'sort_by', 'sort_direction']);
        $items = $this->itemService->getPaginatedItems($filters, 15);
        $metrics = $this->itemService->getInventoryMetrics();
        $categories = ['Mechanical Parts', 'Electronics & Motors', 'Hydraulics', 'Pneumatics', 'Hardware & Fasteners', 'Sensors & Automation'];

        return view('admin.items.index', compact('items', 'metrics', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create(): View
    {
        Gate::authorize('create', Item::class);

        $categories = ['Mechanical Parts', 'Electronics & Motors', 'Hydraulics', 'Pneumatics', 'Hardware & Fasteners', 'Sensors & Automation'];
        $units = ['PCS', 'KG', 'MTR', 'BOX', 'SET', 'LTR', 'ROLL'];

        return view('admin.items.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        $item = $this->itemService->createItem(ItemDTO::fromRequest($request));

        return redirect()
            ->route('admin.items.index')
            ->with('success', "Item {$item->item_code} ({$item->name}) registered successfully.");
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item): RedirectResponse
    {
        Gate::authorize('view', $item);

        return redirect()->route('admin.stock.history', $item);
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item): View
    {
        Gate::authorize('update', $item);

        $categories = ['Mechanical Parts', 'Electronics & Motors', 'Hydraulics', 'Pneumatics', 'Hardware & Fasteners', 'Sensors & Automation'];
        $units = ['PCS', 'KG', 'MTR', 'BOX', 'SET', 'LTR', 'ROLL'];

        return view('admin.items.edit', compact('item', 'categories', 'units'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $this->itemService->updateItem($item, ItemDTO::fromRequest($request));

        return redirect()
            ->route('admin.items.index')
            ->with('success', "Item {$item->item_code} updated successfully.");
    }

    /**
     * Soft-disable or toggle status for the specified item.
     */
    public function destroy(Item $item): RedirectResponse
    {
        Gate::authorize('delete', $item);

        $updated = $this->itemService->toggleActive($item);
        $statusMsg = $updated->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.items.index')
            ->with('success', "Item {$item->item_code} {$statusMsg} successfully.");
    }
}
