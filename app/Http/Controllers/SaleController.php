<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Services\SaleService;
use App\Services\ProductService;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
        protected ProductService $productService,
    ) {
    }

    /**
     * Display sales history.
     */
    public function index()
    {
        $sales = Sale::withCount('items')->orderBy('created_at', 'desc')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show POS / New Sale interface.
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Process a new sale.
     */
    public function store(StoreSaleRequest $request)
    {
        try {
            $sale = $this->saleService->processSale(
                $request->validated()['items'],
                $request->validated()['customer_name'] ?? null,
                $request->validated()['customer_phone'] ?? null,
            );

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'invoice_no' => $sale->invoice_no,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Show invoice details.
     */
    public function show(Sale $sale)
    {
        $sale->load('items');
        return view('sales.show', compact('sale'));
    }

    /**
     * Delete a sale.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale record deleted successfully!');
    }

    /**
     * Search products for POS autocomplete.
     */
    public function searchProducts(\Illuminate\Http\Request $request)
    {
        $products = $this->productService->searchForPos($request->get('term', ''));
        return response()->json($products);
    }
}
