@extends('layouts.app')

@section('title', 'Sales History - Unique Hardware')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-white mb-0">Sales History</h1>
        <p class="text-gray-400 mb-0">View past transactions and invoices</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Sale
    </a>
</div>

<div class="card bg-gray-800 border-0 shadow-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-secondary text-uppercase text-xs font-weight-bolder">Invoice #</th>
                        <th class="px-4 py-3 text-secondary text-uppercase text-xs font-weight-bolder">Date</th>
                        <th class="px-4 py-3 text-secondary text-uppercase text-xs font-weight-bolder">Items</th>
                        <th class="px-4 py-3 text-secondary text-uppercase text-xs font-weight-bolder text-end">Total Amount</th>
                        <th class="px-4 py-3 text-secondary text-uppercase text-xs font-weight-bolder text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($sales as $sale)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="fw-bold text-white">{{ $sale->invoice_no }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $sale->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $sale->items_count }} Items
                        </td>
                        <td class="px-4 py-3 text-white fw-bold text-end">
                            PKR {{ number_format($sale->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info me-1">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteSale({{ $sale->id }}, '{{ $sale->invoice_no }}')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-gray-500">
                            <i class="bi bi-receipt display-4 d-block mb-3"></i>
                            No sales records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
    <div class="card-footer bg-gray-800 border-top border-gray-700 py-3">
        {{ $sales->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSaleModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Sale
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete invoice <strong id="deleteSaleInvoice"></strong>?</p>
                <p class="text-secondary small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSaleForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDeleteSale(id, invoiceNo) {
        document.getElementById('deleteSaleInvoice').textContent = invoiceNo;
        document.getElementById('deleteSaleForm').action = `/sales/${id}`;
        new bootstrap.Modal(document.getElementById('deleteSaleModal')).show();
    }
</script>
@endsection
