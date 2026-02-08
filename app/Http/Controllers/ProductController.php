<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        $products = Product::query()
            ->search($search)
            ->category($category)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Product::CATEGORIES;

        return view('products.index', compact('products', 'categories', 'search', 'category'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'nullable|string',
            'category' => 'required|in:Hardware,Tools,Sanitary,Paint',
            'rack_number' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_address' => 'nullable|string|max:500',
        ]);

        try {
            Product::create($validated);
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
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category' => 'required|in:Hardware,Tools,Sanitary,Paint',
            'rack_number' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_address' => 'nullable|string|max:500',
        ]);

        try {
            $product->update($validated);
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
        // Check if product has sales history
        if ($product->saleItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete this product because it has sales history. Consider marking it as discontinued instead.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
