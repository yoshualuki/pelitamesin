@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Management Stok</h1>
            <!-- <a href="{{ route('admin.inventory.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Item
                    </a> -->
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal"
                data-bs-target="#addStockModal">

                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Stok Baru
            </button>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Total Items Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Barang</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalItems }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Value Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Value</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalValue, 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Stok Item Terendah</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recently Added Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Baru Ditambah</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentlyAddedCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Stock Modal -->
        <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addStockModalLabel">Tambah Stok Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="addStockForm">
                        @csrf
                        <div class="modal-body">
                            <!-- Product Search -->
                            <div class="mb-3">
                                <label for="productSearch" class="form-label">Produk</label>
                                <input type="text" class="form-control" id="productSearch"
                                    placeholder="Cari nama produk..." autocomplete="off">
                                <input type="hidden" id="product_id" name="product_id">
                                <div id="productSearchResults" class="list-group mt-2"
                                    style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                <div class="mt-1">
                                    <span class="badge bg-info text-dark">Stok saat ini: <span
                                            id="currentStock">0</span></span>
                                    
                                </div>
                            </div>

                            <!-- Quantity Input -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah Stok</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                                    <span class="input-group-text">unit</span>
                                </div>
                            </div>

                            <!-- Unit Cost -->
                            <div class="mb-3">
                                <label for="unit_cost" class="form-label">Harga Beli (per unit)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="unit_cost" name="unit_cost" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" id="addStockSpinner"></span>
                                Tambah Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Stock Modal -->
        <div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editStockModalLabel">Edit Stok</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="editStockForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_inventory_id" name="id">
                        <div class="modal-body">
                            <!-- Product Info -->
                            <div class="mb-3">
                                <label class="form-label">Produk</label>
                                <input type="text" class="form-control" id="edit_product_name" readonly>
                                <div class="mt-1">
                                    <span class="badge bg-info text-dark">Stok saat ini: <span id="edit_current_stock">0</span></span>
                                </div>
                            </div>

                            <!-- Quantity Input -->
                            <div class="mb-3">
                                <label for="edit_quantity" class="form-label">Jumlah Stok</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="edit_quantity" name="quantity"
                                        required>
                                    <span class="input-group-text">unit</span>
                                </div>
                            </div>

                            <!-- Unit Cost -->
                            <div class="mb-3">
                                <label for="edit_unit_cost" class="form-label">Harga Beli (per unit)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="edit_unit_cost" name="unit_cost"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" id="editStockSpinner"></span>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus stok ini?</p>
                        <p class="fw-bold" id="deleteItemName"></p>
                        <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">
                            <span class="spinner-border spinner-border-sm d-none" id="deleteSpinner"></span>
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
                <div class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..."
                            id="searchInput" onkeyup="searchTable()">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Total</th>
                                <th>Harga per Unit</th>
                                <th>Total</th>
                                <th>Terakhir Diperbarui</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventories as $inventory)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($inventory->product->image ?? false)
                                                <img src="{{ asset($inventory->product->image) }}"
                                                    class="img-profile rounded-circle mr-3" width="40" height="40"
                                                    style="margin-right:20px;">
                                            @else
                                                <div class="img-profile rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $inventory->product->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-xs text-muted">{{ $inventory->product->sku ?? 'No SKU' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress mb-2" style="height: 5px;">
                                            @php
                                                $percentage = min(
                                                    100,
                                                    ($inventory->quantity / ($inventory->product->max_stock ?? 100)) *
                                                        100,
                                                );
                                                $color = match (true) {
                                                    $inventory->quantity == 0 => 'bg-danger',
                                                    $inventory->quantity < 5 => 'bg-danger',
                                                    $inventory->quantity < 10 => 'bg-warning',
                                                    default => 'bg-success',
                                                };
                                            @endphp
                                            <div class="progress-bar {{ $color }}" role="progressbar"
                                                style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span
                                            class="badge {{ $inventory->quantity == 0
                                                ? 'badge-danger'
                                                : ($inventory->quantity < 5
                                                    ? 'badge-danger'
                                                    : ($inventory->quantity < 10
                                                        ? 'badge-warning'
                                                        : 'badge-success')) }}">
                                            {{ $inventory->quantity }} in stock
                                        </span>
                                    </td>
                                    <td>Rp{{ number_format($inventory->unit_cost, 2) }}</td>
                                    <td class="font-weight-bold">Rp{{ number_format($inventory->total_cost, 2) }}</td>
                                    <td>
                                        {{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y') : 'Never' }}
                                        <div class="text-xs text-muted">
                                            {{ $inventory->last_restocked_at ? $inventory->last_restocked_at->diffForHumans() : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-primary mr-2 edit-btn" title="Edit"
                                                data-id="{{ $inventory->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editStockModal">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- In your table row -->
                                            <button class="btn btn-sm btn-danger delete-btn" title="Hapus"
                                                data-id="{{ $inventory->id }}"
                                                data-name="{{ $inventory->product->name ?? 'N/A' }}"
                                                data-url="{{ route('admin.inventory.destroy', $inventory->id) }}"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No inventory items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $inventories->firstItem() }} ke {{ $inventories->lastItem() }} dari
                        {{ $inventories->total() }} barang
                    </div>
                    <div>
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Warning -->
        @if ($lowStockItems->count() > 0)
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 d-flex align-items-center bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alert
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-warning-50">
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Recommended</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product->image ?? false)
                                                    <img src="{{ asset($item->product->image) }}"
                                                        class="img-profile rounded-circle mr-3" width="40"
                                                        height="40">
                                                    
                                                @else
                                                    <div class="img-profile rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-box"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold">{{ $item->product->name ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-muted">{{ $item->product->sku ?? 'No SKU' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold text-danger">{{ $item->quantity }}</td>
                                        <td>10</td>
                                        <td>
                                            {{-- <a href="{{ route('admin.inventory.edit', $item->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-plus mr-1"></i> Restock
                                            </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

<!-- Search Functionality -->
@section('scripts')
    <script>
        $(document).ready(function() {

            // Thousand separator function
            function formatNumber(input) {
                // Remove non-numeric characters
                let value = input.val().replace(/[^0-9]/g, '');

                // Format with thousand separators
                if (value.length > 0) {
                    value = parseInt(value).toLocaleString('id-ID');
                }

                input.val(value);
                return value.replace(/[^0-9]/g, ''); // Return raw number for calculations
            }


            const productSearch = $('#productSearch');
            const productIdInput = $('#product_id');
            const searchResults = $('#productSearchResults');
            const currentStockSpan = $('#currentStock');
            const unitCostInput = $('#unit_cost');
            // Debounce function to limit API calls
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this,
                        args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            // Product search handler
            productSearch.on('input', debounce(function() {
                const query = $(this).val().trim();
                if (query.length < 2) {
                    searchResults.hide().empty();
                    return;
                }

                $.get('{{ route('products.search') }}', {
                    q: query
                }, function(data) {
                    searchResults.empty();
                    if (data.length > 0) {
                        data.forEach(product => {
                            const item = $(`
                        <a href="#" class="list-group-item list-group-item-action" 
                            data-id="${product.id}" 
                            data-stock="${product.stock}"
                            data-price="${product.purchase_price || ''}">
                            ${product.name} (${product.sku || 'No SKU'}) - Stock: ${product.stock}
                        </a>
                    `);
                            searchResults.append(item);
                        });
                        searchResults.show();
                    } else {
                        searchResults.append(
                            '<div class="list-group-item">No products found</div>');
                        searchResults.show();
                    }
                });
            }, 300));

            // Handle product selection
            searchResults.on('click', '.list-group-item', function(e) {
                e.preventDefault();
                const product = $(this);
                const productName = product.text().split('(')[0].trim(); // Extract just the product name
                productSearch.val(productName);
                productIdInput.val(product.data('id'));
                currentStockSpan.text(product.data('stock'));
                unitCostInput.val(product.data('price'));
                searchResults.hide().empty();
            });

            // Hide results when clicking elsewhere
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearch, #productSearchResults').length) {
                    searchResults.hide();
                }
            });

            // Atur thousand separator
            $('#quantity, #edit_quantity, #unit_cost, #edit_unit_cost').on('input', function() {
                formatNumber($(this));
            });
            // Add Stock Form Submission
            $('#addStockForm').on('submit', function(e) {
                e.preventDefault();
                // Convert formatted numbers back to raw numbers before submission
                $(this).find('input[type="text"]').each(function() {
                    const rawValue = $(this).val().replace(/[^0-9]/g, '');
                    $(this).val(rawValue);
                });

                $.ajax({
                    url: "{{ route('admin.inventory.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addStockModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessage
                        });
                    }
                });
            });

            // Clear selection when modal is hidden
            $('#addStockModal').on('hidden.bs.modal', function() {
                productSearch.val('');
                productIdInput.val('');
                currentStockSpan.text('0');
                unitCostInput.val('');
                searchResults.hide().empty();
            });

            // Edit Stock Modal Handler
            $('.edit-btn').on('click', function() {
                const inventoryId = $(this).data('id');

                $.get("{{ url('admin/inventory') }}/" + inventoryId + "/edit", function(data) {
                    $('#edit_inventory_id').val(data.inventory.id);
                    $('#edit_product_name').val(data.product.name);
                    $('#edit_quantity').val(data.inventory.quantity);
                    $('#edit_unit_cost').val(parseInt(data.inventory.unit_cost).toLocaleString('id-ID'));
                    $('#edit_current_stock').text(data.product.stock);
                    $('#editStockModal').modal('show');
                });
            });

            // Edit Stock Form Submission
            $('#editStockForm').on('submit', function(e) {
                e.preventDefault();
                const inventoryId = $('#edit_inventory_id').val();
                // Convert formatted numbers back to raw numbers before submission
                $(this).find('input[type="text"]').each(function() {
                    const rawValue = $(this).val().replace(/[^0-9]/g, '');
                    $(this).val(rawValue);
                });

                $.ajax({
                    url: "{{ url('admin/inventory') }}/" + inventoryId,
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editStockModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessage
                        });
                    }
                });
            });

            // Delete Confirmation Handler
            $('.delete-btn').on('click', function() {
                const deleteUrl = $(this).data('url');
                const itemName = $(this).data('name');
                $('#deleteItemName').text(itemName);
                $('#deleteModal').modal('show').data('url', deleteUrl);
                // Remove the alert
                // alert(deleteUrl);
            });

            // Delete Confirmation
            $('#confirmDelete').on('click', function() {
                const modal = $('#deleteModal');
                const deleteUrl = modal.data('url');
                const btn = $(this);

                btn.prop('disabled', true);
                $('#deleteSpinner').removeClass('d-none');

                $.ajax({
                    url: deleteUrl,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        modal.modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessage
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        $('#deleteSpinner').addClass('d-none');
                    }
                });
            });
        });

        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("dataTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let found = false;
                const td = tr[i].getElementsByTagName("td");

                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }

                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
@endsection
