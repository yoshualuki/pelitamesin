@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dasbor Overview</h1>
        
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 hover-scale">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pesanan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                            <div class="mt-2 text-xs">
                                <span class="{{ $totalOrdersChange >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $totalOrdersChange >= 0 ? 'up' : 'down' }}"></i> 
                                    {{ abs($totalOrdersChange) }}% dari periode sebelumnya
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 hover-scale">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan (Bulanan)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@money($monthlyRevenue)</div>
                            <div class="mt-2 text-xs">
                                <span class="{{ $revenueChange >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}"></i> 
                                    {{ abs($revenueChange) }}% dari bulan lalu
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 hover-scale">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pesanan Tertunda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                            <div class="mt-2 text-xs">
                                <span class="{{ $pendingChange >= 0 ? 'text-danger' : 'text-success' }}">
                                    <i class="fas fa-arrow-{{ $pendingChange >= 0 ? 'up' : 'down' }}"></i> 
                                    {{ abs($pendingChange) }}% dari minggu lalu
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 hover-scale">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pesanan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyOrders }}</div>
                            <div class="mt-2 text-xs">
                                <span class="{{ $dailyChange >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $dailyChange >= 0 ? 'up' : 'down' }}"></i> 
                                    {{ abs($dailyChange) }}% dari kemarin
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Order Chart with Tabs -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Analisis Pesanan</h6>
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-period="week">Mingguan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-period="month">Bulanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-period="year">Tahunan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="orderChart" height="350"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution with Details -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Status Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="orderStatusChart" height="250"></canvas>
                    </div>
                    <div class="mt-4">
                        @foreach($orderStatusData as $status)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <i class="fas fa-circle mr-2" style="color: {{ $status['color'] }}"></i>
                                <span class="text-sm">
                                    
                                    @switch($status['status'])
                                    @case('waiting_payment') Menunggu Pembayaran @break
                                    @case('waiting_confirmation') Menunggu Konfirmasi @break
                                    @case('processing') Diproses @break
                                    @case('shipped') Dikirim @break
                                    @case('completed') Selesai @break
                                    @case('cancelled') Dibatalkan @break
                                    @case('partially_refunded') Pengembalian Sebagian @break
                                    @case('refunded') Dikembalikan @break
                                    @default {{ ucfirst($order->status) }} @endswitch
                                </span>
                            </div>
                            <div class="font-weight-bold">
                                {{ $status['count'] }} ({{ number_format(($status['count']/$totalOrders)*100, 1) }}%)
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <!-- Recent Transactions with Action Buttons -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terkini</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filter:</div>
                            <a class="dropdown-item" href="#">Semua</a>
                            <a class="dropdown-item" href="#">Selesai</a>
                            <a class="dropdown-item" href="#">Tertunda</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $order)
                                <tr>
                                    <td class="font-weight-bold">#{{ $order->order_id }}</td>
                                    <td>{{ $order->created_at->format('d M, H:i') }}</td>
                                    <td>
                                        <span class="badge badge-@switch($order->status)
                                            @case('completed')success @break
                                            @case('processing')primary @break
                                            @case('shipped')info @break
                                            @case('waiting_payment')warning @break
                                            @case('waiting_confirmation')secondary @break
                                            @case('cancelled')danger @break
                                            @case('refunded')dark @break
                                            @case('partially_refunded')warning @break
                                            @defaultsecondary @endswitch">
                                            @switch($order->status)
                                                @case('waiting_payment') Menunggu Pembayaran @break
                                                @case('waiting_confirmation') Menunggu Konfirmasi @break
                                                @case('processing') Diproses @break
                                                @case('shipped') Dikirim @break
                                                @case('completed') Selesai @break
                                                @case('cancelled') Dibatalkan @break
                                                @case('partially_refunded') Pengembalian Sebagian @break
                                                @case('refunded') Dikembalikan @break
                                                @default {{ ucfirst($order->status) }} @endswitch
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">@money($order->total_amount)</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Tidak ada transaksi terkini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right mt-3">
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-primary">
                            Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products with Interactive Chart -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
                </div>
                <div class="card-body">
                    
                    @foreach($topProducts as $product)
                    <div class="row align-items-center mb-3 product-item" data-product-id="{{ $product->id }}">
                        <div class="col-1">
                            <span class="badge badge-secondary">{{ $loop->iteration }}</span>
                        </div>
                        <div class="col-3">
                            <img src="{{ asset($product->image) ?? asset('images/default-product.png') }}" 
                                 class="img-fluid rounded border" 
                                 alt="{{ $product->name }}"
                                 style="max-height: 100px; width: auto;">
                        </div>
                        <div class="col-8">
                            <div class="text-sm font-weight-bold text-primary mb-1">{{ $product->name }}</div>
                            <div class="small text-muted">
                                <span class="text-success">@money($product->revenue)</span> • 
                                <span>{{ $product->sales_count }} terjual</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Order Chart with Interactive Controls
    const orderCtx = document.getElementById('orderChart').getContext('2d');
    const orderChart = new Chart(orderCtx, {
        type: 'line',
        data: {
            labels: @json($orderChartData['labels']),
            datasets: [{
                label: 'Pesanan',
                data: @json($orderChartData['data']),
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($orderStatusData->map(function($item) {
                switch($item['status']) {
                case 'waiting_payment': return 'Menunggu Pembayaran';
                case 'waiting_confirmation': return 'Menunggu Konfirmasi';
                case 'processing': return 'Diproses';
                case 'shipped': return 'Dikirim';
                case 'completed': return 'Selesai';
                case 'cancelled': return 'Dibatalkan';
                case 'partially_refunded': return 'Pengembalian Sebagian';
                case 'refunded': return 'Dikembalikan';
                default: return ucfirst($item['status']);
            }
            })),
            datasets: [{
                data: @json($orderStatusData->pluck('count')),
                backgroundColor: @json($orderStatusData->pluck('color')),
                borderWidth: 0,
                hoverOffset: 10
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        },
    });

    // Interactive Controls
    document.querySelectorAll('[data-period]').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.nav-pills .nav-link').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            
            document.getElementById('orderChart').style.opacity = '0.5';
            
            const period = this.getAttribute('data-period');
            fetch(`/admin/dashboard/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    orderChart.data.labels = data.labels;
                    orderChart.data.datasets[0].data = data.data;
                    orderChart.update();
                    // Sembunyikan loading
                    document.getElementById('orderChart').style.opacity = '1';
                });
        });
    });


    // Product item click handler
    document.querySelectorAll('.product-item').forEach(item => {
        item.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            window.location.href = `/admin/products/${productId}`;
        });
    });

    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
@endsection