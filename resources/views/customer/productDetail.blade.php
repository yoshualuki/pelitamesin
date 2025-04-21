@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
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
                    <img id="mainImage" src="{{ asset($product->image) }}" class="img-fluid main-image w-100"
                        alt="{{ $product->name }}">

                    @if ($product->images && count($product->images) > 0)
                        <div class="thumbnail-container">
                            <img src="{{ asset($product->image) }}" class="thumbnail" onclick="changeImage(this)">
                            @foreach ($product->images as $image)
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

                    @if ($product->brand)
                        <div class="mb-3">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-tag me-1"></i> {{ $product->brand }}
                            </span>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-1">
                        <div class="product-price me-3">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @if ($product->discount > 0)
                            <span class="text-decoration-line-through text-muted me-2">Rp
                                {{ number_format($product->original_price, 0, ',', '.') }}</span>
                            <span class="badge bg-danger">{{ $product->discount }}% OFF</span>
                        @endif
                    </div>
                    {{-- Product Rating Stars and Count --}}
                    @php
                        $avg = round($product->average_rating ?? 0, 1);
                        $count = $product->rating_count ?? 0;
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avg))
                                <i class="fas fa-star text-warning"></i>
                            @elseif ($i - $avg < 1 && $avg - floor($avg) >= 0.5)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <span class="ms-2">{{ $avg }}</span>
                        <span class="ms-2 text-muted">({{ $count }} ulasan)</span>
                    </div>

                    <div class="product-meta">
                        <div class="product-meta-item">
                            <i class="fas fa-box"></i>
                            <span>Stok:
                                @if ($product->stock <= 0)
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

                        @if ($product->specifications)
                            <div class="mt-3">
                                <h6>Spesifikasi:</h6>
                                <ul>
                                    @foreach ($product->specifications as $spec)
                                        <li>{{ $spec }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2 mb-4">
                        <div class="input-group" style="width: 120px;">
                            <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                            <input type="number" class="form-control text-center quantity-input" value="1"
                                min="1" max="{{ $product->stock }}">
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
                        <a href="https://wa.me/62895631776702?text=Saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}%20{{ urlencode(route('product.show', $product->id) . '?utm_source=whatsapp&utm_medium=product-page&utm_campaign=product-inquiry') }}"
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

        {{-- Review Section (below product info) --}}
        <div class="row">
            <div class="col-lg-12 mb-8">
                <div class="h-100">
                    <h3 class="mb-4">Ulasan Pembeli</h3>
                    @if ($product->ratings->count() > 0)
                        @foreach ($product->ratings->sortByDesc('created_at')->take(5) as $rating)
                            <div class="review-card mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="review-author me-2">{{ $rating->user->name ?? 'Pelanggan' }}</span>
                                    <span class="review-date">{{ $rating->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $rating->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="mb-2">{{ $rating->review }}</div>
                                @if ($rating->media->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($rating->media as $media)
                                            @if (Str::endsWith($media->file_path, ['jpg', 'jpeg', 'png']))
                                                <a href="{{ $media->file_path }}" target="_blank">
                                                    <img src="{{ $media->file_path }}" alt="Review Photo"
                                                        style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted">Belum ada ulasan untuk produk ini.</div>
                    @endif
                </div>
            </div>

        </div>
        {{-- End Review Section --}}

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="related-products">
                <h3 class="mb-4">Produk Terkait</h3>
                <div class="row">
                    @foreach ($relatedProducts as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 product-card">
                                <div class="product-img-container">
                                    <img src="{{ asset($product->image) }}" class="product-img"
                                        alt="{{ $product->name }}">
                                    @if ($product->stock <= 0)
                                        <span class="stock-badge badge bg-danger">Habis</span>
                                    @elseif($product->stock < 5)
                                        <span class="stock-badge badge bg-warning text-dark">Hampir Habis</span>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($product->name, 30) }}</h5>
                                    <p class="card-text text-primary fw-bold">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>

                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('product.show', $product->id) }}"
                                        class="btn btn-sm btn-outline-primary w-100">
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

                $btn.html('<span class="spinner-border spinner-border-sm"></span> Menambahkan...').prop(
                    'disabled', true);

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

                        $btn.html('<i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang')
                            .prop('disabled', false);
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = '/login?redirect=' + encodeURIComponent(
                                window.location.pathname);
                            return;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON.message ||
                                'Gagal menambahkan ke keranjang',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        $btn.html('<i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang')
                            .prop('disabled', false);
                    }
                });
            });
        });

        // All reviews data for modal (pass from backend as JSON)
        const allReviews = @json($product->ratings->sortByDesc('created_at')->values());
        const reviewsPerPage = 6;
        let currentPage = 1;
        let currentFilter = '';

        function renderReviews(page = 1, filter = '') {
            let filtered = allReviews;
            if (filter) {
                filtered = filtered.filter(r => r.rating == filter);
            }
            const start = (page - 1) * reviewsPerPage;
            const end = start + reviewsPerPage;
            const paged = filtered.slice(start, end);

            let html = '';
            paged.forEach(rating => {
                html += `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="review-card h-100">
                        <div class="d-flex align-items-center mb-2">
                            <span class="review-author me-2">${rating.user?.name ?? 'Pelanggan'}</span>
                            <span class="review-date">${(new Date(rating.created_at)).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                        </div>
                        <div class="mb-1">
                            ${[1,2,3,4,5].map(i => i <= rating.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>').join('')}
                        </div>
                        <div class="mb-2">${rating.review ?? ''}</div>
                        ${rating.media && rating.media.length > 0 ? `
                                                    <div class="d-flex flex-wrap gap-2">
                                                        ${rating.media.map(media =>
                                                            (media.file_path.match(/\.(jpg|jpeg|png)$/i))
                                                            ? `<a href="${media.file_path}" target="_blank">
                                        <img src="${media.file_path}" alt="Review Photo" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                    </a>` : ''
                                                        ).join('')}
                                                    </div>
                                                ` : ''}
                    </div>
                </div>
                `;
            });
            document.getElementById('allReviewsList').innerHTML = html || '<div class="text-muted">Tidak ada ulasan.</div>';

            // Pagination
            let totalPages = Math.ceil(filtered.length / reviewsPerPage);
            let pagHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `<li class="page-item${i === page ? ' active' : ''}">
                    <a class="page-link" href="#" onclick="goToReviewPage(${i});return false;">${i}</a>
                </li>`;
            }
            document.getElementById('reviewsPagination').innerHTML = pagHtml;
        }

        function goToReviewPage(page) {
            currentPage = page;
            renderReviews(currentPage, currentFilter);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('allReviewsModal');
            if (modal) {
                modal.addEventListener('show.bs.modal', function() {
                    renderReviews(1, '');
                });
            }
            document.getElementById('filterRating').addEventListener('change', function() {
                currentFilter = this.value;
                currentPage = 1;
                renderReviews(currentPage, currentFilter);
            });
        });
    </script>
@endsection
