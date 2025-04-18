@extends('layouts.admin')
@php
    use App\Helpers\NumberHelper;
    use Illuminate\Support\Str;
@endphp
@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Laporan Stok Menipis</h1>
                <p class="text-muted">Produk dengan stok dibawah {{ $threshold }} unit</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Cetak Laporan
                </button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="lowStockTable">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Status</th>
                                <th class="text-center no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                                                class="rounded me-2" width="40" height="40"
                                                onerror="this.src='{{ asset('images/default-product.png') }}'">
                                            <div>
                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                                <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->brand ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="fw-bold {{ $item->stock == 0 ? 'text-danger' : 'text-warning' }}">
                                            {{ $item->stock ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->stock > 0)
                                            <span class="badge bg-warning">Stok Menipis</span>
                                        @else
                                            <span class="badge bg-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td class="text-center no-print">
                                        <a href="{{ route('admin.product.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                                            <h5 class="text-muted">Semua stok produk dalam kondisi baik</h5>
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

    @push('styles')
        <style>
            @media print {

                .navbar,
                .sidebar,
                .no-print {
                    display: none !important;
                }

                .card {
                    border: none !important;
                    box-shadow: none !important;
                }

                .container-fluid {
                    padding: 0 !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#lowStockTable').DataTable({
                    "order": [
                        [4, "asc"]
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            });
        </script>
    @endpush
@endsection
