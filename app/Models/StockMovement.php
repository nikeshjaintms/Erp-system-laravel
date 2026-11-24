<?php

namespace App\Models;

use App\Enums\AdjustmentDirection;
use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'item_id',
        'type',
        'adjustment_direction',
        'quantity',
        'balance_after',
        'reference_type',
        'reference_id',
        'remarks',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
            'adjustment_direction' => AdjustmentDirection::class,
            'quantity' => 'decimal:4',
            'balance_after' => 'decimal:4',
        ];
    }

    /**
     * The item this stock movement belongs to.
     *
     * @return BelongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * The user who created the stock movement.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Polymorphic reference (e.g. IssueChallan, ReturnChallan, etc.).
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
