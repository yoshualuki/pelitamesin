@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col-auto">
                <h1 class="h3 mb-0 text-gray-800">Laporan Transaksi Harian</h1>
            </div>
            <div class="col-auto">
                <form class="d-flex gap-2">
                    <input type="date" class="form-control" name="date" value="{{ $date }}"
                        max="{{ date('Y-m-d') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Filter
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
                                    Total Transaksi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransactions }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                    Total Pendapatan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
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
                <div class="card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Rata-rata Transaksi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
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
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi</h6>
                <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>Waktu</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->order_id }}</td>
                                    <td>{{ $transaction->created_at->format('H:i') }}</td>
                                    <td>{{ $transaction->customer->name }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $transaction->status === 'completed'
                                                ? 'success'
                                                : ($transaction->status === 'cancelled'
                                                    ? 'danger'
                                                    : 'primary') }}">
                                            @switch($transaction->status)
                                                @case('waiting_payment')
                                                    Menunggu Pembayaran
                                                @break

                                                @case('waiting_confirmation')
                                                    Menunggu Konfirmasi
                                                @break

                                                @case('processing')
                                                    Diproses
                                                @break

                                                @case('shipped')
                                                    Dikirim
                                                @break

                                                @case('completed')
                                                    Selesai
                                                @break

                                                @case('cancelled')
                                                    Dibatalkan
                                                @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $transaction->payment_method }}</td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                                <h5 class="text-muted">Tidak ada transaksi</h5>
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
                    .card-header button,
                    form {
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
    @endsection
