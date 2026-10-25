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
                'invoice_no' => $this->nextInvoiceNumber(),
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
     * Generate the next invoice number as a padded sequential value
     * (INV-00001, INV-00002, ...) instead of uniqid(), which is based on
     * microtime and is not guaranteed unique under concurrent requests.
     * Locks the last row so concurrent sales can't read the same "next"
     * number — caller must already be inside a DB transaction.
     */
    protected function nextInvoiceNumber(): string
    {
        $lastId = Sale::lockForUpdate()->orderByDesc('id')->value('id');

        return 'INV-' . str_pad(($lastId ?? 0) + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Delete a sale and restore the stock that was taken for it.
     *
     * Previously, deleting a sale just removed the record — the quantities
     * sold were never put back, so a mistaken or test sale permanently
     * "lost" that stock from the system.
     */
    public function voidSale(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            $sale->load('items');

            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                // Product may have since been deleted; nothing to restore to.
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            $sale->delete();
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
