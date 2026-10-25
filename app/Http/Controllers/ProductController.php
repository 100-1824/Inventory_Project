<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        $products = Product::query()
            ->search($search)
            ->category($category)
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Dynamic category list: whatever is actually in the table, plus the
        // original defaults so they stay selectable even with zero products.
        $categories = Product::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->merge(Product::CATEGORIES)
            ->unique()
            ->sort()
            ->values();

        // Computed via dedicated queries against the whole table, not the
        // current page's collection.
        $totalProducts = Product::count();
        $lowStockCount = Product::where('quantity', '<=', 10)->count();
        $totalValue = Product::query()->selectRaw('COALESCE(SUM(price * quantity), 0) as total')->value('total');

        return view('products.index', compact(
            'products', 'categories', 'search', 'category',
            'totalProducts', 'lowStockCount', 'totalValue'
        ));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            Product::create($request->validated());
            return redirect()->route('products.index')->with('success', 'Product added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $product->update($request->validated());
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->saleItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete this product because it has sales history. Consider marking it as discontinued instead.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
