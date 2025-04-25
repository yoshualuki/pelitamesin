@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Produk Tidak Laku</h1>

            <!-- Timeframe Selector -->
            <div class="btn-group" role="group">
                @php
                    $timeframes = [
                        'day' => 'Hari Ini',
                        'week' => 'Minggu Ini',
                        'month' => 'Bulan Ini',
                        'year' => 'Tahun Ini',
                    ];
                @endphp

                @foreach ($timeframes as $key => $label)
                    <a href="?timeframe={{ $key }}"
                        class="btn btn-sm {{ $timeframe == $key ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $label }}
                    </a>
                @endforeach
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
                                    Total Produk Tidak Laku
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $unsoldProducts->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
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
                    Daftar Produk (Periode: {{ $timeframes[$timeframe] ?? 'Custom' }})
                </h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="unsoldTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Produk</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Stok</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unsoldProducts as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($product->image) }}" class="img-thumbnail me-3"
                                                width="40" alt="{{ $product->name }}">
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                <div class="text-muted small">{{ $product->brand ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->brand ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock > 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning me-2">
                                                <i class="fas fa-star"></i>
                                                {{ number_format($product->average_rating, 1) }}
                                            </span>
                                            <small class="text-muted">({{ $product->rating_count }})</small>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p>Tidak ada produk yang tidak laku dalam periode ini</p>
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
            background-color: rgba(78, 115, 223, 0.05);
        }

        .badge {
            font-size: 0.8em;
            font-weight: 600;
            padding: 0.35em 0.65em;
        }
    </style>
@endsection
