@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Produk Rating Terendah</h1>

            <!-- Filter Controls -->
            <div class="d-flex">
                <div class="input-group me-3" style="width: 150px;">
                    <span class="input-group-text">Threshold</span>
                    <select class="form-select" id="thresholdSelect">
                        <option value="2" {{ $threshold == 2 ? 'selected' : '' }}>≤ 2</option>
                        <option value="3" {{ $threshold == 3 ? 'selected' : '' }}>≤ 3</option>
                        <option value="4" {{ $threshold == 4 ? 'selected' : '' }}>≤ 4</option>
                    </select>
                </div>

                <div class="input-group" style="width: 150px;">
                    <span class="input-group-text">Limit</span>
                    <select class="form-select" id="limitSelect">
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $limit == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Produk Rating Rendah
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $lowRated->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-thumbs-down fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Rating Rata-rata Terendah
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($lowRated->min('average_rating') ?? 0, 1) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star-half-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Ulasan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $lowRated->sum('rating_count') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Daftar Produk dengan Rating ≤ {{ $threshold }} ({{ $lowRated->count() }} dari {{ $limit }}
                    produk)
                </h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="lowRatedTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Produk</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Rating Rata-rata</th>
                                <th width="15%">Jumlah Ulasan</th>
                                <th width="20%">Distribusi Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowRated as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($product->image) ?? asset('images/default-product.png') }}"
                                                class="img-thumbnail me-3" width="40" alt="{{ $product->name }}">
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                <div class="text-muted small">{{ $product->brand ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $rating = $product->average_rating ?? 0;
                                                $fullStars = floor($rating);
                                                $halfStar = $rating - $fullStars >= 0.5 ? 1 : 0;
                                                $emptyStars = 5 - $fullStars - $halfStar;
                                            @endphp

                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor

                                            @if ($halfStar)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @endif

                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <i class="far fa-star text-warning"></i>
                                            @endfor

                                            <span class="ms-2">{{ number_format($rating, 1) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->rating_count }}</span>
                                    </td>
                                    <td>
                                        @if ($product->rating_count > 0)
                                            <div class="d-flex align-items-center">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <div class="progress me-1" style="height: 20px; width: 15%;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: {{ (($product->{"rating_$i"} ?? 0) / $product->rating_count) * 100 }}%"
                                                            aria-valuenow="{{ $product->{"rating_$i"} ?? 0 }}"
                                                            aria-valuemin="0" aria-valuemax="{{ $product->rating_count }}">
                                                            {{ $i }}
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada ulasan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p>Tidak ada produk dengan rating di bawah {{ $threshold }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .img-thumbnail {
            object-fit: cover;
            height: 40px;
            width: 40px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(220, 53, 69, 0.05);
        }

        .progress {
            height: 20px;
        }

        .progress-bar {
            font-size: 0.7rem;
            line-height: 20px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Filter change handler
            $('#thresholdSelect, #limitSelect').change(function() {
                const threshold = $('#thresholdSelect').val();
                const limit = $('#limitSelect').val();
                window.location.href = `?threshold=${threshold}&limit=${limit}`;
            });

            // Export functionality
            $('#exportBtn').click(function() {
                const threshold = $('#thresholdSelect').val();
                const limit = $('#limitSelect').val();

            });
        });
    </script>
@endsection
