<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SaleService
{
    /**
     * Process a new sale from validated cart data.
     *
     * @param  array  $items      Array of ['id' => int, 'quantity' => int]
     * @param  string|null  $customerName
     * @param  string|null  $customerPhone
     * @return Sale
     *
     * @throws \Exception
     */
    public function processSale(array $items, ?string $customerName, ?string $customerPhone): Sale
    {
        return DB::transaction(function () use ($items, $customerName, $customerPhone) {
            $sale = Sale::create([
                'invoice_no' => 'INV-' . strtoupper(uniqid()),
                'total_amount' => 0,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
            ]);

            $totalAmount = 0;

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                $this->validateStock($product, $item['quantity']);

                $product->reduceStock($item['quantity']);

                $lineTotal = $product->price * $item['quantity'];
                $totalAmount += $lineTotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $lineTotal,
                ]);
            }

            $sale->update(['total_amount' => $totalAmount]);

            return $sale;
        });
    }

    /**
     * Validate that a product has enough stock for the requested quantity.
     *
     * @throws \Exception
     */
    protected function validateStock(Product $product, int $quantity): void
    {
        if (!$product->is_box_item && $product->quantity < $quantity) {
            throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->quantity}");
        }

        if ($product->is_box_item && $product->total_stock < $quantity) {
            throw new \Exception("Insufficient stock for {$product->name}.");
        }
    }
}
