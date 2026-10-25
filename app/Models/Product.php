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
        'purchase_price',
        'quantity',  // Loose quantity
        'box_quantity', // Full boxes
        'items_per_box',
        'is_box_item',
        'unit',
        'supplier_name',
        'supplier_contact',
        'supplier_address',
    ];

    // Available categories
    public const CATEGORIES = ['Hardware', 'Tools', 'Sanitary', 'Paint', 'Electrical', 'Plumbing'];

    // Available units
    public const UNITS = ['pcs', 'dozen', 'meter', 'feet', 'kg', 'liter'];

    // Accessor for total quantity (in base units)
    public function getTotalStockAttribute()
    {
        if ($this->is_box_item && $this->items_per_box > 0) {
            return ($this->box_quantity * $this->items_per_box) + $this->quantity;
        }
        return $this->quantity;
    }

    // Helper to reduce stock safely handling boxes
    public function reduceStock($amount)
    {
        if (!$this->is_box_item) {
            $this->decrement('quantity', $amount);
            return;
        }

        $totalNeeded = $amount;

        // Take from loose quantity first
        if ($this->quantity >= $totalNeeded) {
            $this->decrement('quantity', $totalNeeded);
            return;
        }

        // We need more than loose quantity
        $totalNeeded -= $this->quantity;
        $this->quantity = 0;

        // Calculate boxes needed
        $boxesToOpen = ceil($totalNeeded / $this->items_per_box);

        if ($this->box_quantity >= $boxesToOpen) {
            $this->decrement('box_quantity', $boxesToOpen);
            // Add items from opened boxes to loose quantity, minus what was needed
            $this->quantity = ($boxesToOpen * $this->items_per_box) - $totalNeeded;
            $this->save();
        } else {
            // Not enough stock even with boxes
            // This case should be handled by validation before calling this
            throw new \Exception("Insufficient stock");
        }
    }

    // Search scope for filtering products
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
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
