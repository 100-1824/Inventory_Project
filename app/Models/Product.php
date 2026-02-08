<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'rack_number',
        'price',
        'quantity',
        'unit',
        'supplier_name',
        'supplier_contact',
        'supplier_address',
    ];

    // Available categories
    public const CATEGORIES = ['Hardware', 'Tools', 'Sanitary', 'Paint'];

    // Search scope for filtering products
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('supplier_name', 'like', "%{$search}%");
        });
    }

    // Filter by category
    public function scopeCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    // Relationship to sale items
    public function saleItems()
    {
        return $this->hasMany(\App\Models\SaleItem::class);
    }
}
