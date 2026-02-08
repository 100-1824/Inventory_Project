<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // Display sales history
    public function index()
    {
        $sales = Sale::withCount('items')->orderBy('created_at', 'desc')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    // Show POS / New Sale interface
    public function create()
    {
        return view('sales.create');
    }

    // Process a new sale
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Create Sale Record
            $sale = Sale::create([
                'invoice_no' => 'INV-' . strtoupper(uniqid()),
                'total_amount' => 0, // Will calculate below
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->quantity}");
                }

                // Deduct Stock
                $product->quantity -= $item['quantity'];
                $product->save();

                // Calculate Line Total
                $lineTotal = $product->price * $item['quantity'];
                $totalAmount += $lineTotal;

                // Create Sale Item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $lineTotal,
                ]);
            }

            // Update Sale Total
            $sale->update(['total_amount' => $totalAmount]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Sale completed successfully!', 
                'invoice_no' => $sale->invoice_no
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // Show invoice details
    public function show(Sale $sale)
    {
        $sale->load('items');
        return view('sales.show', compact('sale'));
    }
    
    // Delete a sale
    public function destroy(Sale $sale)
    {
        $sale->delete(); // This will cascade delete sale_items due to foreign key
        
        return redirect()->route('sales.index')
            ->with('success', 'Sale record deleted successfully!');
    }
    
    // Search products for POS
    public function searchProducts(Request $request)
    {
        $query = $request->get('term');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('sku', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'sku', 'price', 'quantity', 'rack_number']);
            
        return response()->json($products);
    }
}
