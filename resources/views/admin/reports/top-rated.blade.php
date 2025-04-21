@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                        <div class="mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape icon-lg bg-warning text-white rounded-circle me-3">
                                    <i class="fas fa-trophy fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h5 mb-0 fw-bold text-warning">PRODUK TERBAIK</h2>
                                    <p class="mb-0 text-muted small">Berdasarkan rating dan ulasan pelanggan</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <form action="{{ route('admin.reports.top-rated') }}" method="GET" class="d-flex gap-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-calendar-alt text-muted"></i></span>
                                    <select name="time_range" class="form-select border-start-0 ps-3" style="width: 160px;"
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
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-filter text-muted"></i></span>
                                    <input type="number" name="limit" class="form-control border-start-0 ps-3"
                                        style="width: 100px;" min="1" max="50" placeholder="Jumlah"
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
                                    <div class="col-lg-4 mb-4">
                                        <div class="card border-warning h-100">
                                            <div
                                                class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">
                                                    <i class="fas fa-crown text-warning me-2"></i>Produk Terbaik
                                                </h6>
                                                <span class="badge bg-warning text-dark">#1</span>
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="position-relative mb-3">
                                                    <img src="{{ asset($topRated[0]->image) ?? asset('images/default-product.png') }}"
                                                        class="img-fluid rounded" style="max-height: 180px; width: auto;"
                                                        alt="{{ $topRated[0]->name }}">
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-success">
                                                            {{ number_format($topRated[0]->average_rating, 1) }} <i
                                                                class="fas fa-star"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="mb-1">{{ $topRated[0]->name }}</h5>
                                                <p class="text-muted mb-2">
                                                    {{ $topRated[0]->brand ?? 'No Category' }}</p>
                                                <div class="d-flex justify-content-center mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($topRated[0]->average_rating))
                                                            <i class="fas fa-star text-warning"></i>
                                                        @elseif($i - 0.5 <= $topRated[0]->average_rating)
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <small class="ms-2">({{ $topRated[0]->rating_count }} ulasan)</small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Other Top Products -->
                                <div class="col-lg-8">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th>Produk</th>
                                                    <th width="120">Rating</th>
                                                    <th width="120">Ulasan</th>
                                                    <th width="120">Penjualan</th>
                                                    <th width="100">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($topRated as $index => $product)
                                                    @if ($index > 0)
                                                        <tr>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $index == 1 ? 'info' : ($index == 2 ? 'secondary' : 'light') }} text-{{ $index == 1 ? 'white' : ($index == 2 ? 'white' : 'dark') }}">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="{{ $product->image_url ?? asset('images/default-product.png') }}"
                                                                        class="rounded me-3" width="40" height="40"
                                                                        alt="{{ $product->name }}">
                                                                    <div>
                                                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                                                        <small
                                                                            class="text-muted">{{ $product->category->name ?? 'No Category' }}</small>
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
                                                                        class="fw-bold">{{ number_format($product->average_rating, 1) }}</span>
                                                                </div>
                                                            </td>
                                                            <td>{{ $product->rating_count }}</td>
                                                            <td>{{ $product->sales_count ?? 0 }}</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                        type="button"
                                                                        id="actionDropdown{{ $product->id }}"
                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-h"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu"
                                                                        aria-labelledby="actionDropdown{{ $product->id }}">
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('admin.products.edit', $product->id) }}">
                                                                                <i class="fas fa-edit me-2"></i> Edit
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('product.detail', $product->slug) }}"
                                                                                target="_blank">
                                                                                <i class="fas fa-eye me-2"></i> Lihat
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#reviewsModal{{ $product->id }}">
                                                                                <i class="fas fa-comment-alt me-2"></i>
                                                                                Ulasan
                                                                            </a>
                                                                        </li>
                                                                    </ul>
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="reviewsModalLabel{{ $product->id }}">
                            Ulasan untuk {{ $product->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 text-center">
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
                            <div class="flex-grow-1">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="row align-items-center mb-1">
                                        <div class="col-2 text-end">
                                            <small>{{ $i }} <i class="fas fa-star text-warning"></i></small>
                                        </div>
                                        <div class="col-7">
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

                        <h6 class="mb-3">Ulasan Terbaru</h6>
                        @forelse($product->recentReviews as $review)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $review->user->avatar ?? asset('images/default-avatar.png') }}"
                                                class="rounded-circle me-2" width="32" height="32"
                                                alt="{{ $review->user->name }}">
                                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        </div>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-1">{{ $review->comment }}</p>
                                    <small class="text-muted">{{ $review->created_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                Belum ada ulasan untuk produk ini.
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    @endforeach

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
            max-height: 180px;
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
            max-height: 70vh;
            overflow-y: auto;
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
        });
    </script>
@endsection
