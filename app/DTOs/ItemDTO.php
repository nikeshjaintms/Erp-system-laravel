<?php

namespace App\DTOs;

use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;

readonly class ItemDTO
{
    public function __construct(
        public string $item_code,
        public string $name,
        public ?string $description,
        public string $unit,
        public ?string $category,
        public float $opening_stock,
        public ?string $opening_stock_date,
        public float $reorder_level,
        public float $cost_price,
        public float $unit_price,
        public bool $is_active = true,
    ) {}

    /**
     * Instantiate DTO from a Store or Update FormRequest.
     */
    public static function fromRequest(StoreItemRequest|UpdateItemRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            item_code: strtoupper((string) $validated['item_code']),
            name: (string) $validated['name'],
            description: isset($validated['description']) ? (string) $validated['description'] : null,
            unit: strtoupper((string) $validated['unit']),
            category: isset($validated['category']) ? (string) $validated['category'] : null,
            opening_stock: (float) ($validated['opening_stock'] ?? 0),
            opening_stock_date: $validated['opening_stock_date'] ?? now()->toDateString(),
            reorder_level: (float) ($validated['reorder_level'] ?? 0),
            cost_price: (float) ($validated['cost_price'] ?? 0),
            unit_price: (float) ($validated['unit_price'] ?? 0),
            is_active: $request->boolean('is_active', true),
        );
    }

    /**
     * Convert DTO to array for model persistence.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'item_code' => $this->item_code,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'category' => $this->category,
            'opening_stock' => $this->opening_stock,
            'opening_stock_date' => $this->opening_stock_date,
            'reorder_level' => $this->reorder_level,
            'cost_price' => $this->cost_price,
            'unit_price' => $this->unit_price,
            'is_active' => $this->is_active,
        ];
    }
}
