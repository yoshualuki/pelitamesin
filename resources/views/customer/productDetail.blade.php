@php
use Illuminate\Support\Str;
@endphp
@extends('customer.template')

@section('styles')
<style>
    .product-gallery {
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .main-image {
        height: 400px;
        object-fit: contain;
        background: #f9f9f9;
    }
    
    .thumbnail-container {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .thumbnail {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .thumbnail:hover {
        border-color: #0d6efd;
        transform: scale(1.05);
    }
    
    .product-info {
        background: #fff;
        border-radius: 8px;
        padding: 25px;
    }
    
    .product-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #dc3545;
    }
    
    .product-meta {
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
        margin: 1rem 0;
    }
    
    .product-meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .product-meta-item i {
        width: 25px;
        color: #6c757d;
    }
    
    .stock-badge {
        font-size: 0.9rem;
        padding: 0.35em 0.65em;
    }
    
    .related-products {
        margin-top: 3rem;
    }
    
    .review-card {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .review-author {
        font-weight: 600;
    }
    
    .review-date {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    @media (max-width: 768px) {
        .main-image {
            height: 300px;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Gallery -->
        <div class="col-lg-6">
            <div class="product-gallery">
                <img id="mainImage" src="{{ asset($product->image) }}" class="img-fluid main-image w-100" alt="{{ $product->name }}">
                
                @if($product->images && count($product->images) > 0)
                <div class="thumbnail-container">
                    <img src="{{ asset($product->image) }}" class="thumbnail" onclick="changeImage(this)">
                    @foreach($product->images as $image)
                        <img src="{{ asset($image->path) }}" class="thumbnail" onclick="changeImage(this)">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="product-info">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                @if($product->brand)
                <div class="mb-3">
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-tag me-1"></i> {{ $product->brand }}
                    </span>
                </div>
                @endif
                
                <div class="d-flex align-items-center mb-3">
                    <div class="product-price me-3">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    @if($product->discount > 0)
                        <span class="text-decoration-line-through text-muted me-2">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                        <span class="badge bg-danger">{{ $product->discount }}% OFF</span>
                    @endif
                </div>
                
                <div class="product-meta">
                    <div class="product-meta-item">
                        <i class="fas fa-box"></i>
                        <span>Stok: 
                            @if($product->stock <= 0)
                                <span class="badge bg-danger stock-badge">Habis</span>
                            @elseif($product->stock < 5)
                                <span class="badge bg-warning text-dark stock-badge">Hampir Habis</span>
                            @else
                                <span class="badge bg-success stock-badge">Tersedia</span>
                            @endif
                            ({{ $product->stock }} unit)
                        </span>
                    </div>
                    <div class="product-meta-item">
                        <i class="fas fa-truck"></i>
                        <span>Pengiriman: {{ $product->shipping_info ?? 'Standar 2-3 hari' }}</span>
                    </div>
                    <div class="product-meta-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Garansi: {{ $product->warranty ?? '1 tahun' }}</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Deskripsi Produk</h5>
                    <p>{{ $product->description }}</p>
                    
                    @if($product->specifications)
                    <div class="mt-3">
                        <h6>Spesifikasi:</h6>
                        <ul>
                            @foreach($product->specifications as $spec)
                                <li>{{ $spec }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                
                <div class="d-flex gap-2 mb-4">
                    <div class="input-group" style="width: 120px;">
                        <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                        <input type="number" class="form-control text-center quantity-input" value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                    </div>
                    
                    <button class="btn btn-primary flex-grow-1 add-to-cart" data-product-id="{{ $product->id }}">
                        <i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang
                    </button>
                    
                    {{-- <button class="btn btn-outline-secondary wishlist-toggle" data-product-id="{{ $product->id }}">
                        <i class="far fa-heart"></i>
                    </button> --}}
                </div>
                
                <div class="d-flex gap-2">
                    <a href="https://wa.me/62895631776702?text=Saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}%20{{ urlencode(route('product.show', $product->id).'?utm_source=whatsapp&utm_medium=product-page&utm_campaign=product-inquiry') }}" 
                        class="btn btn-outline-success" target="_blank">
                         <i class="fab fa-whatsapp me-2"></i> Chat via WhatsApp
                     </a>
                    <a href="{{ route('contact-us') }}" class="btn btn-outline-success">
                        <i class="fas fa-phone-alt me-2"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products">
        <h3 class="mb-4">Produk Terkait</h3>
        <div class="row">
            @foreach($relatedProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 product-card">
                    <div class="product-img-container">
                        <img src="{{ asset($product->image) }}" class="product-img" alt="{{ $product->name }}">
                        @if($product->stock <= 0)
                            <span class="stock-badge badge bg-danger">Habis</span>
                        @elseif($product->stock < 5)
                            <span class="stock-badge badge bg-warning text-dark">Hampir Habis</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($product->name, 30) }}</h5>
                        <p class="card-text text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-outline-primary w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// Change product image on thumbnail click
function changeImage(element) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = element.src;
    mainImage.alt = element.alt;
}

// Quantity controls
$(document).ready(function() {
    $('.quantity-plus').click(function() {
        const input = $(this).siblings('.quantity-input');
        const max = parseInt(input.attr('max'));
        let value = parseInt(input.val());
        if (value < max) {
            input.val(value + 1);
        }
    });
    
    $('.quantity-minus').click(function() {
        const input = $(this).siblings('.quantity-input');
        let value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });
    
    // Add to cart functionality
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const quantity = $('.quantity-input').val();
        const $btn = $(this);
        
        $btn.html('<span class="spinner-border spinner-border-sm"></span> Menambahkan...').prop('disabled', true);
        
        $.ajax({
            type: 'POST',
            url: '{{ route('cart.add') }}',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#cart-count').text(response.totalQuantity);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                
                $btn.html('<i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang').prop('disabled', false);
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    return;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON.message || 'Gagal menambahkan ke keranjang',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                
                $btn.html('<i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang').prop('disabled', false);
            }
        });
    });
});
</script>
@endsection