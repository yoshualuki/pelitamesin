@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Laporan Pesanan Dibatalkan</h1>
            </div>
            <div class="col-auto">
                <form class="d-flex gap-2">
                    <select name="timeframe" class="form-select">
                        <option value="day" {{ $timeframe == 'day' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $timeframe == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $timeframe == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ $timeframe == 'year' ? 'selected' : '' }}>Tahun Ini</option>
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

        <div class="row mb-4">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-danger h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Pesanan Dibatalkan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($totalCancelled, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Nilai Dibatalkan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Pesanan Dibatalkan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="cancelledOrdersTable">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th class="text-end">Total</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cancelledOrders as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div>
                                            <span class="fw-bold">{{ $order->customer->name }}</span>
                                            <small class="d-block text-muted">{{ $order->customer->phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($order->items as $item)
                                                <li>{{ $item->products->name }} ({{ $item->quantity }}x)</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($order->final_amount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                            <h5 class="text-muted">Tidak ada pesanan yang dibatalkan</h5>
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
                $('#cancelledOrdersTable').DataTable({
                    "order": [
                        [1, "desc"]
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            });
        </script>
    @endpush
@endsection
