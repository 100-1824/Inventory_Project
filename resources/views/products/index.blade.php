@extends('layouts.app')

@section('content')
    <div class="row g-4 mb-4 fade-in">
        <!-- Stats Cards -->
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ $products->total() }}</div>
                <div class="stat-label"><i class="bi bi-box me-1"></i> Total Products</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card accent">
                <div class="stat-number">{{ $lowStockCount }}</div>
                <div class="stat-label"><i class="bi bi-exclamation-triangle me-1"></i> Low Stock</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-number">{{ count($categories) }}</div>
                <div class="stat-label"><i class="bi bi-tags me-1"></i> Categories</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-number">PKR {{ number_format($totalValue, 0) }}</div>
                <div class="stat-label"><i class="bi bi-currency-dollar me-1"></i> Total Value</div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2 text-accent" style="color: var(--accent);"></i>
                Product Inventory
            </h5>
            <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </button>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <form action="{{ route('products.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" name="search"
                            placeholder="Search products, SKU, or supplier..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="category" onchange="this.form.submit()">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($category ?? '') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-light w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>

            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Rack</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Supplier</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-secondary">{{ Str::limit($product->description, 40) }}</small>
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <span class="badge badge-category badge-{{ Str::slug($product->category) }}">
                                        {{ $product->category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $product->rack_number ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-semibold">PKR {{ number_format($product->price, 2) }}</td>
                                <td>
                                    @php
                                        $stockClass = $product->quantity <= 5 ? 'stock-low' :
                                            ($product->quantity <= 20 ? 'stock-medium' : 'stock-good');
                                    @endphp
                                    <span class="{{ $stockClass }} fw-semibold">
                                        {{ $product->quantity }} {{ $product->unit }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->supplier_name)
                                        <div>{{ $product->supplier_name }}</div>
                                        <small class="text-secondary">{{ $product->supplier_contact }}</small>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-action btn-outline-info me-1"
                                        onclick="viewProduct({{ $product->id }})" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-action btn-outline-warning me-1"
                                        onclick="editProduct({{ json_encode($product) }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-action btn-outline-danger"
                                        onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-secondary">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    No products found. Add your first product!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle me-2" style="color: var(--accent);"></i>
                            Add New Product
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Product Info -->
                            <div class="col-md-8">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SKU *</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku"
                                    value="{{ old('sku') }}" required placeholder="e.g., HW-001">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" name="category"
                                    required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Rack Number</label>
                                <input type="text" class="form-control" name="rack_number" placeholder="e.g., A-12">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Selling Price (PKR) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" name="price"
                                    step="0.01" min="0" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Purchase Price (PKR) *</label>
                                <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
                                    name="purchase_price" step="0.01" min="0" value="{{ old('purchase_price') }}" required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity (Loose) *</label>
                                <input type="number" class="form-control" name="quantity" min="0" required
                                    value="{{ old('quantity', 0) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit *</label>
                                <select class="form-select" name="unit" required>
                                    <option value="pcs">Pcs</option>
                                    <option value="dozen">Dozen</option>
                                    <option value="meter">Meter</option>
                                    <option value="feet">Feet</option>
                                    <option value="kg">Kg</option>
                                    <option value="liter">Liter</option>
                                </select>
                            </div>

                            <!-- Box/Unit Info -->
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_box_item" name="is_box_item"
                                        value="1" onchange="toggleBoxFields(this)">
                                    <label class="form-check-label" for="is_box_item">Sold in Boxes/Cartons?</label>
                                </div>
                            </div>
                            <div class="col-md-4 box-field d-none">
                                <label class="form-label">Items per Box</label>
                                <input type="number" class="form-control" name="items_per_box" min="1">
                            </div>
                            <div class="col-md-4 box-field d-none">
                                <label class="form-label">Full Boxes in Stock</label>
                                <input type="number" class="form-control" name="box_quantity" min="0" value="0">
                            </div>

                            <!-- Supplier Info -->
                            <div class="col-12 mt-4">
                                <h6 class="text-secondary mb-3">
                                    <i class="bi bi-truck me-2"></i>Supplier Information
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" name="supplier_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="supplier_contact"
                                    placeholder="Phone or Email">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="supplier_address">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-check-lg me-2"></i>Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editProductForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2" style="color: var(--warning);"></i>
                            Edit Product
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SKU *</label>
                                <input type="text" class="form-control" name="sku" id="edit_sku" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category *</label>
                                <select class="form-select" name="category" id="edit_category" required>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Tools">Tools</option>
                                    <option value="Sanitary">Sanitary</option>
                                    <option value="Paint">Paint</option>
                                    @foreach($categories as $cat)
                                        {{-- Ensure dynamic categories are still available if not in hardcoded list --}}
                                        @if(!in_array($cat, ['Hardware', 'Tools', 'Sanitary', 'Paint']))
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rack Number</label>
                                <input type="text" class="form-control" name="rack_number" id="edit_rack_number"
                                    placeholder="e.g., A-12">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Selling Price (PKR) *</label>
                                <input type="number" class="form-control" name="price" id="edit_price" step="0.01" min="0"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Purchase Price (PKR) *</label>
                                <input type="number" class="form-control" name="purchase_price" id="edit_purchase_price"
                                    step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity </label>
                                <input type="number" class="form-control" name="quantity" id="edit_quantity" min="0"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit *</label>
                                <select class="form-select" name="unit" id="edit_unit" required>
                                    <option value="pcs">Pcs</option>
                                    <option value="dozen">Dozen</option>
                                    <option value="meter">Meter</option>
                                    <option value="feet">Feet</option>
                                    <option value="kg">Kg</option>
                                    <option value="liter">Liter</option>
                                </select>
                            </div>

                            <!-- Box/Unit Info Edit -->
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_is_box_item" name="is_box_item"
                                        value="1" onchange="toggleEditBoxFields(this)">
                                    <label class="form-check-label" for="edit_is_box_item">Sold in Boxes/Cartons?</label>
                                </div>
                            </div>
                            <div class="col-md-4 edit-box-field d-none">
                                <label class="form-label">Items per Box</label>
                                <input type="number" class="form-control" name="items_per_box" id="edit_items_per_box"
                                    min="1">
                            </div>
                            <div class="col-md-4 edit-box-field d-none">
                                <label class="form-label">Full Boxes in Stock</label>
                                <input type="number" class="form-control" name="box_quantity" id="edit_box_quantity"
                                    min="0">
                            </div>

                            <div class="col-12 mt-4">
                                <h6 class="text-secondary mb-3">
                                    <i class="bi bi-truck me-2"></i>Supplier Information
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" name="supplier_name" id="edit_supplier_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="supplier_contact" id="edit_supplier_contact">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="supplier_address" id="edit_supplier_address">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-check-lg me-2"></i>Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle me-2" style="color: var(--accent);"></i>
                        Product Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewProductBody">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>Delete Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                    <p class="text-secondary small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" class="d-inline">
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
        // Edit Product
        function editProduct(product) {
            document.getElementById('editProductForm').action = `/products/${product.id}`;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_sku').value = product.sku;
            document.getElementById('edit_description').value = product.description || '';
            document.getElementById('edit_category').value = product.category;
            document.getElementById('edit_rack_number').value = product.rack_number || '';
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_quantity').value = product.quantity;
            document.getElementById('edit_unit').value = product.unit;
            document.getElementById('edit_supplier_name').value = product.supplier_name || '';
            document.getElementById('edit_supplier_contact').value = product.supplier_contact || '';
            document.getElementById('edit_supplier_address').value = product.supplier_address || '';

            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }

        // View Product
        function viewProduct(id) {
            fetch(`/products/${id}`)
                .then(response => response.json())
                .then(product => {
                    const categorySlug = product.category.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                    const categoryClass = `badge-${categorySlug}`;
                    document.getElementById('viewProductBody').innerHTML = `
                                    <div class="text-center mb-4">
                                        <span class="badge badge-category ${categoryClass} mb-3">${product.category}</span>
                                        <h4 class="mb-1">${product.name}</h4>
                                        <code>${product.sku}</code>
                                    </div>
                                    <table class="table table-sm">
                                        <tr><td class="text-secondary">Description</td><td>${product.description || '-'}</td></tr>
                                        <tr><td class="text-secondary">Rack</td><td><span class="badge bg-secondary">${product.rack_number || 'N/A'}</span></td></tr>
                                        <tr><td class="text-secondary">Selling Price</td><td class="fw-semibold">PKR ${parseFloat(product.price).toFixed(2)}</td></tr>
                                        <tr><td class="text-secondary">Purchase Price</td><td class="fw-semibold">PKR ${parseFloat(product.purchase_price || 0).toFixed(2)}</td></tr>
                                        <tr><td class="text-secondary">Stock</td><td>${product.quantity} ${product.unit}</td></tr>
                                    </table>
                                    ${product.supplier_name ? `
                                    <h6 class="text-secondary mt-4 mb-3"><i class="bi bi-truck me-2"></i>Supplier</h6>
                                    <table class="table table-sm">
                                        <tr><td class="text-secondary">Name</td><td>${product.supplier_name}</td></tr>
                                        <tr><td class="text-secondary">Contact</td><td>${product.supplier_contact || '-'}</td></tr>
                                        <tr><td class="text-secondary">Address</td><td>${product.supplier_address || '-'}</td></tr>
                                    </table>
                                    ` : ''}
                                `;
                    new bootstrap.Modal(document.getElementById('viewProductModal')).show();
                });
        }

        // Delete Confirmation
        function confirmDelete(id, name) {
            document.getElementById('deleteProductName').textContent = name;
            document.getElementById('deleteForm').action = `/products/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function toggleBoxFields(checkbox) {
            const fields = document.querySelectorAll('.box-field');
            fields.forEach(field => {
                if (checkbox.checked) {
                    field.classList.remove('d-none');
                } else {
                    field.classList.add('d-none');
                }
            });
        }

        function toggleEditBoxFields(checkbox) {
            const fields = document.querySelectorAll('.edit-box-field');
            fields.forEach(field => {
                if (checkbox.checked) {
                    field.classList.remove('d-none');
                } else {
                    field.classList.add('d-none');
                }
            });
        }

        // Update editProduct to handle new fields
        const originalEditProduct = editProduct;
        editProduct = function (product) {
            // Call original function logic (manual update since I can't easily hook)
            document.getElementById('editProductForm').action = `/products/${product.id}`;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_sku').value = product.sku;
            document.getElementById('edit_description').value = product.description || '';
            document.getElementById('edit_category').value = product.category;
            document.getElementById('edit_rack_number').value = product.rack_number || '';
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_purchase_price').value = product.purchase_price || 0;
            document.getElementById('edit_quantity').value = product.quantity;
            document.getElementById('edit_unit').value = product.unit;
            document.getElementById('edit_supplier_name').value = product.supplier_name || '';
            document.getElementById('edit_supplier_contact').value = product.supplier_contact || '';
            document.getElementById('edit_supplier_address').value = product.supplier_address || '';

            // New fields
            const isBoxItem = product.is_box_item == 1;
            document.getElementById('edit_is_box_item').checked = isBoxItem;
            document.getElementById('edit_items_per_box').value = product.items_per_box || '';
            document.getElementById('edit_box_quantity').value = product.box_quantity || 0;

            toggleEditBoxFields(document.getElementById('edit_is_box_item'));

            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }
    </script>
@endsection