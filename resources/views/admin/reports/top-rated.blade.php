@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-3 px-md-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center py-3 gap-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape icon-lg bg-warning text-white rounded-circle me-3 flex-shrink-0"
                                style="padding: 12px;">
                                <i class="fas fa-trophy fs-4"></i>
                            </div>
                            <div>
                                <h2 class="h5 mb-0 fw-bold text-warning">PRODUK TERBAIK</h2>
                                <p class="mb-0 text-muted small">Berdasarkan rating dan ulasan pelanggan</p>
                            </div>
                        </div>
                        <div class="w-100 w-md-auto mt-3 mt-md-0">
                            <form action="{{ route('admin.reports.top-rated') }}" method="GET"
                                class="d-flex flex-column flex-md-row gap-2">
                                <div class="input-group flex-grow-1">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-calendar-alt text-muted"></i></span>
                                    <select name="time_range" class="form-select border-start-0 ps-3"
                                        onchange="this.form.submit()">
                                        <option value="month" {{ request('time_range') == 'month' ? 'selected' : '' }}>
                                            Bulan Ini</option>
                                        <option value="quarter" {{ request('time_range') == 'quarter' ? 'selected' : '' }}>3
                                            Bulan Terakhir</option>
                                        <option value="year" {{ request('time_range') == 'year' ? 'selected' : '' }}>Tahun
                                            Ini</option>
                                        <option value="all" {{ request('time_range') == 'all' ? 'selected' : '' }}>Semua
                                            Waktu</option>
                                    </select>
                                </div>
                                <div class="input-group flex-grow-1">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-filter text-muted"></i></span>
                                    <input type="number" name="limit" class="form-control border-start-0 ps-3"
                                        min="1" max="50" placeholder="Jumlah"
                                        value="{{ request('limit', 10) }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($topRated->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                                <h5 class="text-muted">Tidak ada data produk</h5>
                                <p class="text-muted">Belum ada produk yang menerima rating dari pelanggan</p>
                            </div>
                        @else
                            <div class="row">
                                <!-- Top Product Card -->
                                @if (isset($topRated[0]))
                                    <div class="col-12 col-lg-4 mb-4">
                                        <div class="card border-warning h-100">
                                            <div
                                                class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">
                                                    <i class="fas fa-crown text-warning me-2"></i>Produk Terbaik
                                                </h6>
                                                <span class="badge bg-warning text-dark">#1</span>
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="position-relative mb-3"
                                                    style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                                    <img src="{{ asset($topRated[0]->image) ?? asset('images/default-product.png') }}"
                                                        class="img-fluid rounded"
                                                        style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                        alt="{{ $topRated[0]->name }}">
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-success">
                                                            {{ number_format($topRated[0]->average_rating, 1) }} <i
                                                                class="fas fa-star"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="mb-1">{{ $topRated[0]->name }}</h5>
                                                <p class="text-muted mb-2">{{ $topRated[0]->brand ?? 'No Brand' }}</p>
                                                <div
                                                    class="d-flex justify-content-center align-items-center mb-2 flex-wrap">
                                                    <div class="me-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= floor($topRated[0]->average_rating))
                                                                <i class="fas fa-star text-warning"></i>
                                                            @elseif($i - 0.5 <= $topRated[0]->average_rating)
                                                                <i class="fas fa-star-half-alt text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">(
                                                        {{ $topRated[0]->rating_count }}
                                                        ulasan)</small>
                                                </div>

                                                <!-- Top 2 Reviews Preview -->
                                                @if ($topRated[0]->topReviews()->count() > 0)
                                                    <div class="mt-3 text-start">
                                                        <h6 class="small fw-bold mb-2">Ulasan Teratas:</h6>
                                                        @foreach ($topRated[0]->topReviews()->take(2) as $review)
                                                            <div class="card mb-2 border-0 bg-light">
                                                                <div class="card-body p-2">
                                                                    <div class="d-flex justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            @for ($i = 1; $i <= 5; $i++)
                                                                                @if ($i <= $review->rating)
                                                                                    <i
                                                                                        class="fas fa-star text-warning small"></i>
                                                                                @else
                                                                                    <i
                                                                                        class="far fa-star text-warning small"></i>
                                                                                @endif
                                                                            @endfor
                                                                        </div>
                                                                        <small
                                                                            class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                                                    </div>
                                                                    <p class="small mb-0 text-truncate-2">
                                                                        {{ $review->review }}</p>
                                                                    <small class="text-muted">
                                                                        {{ $review->order->customer->name }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                                                    <a href="{{ route('admin.products.edit', $topRated[0]->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <a href="{{ route('product.detail', $topRated[0]->slug) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                        data-bs-target="#reviewsModal{{ $topRated[0]->id }}">
                                                        <i class="fas fa-comment-alt me-1"></i> Ulasan
                                                    </button>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Other Top Products -->
                                <div class="col-12 col-lg-8">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th>Produk</th>
                                                    <th width="120">Rating</th>
                                                    <th width="100">Ulasan</th>
                                                    <th width="100">Penjualan</th>
                                                    <th width="120">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($topRated as $index => $product)
                                                    @if ($index > 0)
                                                        <tr class="cursor-pointer" data-bs-toggle="modal"
                                                            data-bs-target="#reviewsModal{{ $product->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Klik untuk melihat detail ulasan">
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $index == 1 ? 'info' : ($index == 2 ? 'secondary' : 'light') }} text-{{ $index == 1 ? 'white' : ($index == 2 ? 'white' : 'dark') }}">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="{{ asset($product->image) ?? asset('images/default-product.png') }}"
                                                                        class="rounded me-3" width="40" height="40"
                                                                        style="object-fit: cover;"
                                                                        alt="{{ $product->name }}">
                                                                    <div class="text-truncate">
                                                                        <h6 class="mb-0 text-truncate">
                                                                            {{ $product->name }}</h6>
                                                                        <small
                                                                            class="text-muted text-truncate">{{ $product->brand ?? 'No Category' }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress flex-grow-1 me-2"
                                                                        style="height: 6px;">
                                                                        <div class="progress-bar bg-warning"
                                                                            role="progressbar"
                                                                            style="width: {{ ($product->average_rating / 5) * 100 }}%"
                                                                            aria-valuenow="{{ $product->average_rating }}"
                                                                            aria-valuemin="0" aria-valuemax="5"></div>
                                                                    </div>
                                                                    <span
                                                                        class="fw-bold small">{{ number_format($product->average_rating, 1) }}</span>
                                                                </div>
                                                            </td>
                                                            <td>{{ $product->rating_count }}</td>
                                                            <td>{{ $product->salesCount() ?? 0 }}</td>
                                                            <td>
                                                                <div class="d-flex gap-1">
                                                                    {{-- <a href="{{ route('admin.product', $product->id) }}"
                                                                        class="btn btn-sm btn-outline-primary"
                                                                        data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <a href="{{ route('admin.product', $product->slug) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-secondary"
                                                                        data-bs-toggle="tooltip" title="Lihat">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a> --}}
                                                                    <button class="btn btn-sm btn-outline-warning"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#reviewsModal{{ $product->id }}"
                                                                        data-bs-toggle="tooltip" title="Ulasan">
                                                                        <i class="fas fa-comment-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modals -->
    @foreach ($topRated as $product)
        <div class="modal fade" id="reviewsModal{{ $product->id }}" tabindex="-1"
            aria-labelledby="reviewsModalLabel{{ $product->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="reviewsModalLabel{{ $product->id }}">
                            <i class="fas fa-comment-alt me-2"></i>Ulasan untuk {{ $product->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-column flex-md-row align-items-center mb-4 gap-2">
                            <div class="text-center">
                                <h1 class="mb-0">{{ number_format($product->average_rating, 1) }}</h1>
                                <div class="mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product->average_rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $product->average_rating)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $product->rating_count }} ulasan</small>
                            </div>
                            <div class="flex-grow-1 w-100">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="row align-items-center mb-2">
                                        <div class="col-3 col-md-2 text-end">
                                            <small>{{ $i }} <i class="fas fa-star text-warning"></i></small>
                                        </div>
                                        <div class="col-6 col-md-7">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ $product->rating_count > 0 ? ($product->{'rating_' . $i} / $product->rating_count) * 100 : 0 }}%"
                                                    aria-valuenow="{{ $product->{'rating_' . $i} }}" aria-valuemin="0"
                                                    aria-valuemax="{{ $product->rating_count }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted">{{ $product->{'rating_' . $i} }} ulasan</small>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="card mb-4">
                            <div class="card-body p-2">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="small fw-bold me-2">Filter:</span>
                                    {{-- href="{{ route('admin.reports.product-reviews', ['product' => $product->id]) }}" --}}
                                    <a
                                        class="btn btn-sm btn-outline-secondary filter-rating {{ !request()->has('rating') ? 'active' : '' }}">
                                        Semua
                                    </a>
                                    @for ($i = 5; $i >= 1; $i--)
                                        {{-- <a href="{{ route('admin.reports.product-reviews', ['product' => $product->id, 'rating' => $i]) }}" --}}
                                        <a
                                            class="btn btn-sm btn-outline-warning filter-rating {{ request('rating') == $i ? 'active' : '' }}">
                                            {{ $i }} <i class="fas fa-star"></i>
                                        </a>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3">Ulasan Terbaru</h6>
                        @forelse($product->recentReviews(5) as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $review->order->customer ?? asset('images/default-avatar.png') }}"
                                                class="rounded-circle me-2" width="32" height="32"
                                                style="object-fit: cover;" alt="{{ $review->order->customer->name }}">
                                            <h6 class="mb-0">{{ $review->order->customer->name }}</h6>
                                        </div>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <small
                                                class="text-muted ms-2">{{ $review->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                    <p class="mb-1">{{ $review->comment }}</p>
                                    @if ($review->media->count() > 0)
                                        <div class="mt-2">
                                            <div class="row g-2">
                                                @foreach ($review->media->take(3) as $image)
                                                    <div class="col-4">
                                                        <img src="{{ asset($image->file_path) }}" class="img-thumbnail"
                                                            style="width: 100%; height: 80px; object-fit: cover; cursor: pointer;"
                                                            onclick="showImageModal('{{ asset($image->file_path) }}')">
                                                    </div>
                                                @endforeach
                                                @if ($review->media->count() > 3)
                                                    <div class="col-4">
                                                        <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                            style="width: 100%; height: 80px; background-color: #f8f9fa; cursor: pointer;"
                                                            onclick="showImageModal('')">
                                                            <span class="text-muted">+{{ $review->images->count() - 3 }}
                                                                lagi</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                Belum ada ulasan untuk produk ini.
                            </div>
                        @endforelse

                        <!-- Pagination -->
                        @if ($product->reviews instanceof \Illuminate\Pagination\LengthAwarePaginator && $product->reviews->hasPages())
                            <div class="mt-4">
                                {{ $product->reviews->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gambar Ulasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImagePreview" src="" class="img-fluid" style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table th {
            white-space: nowrap;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .progress {
            background-color: #f0f0f0;
        }

        .img-fluid {
            max-height: 100%;
            object-fit: contain;
        }

        .badge {
            font-weight: 500;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: none;
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .filter-rating.active {
            background-color: #ffc107;
            color: #000;
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            color: #000;
        }

        @media (max-width: 767.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table td,
            .table th {
                white-space: nowrap;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .modal-body {
                max-height: 50vh;
            }

            .filter-rating {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 575.98px) {
            .input-group {
                width: 100% !important;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Tooltip initialization
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Update time range filter
            $('select[name="time_range"]').change(function() {
                $(this).closest('form').submit();
            });

            // Auto-submit form when limit changes on mobile
            $('input[name="limit"]').change(function() {
                if ($(window).width() < 768) {
                    $(this).closest('form').submit();
                }
            });

            // Highlight active rating filter
            $('.filter-rating').click(function(e) {
                e.preventDefault();
                window.location.href = $(this).attr('href');
            });
        });

        function showImageModal(imageUrl) {
            if (imageUrl) {
                $('#modalImagePreview').attr('src', imageUrl);
                $('#imagePreviewModal').modal('show');
            }
        }
    </script>
@endsection
