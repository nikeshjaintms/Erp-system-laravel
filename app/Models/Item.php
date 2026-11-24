<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'item_code',
        'name',
        'description',
        'unit',
        'category',
        'opening_stock',
        'opening_stock_date',
        'reorder_level',
        'cost_price',
        'unit_price',
        'current_stock',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opening_stock' => 'decimal:4',
            'opening_stock_date' => 'date',
            'reorder_level' => 'decimal:4',
            'cost_price' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'current_stock' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Stock movements for this item.
     *
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Scope items whose current stock is at or below their reorder level.
     *
     * @param  Builder<Item>  $query
     */
    public function scopeLowStock(Builder $query): void
    {
        $query->whereColumn('current_stock', '<=', 'reorder_level');
    }

    /**
     * Scope active items.
     *
     * @param  Builder<Item>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Determine the visual stock status.
     */
    public function getStockStatusAttribute(): string
    {
        $current = (float) $this->current_stock;
        $reorder = (float) $this->reorder_level;

        if ($current <= 0) {
            return 'Out of Stock';
        }

        if ($reorder > 0 && $current <= ($reorder * 0.25)) {
            return 'Critical';
        }

        if ($current <= $reorder) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    /**
     * Get CSS badge class for current stock status.
     */
    public function getStockStatusBadgeClassAttribute(): string
    {
        return match ($this->stock_status) {
            'In Stock' => 'success',
            'Low Stock' => 'warning',
            'Critical', 'Out of Stock' => 'danger',
            default => 'secondary',
        };
    }
}
