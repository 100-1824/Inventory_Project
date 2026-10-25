<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * Search products by name or SKU (for POS autocomplete).
     *
     * @param  string  $term
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchForPos(string $term, int $limit = 10)
    {
        return Product::where('name', 'LIKE', "%{$term}%")
            ->orWhere('sku', 'LIKE', "%{$term}%")
            ->limit($limit)
            ->get(['id', 'name', 'sku', 'price', 'purchase_price', 'quantity', 'rack_number', 'unit']);
    }
}
