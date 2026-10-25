<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($this->route('product')),
            ],
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'rack_number' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => ['required', 'string', Rule::in(Product::UNITS)],
            'is_box_item' => 'boolean',
            'items_per_box' => 'nullable|required_if:is_box_item,true|integer|min:1',
            'box_quantity' => 'nullable|integer|min:0',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_address' => 'nullable|string|max:500',
        ];
    }
}
