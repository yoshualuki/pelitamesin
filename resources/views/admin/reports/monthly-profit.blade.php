@extends('layouts.admin')
@php
    use App\Helpers\NumberHelper;
    use Illuminate\Support\Carbon;
@endphp
@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Laporan Profit Bulanan</h1>
            </div>
            <div class="col-auto">
                <form class="d-flex gap-2">
                    <select name="month" class="form-select">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ Carbon::create(null, $m, 1)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="form-select">
                        @foreach (range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
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
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pendapatan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($revenue, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-danger h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Modal</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($cogs, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Profit</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($revenue - $cogs, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="profitTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>ID Order</th>
                                <th>Produk</th>
                                <th class="text-end">Modal</th>
                                <th class="text-end">Pendapatan</th>
                                <th class="text-end">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->updated_at->format('d M Y') }}</td>
                                    <td>{{ $transaction->order_id }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($transaction->items as $item)
                                                <li>{{ $item->product_name }} ({{ $item->quantity }}x)</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-end">Rp
                                        {{ number_format(
                                            $transaction->items->sum(function ($item) {
                                                return $item->buy_price * $item->quantity;
                                            }),
                                            0,
                                            ',',
                                            '.',
                                        ) }}
                                    </td>
                                    <td class="text-end">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                    <td class="text-end">Rp
                                        {{ number_format(
                                            $transaction->final_amount -
                                                $transaction->items->sum(function ($item) {
                                                    return $item->buy_price * $item->quantity;
                                                }),
                                            0,
                                            ',',
                                            '.',
                                        ) }}
                                    </td>
                                </tr>
                            @endforeach
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
                $('#profitTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            });
        </script>
    @endpush
@endsection
