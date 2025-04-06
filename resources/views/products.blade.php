@extends('customer.template')
@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
@endphp

@section('styles')
<style>
    /* Product Card Styles */
    .product-card {
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .product-img-container {
        height: 200px;
        position: relative;
        overflow: hidden;
    }
    
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    
    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        font-size: 0.75rem;
    }
    
    /* Filter Sidebar */
    .filter-sidebar {
        position: sticky;
        top: 20px;
    }
    
    .filter-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .filter-card .card-header {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 1px solid #eee;
    }
    
    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    /* Price Range Slider */
    .price-range-slider {
        margin: 15px 0;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .filter-sidebar {
            position: static;
            margin-bottom: 20px;
        }
        
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <div class="card filter-card mb-4">
                    <div class="card-header">
                        <i class="fas fa-filter me-2"></i> Filter Produk
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products') }}" method="GET">
                            <div class="mb-4">
                                <h6><i class="fas fa-tag me-2"></i> Merek</h6>
                                <select name="brand" class="form-select">
                                    <option value="">Semua Merek</option>
                                    @foreach(['Singer', 'Brother', 'Janome', 'Juki', 'Messina', 'Typical', 'Yamata', 'Newlong', 'Aksesoris', 'Sparepart'] as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <h6><i class="fas fa-dollar-sign me-2"></i> Rentang Harga</h6>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="number" name="min_price" class="form-control" placeholder="Min" 
                                               value="{{ request('min_price') }}" min="0">
                                    </div>
                                    <div class="col">
                                        <input type="number" name="max_price" class="form-control" placeholder="Max"
                                               value="{{ request('max_price') }}" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('products') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Additional Filters Can Be Added Here -->
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    @if(request()->has('brand') || request()->has('min_price') || request()->has('max_price'))
                        Hasil Pencarian
                    @else
                        Semua Produk
                    @endif
                </h4>
                <div class="text-muted small">
                    Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                </div>
            </div>
            
            @if($products->count() > 0)
                <div class="product-grid">
                    @foreach ($products as $product)
                    <div class="product-card card">
                        <div class="product-img-container">
                            <img src="{{ asset($product->image) }}" class="product-img" alt="{{ $product->name }}">
                            @if($product->stock <= 0)
                                <span class="stock-badge badge bg-danger">Habis</span>
                            @elseif($product->stock < 5)
                                <span class="stock-badge badge bg-warning text-dark">Stok Terbatas</span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ Str::limit($product->name, 30) }}</h5>
                            <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 50) }}</p>
                            <p class="card-text fw-bold text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            
                            <div class="mt-auto">
                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                                <button class="btn btn-primary btn-sm w-100 add-to-cart" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-product.svg') }}" alt="No products" style="max-width: 300px;" class="mb-4">
                    <h5>Produk tidak ditemukan</h5>
                    <p class="text-muted">Coba gunakan filter yang berbeda atau <a href="{{ route('products') }}">reset filter</a></p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Add to cart functionality with improved feedback
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = $btn.data('product-id');
        
        // Show loading state
        $btn.html('<span class="spinner-border spinner-border-sm"></span> Menambahkan...').prop('disabled', true);
        
        $.ajax({
            type: 'POST',
            url: '{{ route('cart.add') }}',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update cart count
                $('#cart-count').text(response.totalQuantity);
                
                // Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                
                // Reset button state
                $btn.html('<i class="fas fa-cart-plus"></i> Tambah ke Keranjang').prop('disabled', false);
            },
            error: function(xhr) {
                // Handle unauthorized (401) by redirecting to login
                if (xhr.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    return;
                }
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON.message || 'Gagal menambahkan ke keranjang',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                
                // Reset button state
                $btn.html('<i class="fas fa-cart-plus"></i> Tambah ke Keranjang').prop('disabled', false);
            }
        });
    });
});
</script>
@endsection