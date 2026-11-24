<?php

namespace App\Http\Requests\Items;

use App\Models\Item;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-inventory') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Item|string|null $item */
        $item = $this->route('item');
        $itemId = $item instanceof Item ? $item->id : $item;

        return [
            'item_code' => ['required', 'string', 'max:50', Rule::unique('items', 'item_code')->ignore($itemId)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'unit' => ['required', 'string', 'max:20'],
            'category' => ['nullable', 'string', 'max:100'],
            'opening_stock' => ['sometimes', 'numeric', 'min:0'],
            'opening_stock_date' => ['nullable', 'date'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
