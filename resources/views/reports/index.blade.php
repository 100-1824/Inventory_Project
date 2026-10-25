@extends('layouts.app')

@section('content')
    <div class="row fade-in">
        <div class="col-md-12 mb-4">
            <h2 class="mb-3">Reports & Analytics</h2>
        </div>

        <!-- Financial Overview -->
        <div class="col-md-4 mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-value text-success">${{ number_format($monthlySales, 2) }}</div>
                <div class="stat-label">Monthly Sales</div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-value text-warning">${{ number_format($monthlyExpenses, 2) }}</div>
                <div class="stat-label">Monthly Expenses</div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="stat-card {{ $profit >= 0 ? 'success' : 'danger' }}">
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-value {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($profit, 2) }}
                </div>
                <div class="stat-label">Estimated Profit</div>
            </div>
        </div>

        <!-- Dead Stock Alert -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Dead Stock Alert (No sales > 60 days)
                    </h5>
                </div>
                <div class="card-body">
                    @if($deadStock->isEmpty())
                        <p class="text-center text-muted my-3">No dead stock detected. Good job!</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Value</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deadStock as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td><span class="badge bg-secondary">{{ $product->category }}</span></td>
                                            <td>{{ $product->quantity }} {{ $product->unit }}</td>
                                            <td>${{ number_format($product->price * $product->quantity, 2) }}</td>
                                            <td>{{ $product->updated_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection