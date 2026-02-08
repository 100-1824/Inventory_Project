@extends('layouts.app')

@section('title', 'Invoice ' . $sale->invoice_no . ' - Unique Hardware')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('sales.index') }}" class="text-gray-400 text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to History
            </a>
            <button class="btn btn-outline-light btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Print Invoice
            </button>
        </div>

        <div class="card bg-gray-800 border-0 shadow-lg" id="invoice_canvas">
            <div class="card-body p-5">
                <!-- Header -->
                <div class="d-flex justify-content-between mb-5 border-bottom border-gray-700 pb-4">
                    <div>
                        <h2 class="text-primary fw-bold mb-0">Unique Hardware</h2>
                        <div class="text-gray-400 small mt-1">Inventory Management System</div>
                    </div>
                    <div class="text-end">
                        <h4 class="text-white mb-1">INVOICE</h4>
                        <div class="text-gray-400">#{{ $sale->invoice_no }}</div>
                        <div class="text-gray-400 small mt-1">{{ $sale->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>

                <!-- Items -->
                <div class="table-responsive mb-4">
                    <table class="table table-dark table-borderless">
                        <thead class="text-gray-400 border-bottom border-gray-700">
                            <tr>
                                <th class="pb-3 text-uppercase small">Item Description</th>
                                <th class="pb-3 text-center text-uppercase small" style="width: 100px;">Qty</th>
                                <th class="pb-3 text-end text-uppercase small" style="width: 150px;">Unit Price</th>
                                <th class="pb-3 text-end text-uppercase small" style="width: 150px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="py-3">
                                    <div class="text-white fw-bold">{{ $item->product_name }}</div>
                                    <div class="small text-gray-500">Product ID: #{{ $item->product_id }}</div>
                                </td>
                                <td class="py-3 text-center text-gray-300">{{ $item->quantity }}</td>
                                <td class="py-3 text-end text-gray-300">{{ number_format($item->price, 2) }}</td>
                                <td class="py-3 text-end text-white fw-bold">{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top border-gray-700">
                            <tr>
                                <td colspan="3" class="pt-4 text-end text-gray-400 text-uppercase small">Total Amount</td>
                                <td class="pt-4 text-end text-primary h4 fw-bold">PKR {{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Footer -->
                <div class="text-center text-gray-500 mt-5 pt-4 border-top border-gray-800 small">
                    <p class="mb-2 text-warning" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 0.95rem;">
                        <strong>واپسی کی پالیسی:</strong> ایک ہفتے کے اندر پروڈکٹ واپس کریں
                    </p>
                    <p class="mb-2 text-gray-400">
                        <strong>Return Policy:</strong> Products can be returned within one week
                    </p>
                    <p class="mb-1 mt-4">Thank you for your business!</p>
                    <p class="mb-0">Unique Hardware Store</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
