@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Laporan Produk Terlaris</h1>
            </div>
            <div class="col-auto">
                <form class="d-flex gap-2">
                    <select name="timeframe" class="form-select">
                        <option value="day" {{ $timeframe == 'day' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $timeframe == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $timeframe == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $timeframe == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                    <select name="limit" class="form-select">
                        <option value="5" {{ $limit == 5 ? 'selected' : '' }}>Top 5</option>
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="topProductsTable">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Produk</th>
                                <th class="text-center">Total Terjual</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $index => $product)
                                <tr>
                                    <td class="align-middle">
                                        @if ($index < 3)
                                            <span class="badge bg-{{ ['warning', 'secondary', 'bronze'][$index] }} fs-5">
                                                #{{ $index + 1 }}
                                            </span>
                                        @else
                                            #{{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                                class="rounded me-2" width="48" height="48"
                                                onerror="this.src='{{ asset('images/default-product.png') }}'">
                                            <div>
                                                <h6 class="mb-0">{{ $product->name }}</h6>
                                                <small
                                                    class="text-muted">{{ $product->category->name ?? 'Tanpa Kategori' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="fw-bold">{{ number_format($product->total_quantity, 0, ',', '.') }}</span>
                                        <small class="text-muted d-block">Unit</small>
                                    </td>
                                    <td class="text-end align-middle">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end align-middle">
                                        Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                                            <h5 class="text-muted">Belum ada data penjualan</h5>
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
            .badge.bg-bronze {
                background-color: #cd7f32;
                color: white;
            }

            @media print {

                .navbar,
                .sidebar,
                form,
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
                $('#topProductsTable').DataTable({
                    "order": [
                        [2, "desc"]
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            });
        </script>
    @endpush
@endsection
