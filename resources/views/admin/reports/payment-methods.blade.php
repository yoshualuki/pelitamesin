@extends('layouts.admin')


@section('content')
    @php

        // Helper methods untuk view
        function getPaymentMethodColor($method)
        {
            $colors = [
                'Bank Transfer' => '#4e73df',
                'Credit Card' => '#1cc88a',
                'E-Wallet' => '#f6c23e',
                'Cash on Delivery' => '#e74a3b',
                'Virtual Account' => '#36b9cc',
                'Retail Payment' => '#858796',
            ];

            return $colors[$method] ?? '#' . substr(md5($method), 0, 6);
        }
        function getPaymentMethodIcon($method)
        {
            $icons = [
                'Bank Transfer' => 'fa-university',
                'Credit Card' => 'fa-credit-card',
                'E-Wallet' => 'fa-wallet',
                'Cash on Delivery' => 'fa-money-bill-wave',
                'Virtual Account' => 'fa-vihara',
                'Retail Payment' => 'fa-store',
            ];

            return $icons[$method] ?? 'fa-money-check-alt';
        }
    @endphp
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Metode Pembayaran</h1>

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
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Metode Pembayaran
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $paymentMethods->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Transaksi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $paymentMethods->sum('order_count') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                                    Total Nilai Transaksi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($paymentMethods->sum('total_amount'), 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="row">
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Metode Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            @foreach ($paymentMethods as $method)
                                <span class="mr-2">
                                    <i class="fas fa-circle"
                                        style="color: {{ getPaymentMethodColor($method->payment_method) }}"></i>
                                    {{ $method->payment_method }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Detail Metode Pembayaran (Periode: {{ $timeframes[$timeframe] ?? 'Custom' }})
                        </h6>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="paymentMethodsTable" width="100%"
                                cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Metode Pembayaran</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Total Nilai</th>
                                        <th>Persentase</th>
                                        <th>Rata-rata/Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalOrders = $paymentMethods->sum('order_count');
                                        $totalAmount = $paymentMethods->sum('total_amount');
                                    @endphp
                                    @foreach ($paymentMethods as $method)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i
                                                        class="fas {{ getPaymentMethodIcon($method->payment_method) }} me-2 text-primary"></i>
                                                    <span>{{ $method->payment_method }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $method->order_count }}</td>
                                            <td>Rp {{ number_format($method->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ ($method->order_count / $totalOrders) * 100 }}%; 
                                                        background-color: {{ getPaymentMethodColor($method->payment_method) }}"
                                                        aria-valuenow="{{ ($method->order_count / $totalOrders) * 100 }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        {{ round(($method->order_count / $totalOrders) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp
                                                {{ number_format($method->total_amount / $method->order_count, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .progress {
            height: 20px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .progress-bar {
            border-radius: 4px;
            font-size: 0.75rem;
            line-height: 20px;
        }

        .chart-pie {
            position: relative;
            height: 250px;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Pie Chart
            const ctx = document.getElementById('paymentMethodChart').getContext('2d');
            const paymentMethodsChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($paymentMethods->pluck('payment_method')) !!},
                    datasets: [{
                        data: {!! json_encode($paymentMethods->pluck('order_count')) !!},
                        backgroundColor: {!! json_encode(
                            $paymentMethods->map(function ($method) {
                                return getPaymentMethodColor($method->payment_method);
                            }),
                        ) !!},
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} transaksi (${percentage}%)`;
                                }
                            }
                        },
                        legend: {
                            display: false,
                        },
                    },
                    cutout: '70%',
                },
            });

        });
    </script>
@endsection
