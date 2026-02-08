@extends('layouts.app')

@section('title', 'New Sale - Unique Hardware')

@section('content')
<div class="row h-100">
    <!-- Left Column: Product Search & List -->
    <div class="col-md-7 h-100 d-flex flex-column">
        <div class="card bg-gray-900 border-0 shadow-lg mb-3 flex-shrink-0">
            <div class="card-body">
                <h5 class="card-title text-white mb-3"><i class="bi bi-search me-2 text-primary"></i>Product Search</h5>
                <input type="text" id="product_search" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Type product name or SKU..." autocomplete="off">
            </div>
        </div>

        <div class="card bg-gray-900 border-0 shadow-lg flex-grow-1 overflow-hidden">
            <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center">
                <h6 class="text-gray-400 mb-0">Available Products</h6>
                <small class="text-muted">Click to add to cart</small>
            </div>
            <div class="card-body p-0 overflow-auto" id="product_list_container">
                <div class="list-group list-group-flush" id="product_results">
                    <!-- Results will appear here -->
                    <div class="text-center py-5 text-gray-500">
                        <i class="bi bi-arrow-up-circle display-4 mb-2 d-block"></i>
                        Start typing to search products
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Cart -->
    <div class="col-md-5 h-100 d-flex flex-column">
        <div class="card bg-gray-800 border-0 shadow-lg flex-grow-1 d-flex flex-column">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cart3 me-2"></i>Current Sale</h5>
                    <span class="badge bg-white text-primary" id="cart_count">0 Items</span>
                </div>
            </div>
            
            <div class="card-body p-0 flex-grow-1 overflow-auto bg-gray-900">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="bg-gray-800 sticky-top">
                        <tr>
                            <th class="ps-3">Item</th>
                            <th style="width: 80px;">Qty</th>
                            <th class="text-end">Total</th>
                            <th style="width: 40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="cart_items">
                        <!-- Cart items will go here -->
                        <tr id="empty_cart_msg">
                            <td colspan="4" class="text-center py-5 text-muted">
                                Cart is empty
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-gray-800 border-top border-secondary p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-gray-400 mb-0">Total:</h4>
                    <h2 class="text-primary fw-bold mb-0" id="cart_total">PKR 0.00</h2>
                </div>
                <button class="btn btn-success btn-lg w-100 fw-bold" id="btn_checkout" disabled onclick="processSale()">
                    <i class="bi bi-check-circle-fill me-2"></i>Complete Sale
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];
    let searchTimeout;

    // Search Products
    document.getElementById('product_search').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        
        if (query.length < 2) {
            document.getElementById('product_results').innerHTML = '<div class="text-center py-5 text-gray-500"><i class="bi bi-search display-6 mb-2 d-block"></i>Type to search...</div>';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('sales.search') }}?term=${query}`)
                .then(response => response.json())
                .then(products => {
                    const resultsDiv = document.getElementById('product_results');
                    resultsDiv.innerHTML = '';
                    
                    if (products.length === 0) {
                        resultsDiv.innerHTML = '<div class="text-center py-5 text-gray-500">No products found</div>';
                        return;
                    }

                    products.forEach(product => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action bg-transparent text-white border-secondary d-flex justify-content-between align-items-center p-3';
                        item.onclick = (e) => { e.preventDefault(); addToCart(product); };
                        
                        item.innerHTML = `
                            <div>
                                <div class="fw-bold">${product.name}</div>
                                <div class="small text-muted">SKU: ${product.sku} | Rack: <span class="text-info">${product.rack_number || 'N/A'}</span></div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">PKR ${parseFloat(product.price).toFixed(2)}</div>
                                <div class="small ${product.quantity > 0 ? 'text-success' : 'text-danger'}">${product.quantity} in stock</div>
                            </div>
                        `;
                        resultsDiv.appendChild(item);
                    });
                });
        }, 300);
    });

    // Add to Cart
    function addToCart(product) {
        if (product.quantity <= 0) {
            alert('Out of Stock!');
            return;
        }

        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            if (existingItem.quantity >= product.quantity) {
                alert('Cannot add more than available stock!');
                return;
            }
            existingItem.quantity++;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: 1,
                max_stock: product.quantity
            });
        }
        updateCartUI();
    }

    // Update Cart Quantity
    window.updateQty = function(productId, change) {
        const item = cart.find(item => item.id === productId);
        if (!item) return;

        const newQty = item.quantity + change;
        
        if (newQty <= 0) {
            removeFromCart(productId);
            return;
        }
        
        if (newQty > item.max_stock) {
            alert('Cannot exceed available stock!');
            return;
        }

        item.quantity = newQty;
        updateCartUI();
    };

    // Remove from Cart
    window.removeFromCart = function(productId) {
        cart = cart.filter(item => item.id !== productId);
        updateCartUI();
    };

    // Update Cart UI
    function updateCartUI() {
        const tbody = document.getElementById('cart_items');
        tbody.innerHTML = '';
        let total = 0;
        let count = 0;

        if (cart.length === 0) {
            tbody.innerHTML = '<tr id="empty_cart_msg"><td colspan="4" class="text-center py-5 text-muted">Cart is empty</td></tr>';
            document.getElementById('btn_checkout').disabled = true;
        } else {
            document.getElementById('btn_checkout').disabled = false;
            
            cart.forEach(item => {
                const lineTotal = item.price * item.quantity;
                total += lineTotal;
                count += item.quantity;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="ps-3">
                        <div class="fw-bold text-white">${item.name}</div>
                        <div class="small text-muted">PKR ${item.price.toFixed(2)}</div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                            <span class="input-group-text bg-dark text-white border-secondary" style="min-width: 30px; justify-content: center;">${item.quantity}</span>
                            <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end text-primary fw-bold">PKR ${lineTotal.toFixed(2)}</td>
                    <td class="text-end pe-2">
                        <button class="btn btn-sm btn-link text-danger p-0" onclick="removeFromCart(${item.id})">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('cart_total').textContent = 'PKR ' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('cart_count').textContent = count + ' Items';
    }

    // Process Sale
    window.processSale = function() {
        if (cart.length === 0) return;
        if (!confirm('Complete this sale?')) return;

        const btn = document.getElementById('btn_checkout');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        fetch('{{ route("sales.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: cart })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success toast or alert
                alert('Sale Completed Successfully! Invoice: ' + data.invoice_no);
                
                // Clear cart
                cart = [];
                updateCartUI();
                
                // Refresh product search if needed or redirect
                document.getElementById('product_search').value = '';
                document.getElementById('product_results').innerHTML = '<div class="text-center py-5 text-gray-500"><i class="bi bi-arrow-up-circle display-4 mb-2 d-block"></i>Start typing to search products</div>';
                
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the sale.');
        })
        .finally(() => {
            btn.disabled = cart.length === 0;
            btn.innerHTML = originalText;
        });
    };
</script>
@endsection
