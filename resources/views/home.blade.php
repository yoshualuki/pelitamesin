@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
@endphp
@extends('customer.template')

@section('content')
<!-- Banner Section -->
<div class="banner-container mb-4">
    <img src="{{ asset('images/banner1.jpeg') }}" class="banner rounded" alt="Banner Toko">
</div>


<!-- New Products Section -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">New Arrivals</h2>
        {{-- <a href="{{ route('products.new') }}" class="btn btn-outline-primary">View All</a> --}}
    </div>
    
    <div class="scroll-container">
        <button class="scroll-btn prev">
            <i class="bi bi-chevron-left"></i>
        </button>
        
        <div class="products-scroll" id="newProductsScroll">
            @foreach ($newProducts as $product)
            <div class="product-card card">
                <div class="product-img-container">
                    <img src="{{ asset($product->image) }}" class="product-img" alt="{{ $product->name }}">
                    @if($product->stock <= 0)
                        <span class="stock-badge badge bg-danger">Sold Out</span>
                    @elseif($product->stock < 5)
                        <span class="stock-badge badge bg-warning text-dark">Low Stock</span>
                    @endif
                    <span class="position-absolute top-0 start-0 m-2 badge bg-success">New</span>
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($product->name, 30) }}</h5>
                    <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                    <p class="card-text fw-bold text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    
                    <button class="btn btn-sm btn-primary w-100 add-to-cart" data-product-id="{{ $product->id }}">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <button class="scroll-btn next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<!-- Top Products Section -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Top Products</h2>
        {{-- <a href="{{ route('products.top') }}" class="btn btn-outline-primary">View All</a> --}}
    </div>
    
    <div class="scroll-container">
        <button class="scroll-btn prev">
            <i class="bi bi-chevron-left"></i>
        </button>
        
        <div class="products-scroll" id="topProductsScroll">
            @foreach ($topProducts as $product)
            <div class="product-card card">
                <div class="product-img-container">
                    <img src="{{ asset($product->image) }}" class="product-img" alt="{{ $product->name }}">
                    @if($product->stock <= 0)
                        <span class="stock-badge badge bg-danger">Sold Out</span>
                    @elseif($product->stock < 5)
                        <span class="stock-badge badge bg-warning text-dark">Low Stock</span>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($product->name, 30) }}</h5>
                    <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                    <p class="card-text fw-bold text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    
                    <button class="btn btn-sm btn-primary w-100 add-to-cart" data-product-id="{{ $product->id }}">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <button class="scroll-btn next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>


@endsection

@section('scripts')
<script>
    function equalizeCardHeights() {
        $('.row').each(function() {
            var maxHeight = 0;
            $(this).find('.product-card').css('height', ''); // Reset heights
            
            $(this).find('.product-card').each(function() {
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
            });
            
            $(this).find('.product-card').height(maxHeight);
        });
    }

    $(window).resize(function() {
        equalizeCardHeights();
    });
    $(document).ready(function() {
        // Equalize card heights per row
        equalizeCardHeights();
        // Initialize scroll functionality for both sections
        initHorizontalScroll('topProductsScroll');
        initHorizontalScroll('newProductsScroll');
        
        function initHorizontalScroll(containerId) {
            const container = document.getElementById(containerId);
            const prevBtn = container.parentElement.querySelector('.prev');
            const nextBtn = container.parentElement.querySelector('.next');
            
            // Scroll buttons functionality
            prevBtn.addEventListener('click', () => {
                container.scrollBy({ left: -300, behavior: 'smooth' });
            });
            
            nextBtn.addEventListener('click', () => {
                container.scrollBy({ left: 300, behavior: 'smooth' });
            });
            
            // Hide buttons when at scroll extremes
            container.addEventListener('scroll', () => {
                const maxScroll = container.scrollWidth - container.clientWidth;
                prevBtn.style.display = container.scrollLeft <= 10 ? 'none' : 'flex';
                nextBtn.style.display = container.scrollLeft >= maxScroll - 10 ? 'none' : 'flex';
            });
            
            // Initial button state
            container.dispatchEvent(new Event('scroll'));
        }
        
        // Add to cart functionality
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            var $btn = $(this);
            
            $btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);
            
            $.ajax({
                type: 'POST',
                url: '{{ route('cart.add') }}',
                data: {
                    product_id: productId,
                    quantity: 1,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Check if the cart count element exists
                    if ($('#cart-count').length) {
                        // Update existing cart count
                        $('#cart-count').text(response.totalQuantity);
                    } else {
                        // Create and append new cart count element
                        $('#cart-container').append(
                            $('<span>', {
                                id: 'cart-count',
                                class: 'badge bg-primary ms-1',
                                text: response.totalQuantity || 1 // Fallback to 1 if totalQuantity is undefined
                            })
                        );
                    }
                    
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
                    $btn.html('<i class="bi bi-cart-plus"></i> Added!');
                    setTimeout(() => $btn.html('<i class="bi bi-cart-plus"></i> Add to Cart').prop('disabled', false), 1500);
                },
                error: function(xhr) {

                    $btn.html('<i class="bi bi-cart-plus"></i> Add to Cart').prop('disabled', false);
                    // alert(xhr.responseJSON.message || 'Failed to add to cart');
                    if (xhr.status === 401) {
                        // Redirect to login with intended URL
                        window.location.href = '/login';
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message || 'Failed to add to cart',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            });
        });
    });
</script>
@endsection