@extends('layouts.admin')
@php
    use App\Helpers\NumberHelper;
    use Illuminate\Support\Str;
@endphp

@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Produk</h1>
        <a href="{{ route('admin.product.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Produk
        </a>
    </div>

    <!-- Alert Messages -->
    <div class="alert-container">
        @if (session()->has('success') || isset($success) && $success)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session()->has('success') ? session()->get('success') : $success }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session()->has('error') || isset($error) && $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session()->has('error') ? session()->get('error') : $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Product Cards Summary -->
    <div class="row mb-4">
        <!-- Total Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Produk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Stok Habis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outOfStock ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok Rendah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStock ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Value -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Nilai Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalValue ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            <form id="searchForm" action="{{ route('admin.product') }}" method="GET" class="mt-2 mt-md-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari produk..." value="{{ request('search') }}"
                           aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Berat</th>
                            <th>Brand</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $product->name }}</div>
                                <small class="text-muted">SKU: {{ $product->id ?? 'N/A' }}</small>
                            </td>
                            <td class="text-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <div class="progress mb-1" style="height: 5px;">
                                    @php
                                        $stockPercentage = min(100, ($product->stock / ($product->max_stock ?? 100)) * 100);
                                        $stockColor = match(true) {
                                            $product->stock == 0 => 'bg-danger',
                                            $product->stock < 5 => 'bg-warning',
                                            default => 'bg-success',
                                        };
                                    @endphp
                                    <div class="progress-bar {{ $stockColor }}" role="progressbar" 
                                         style="width: {{ $stockPercentage }}%" 
                                         aria-valuenow="{{ $stockPercentage }}" 
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="badge {{ $product->stock == 0 ? 'badge-danger' : ($product->stock < 5 ? 'badge-warning' : 'badge-success') }}">
                                    {{ $product->stock }} pcs
                                </span>
                            </td>
                            <td>{{ $product->weight }} gram</td>
                            <td>{{ $product->brand }}</td>
                            <td>{{ Str::limit($product->description, 50) }}</td>
                            <td>
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                     class="img-thumbnail" style="width: 80px; height: auto; cursor: pointer" 
                                     onclick="showImageModal('{{ asset($product->image) }}', '{{ $product->name }}')">
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column flex-sm-row justify-content-center">
                                    <a href="{{ route('admin.product.edit', $product->id) }}" 
                                       class="btn btn-sm btn-primary mb-1 mb-sm-0 mr-sm-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                title="Hapus" onclick="return confirmDelete()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Tidak ada produk ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between">
                <div class="text-muted">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </div>
                <div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Format number input
    function formatNumber(input) {
        return input.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Show image in modal
    function showImageModal(imageSrc, productName) {
        $('#modalImage').attr('src', imageSrc);
        $('#imageModalTitle').text(productName);
        $('#imageModal').modal('show');
    }

    // Confirm delete
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus produk ini?');
    }

    // Auto dismiss alerts
    $(document).ready(function() {
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Initialize tooltips
        $('[title]').tooltip();

        // Format number inputs
        $('input[name="price"], input[name="stock"], input[name="weight"]').on('input', function() {
            this.value = formatNumber(this.value);
        });
    });

    // AJAX form submission for product creation
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let price = parseFloat(formData.get('price').replace(/,/g, ''));
        let stock = parseInt(formData.get('stock').replace(/,/g, ''));
        let weight = parseFloat(formData.get('weight').replace(/,/g, ''));

        formData.set('price', price);
        formData.set('stock', stock);
        formData.set('weight', weight);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: response.message || 'Produk berhasil ditambahkan',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan';
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
    .img-thumbnail {
        transition: transform 0.2s;
    }
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
    .badge {
        font-size: 0.8rem;
        font-weight: 500;
    }
    .table th {
        white-space: nowrap;
    }
    .alert-container {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 1050;
        min-width: 350px;
    }
    @media (max-width: 768px) {
        .alert-container {
            left: 20px;
            right: 20px;
            min-width: auto;
        }
    }

    
</style>
@endsection