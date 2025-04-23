@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Detail Pesanan #{{ $order->order_id }}</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak Invoice
                </button>
                <a href="{{ route('admin.orders') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4 mb-4">
            <!-- Order Status Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-steps">
                            @foreach ($statusHistory as $status)
                                <div class="timeline-step {{ $status['is_active'] ? 'active' : '' }}">
                                    <div class="timeline-content">
                                        <div class="timeline-icon bg-{{ $status['color'] }}">
                                            <i class="{{ $status['icon'] }}"></i>
                                        </div>
                                        <p class="mb-0 text-small">{{ $status['label'] }}</p>
                                        <small class="text-muted">{{ $status['time'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info Card -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <!-- Customer Details -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pelanggan</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">Nama</dt>
                                    <dd class="col-sm-7">{{ $order->user->name }}</dd>

                                    <dt class="col-sm-5">Email</dt>
                                    <dd class="col-sm-7">{{ $order->user->email }}</dd>

                                    <dt class="col-sm-5">Telepon</dt>
                                    <dd class="col-sm-7">{{ $order->recipient_phone }}</dd>

                                    <dt class="col-sm-5">Member Sejak</dt>
                                    <dd class="col-sm-7">{{ $order->user->created_at->format('d M Y') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Details -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Pengiriman</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">Penerima</dt>
                                    <dd class="col-sm-7">{{ $order->recipient_name }}</dd>

                                    <dt class="col-sm-5">Alamat</dt>
                                    <dd class="col-sm-7">
                                        {{ $order->shipping_address }}<br>
                                        {{ $order->city }}, {{ $order->province }}<br>
                                        {{ $order->postal_code }}
                                    </dd>

                                    @if ($order->tracking_number)
                                        <dt class="col-sm-5">Resi</dt>
                                        <dd class="col-sm-7">
                                            <a href="{{ $order->tracking_url }}" target="_blank" class="text-primary">
                                                {{ $order->tracking_number }}
                                            </a>
                                            <small class="d-block text-muted">{{ $order->courier }} -
                                                {{ $order->service }}</small>
                                        </dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Produk Dipesan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="50"></th>
                                <th>Produk</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->product_image) }}" class="rounded" width="50"
                                            alt="{{ $item->product_name }}"
                                            onerror="this.src='{{ asset('images/default-product.png') }}'">
                                    </td>
                                    <td>
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Subtotal</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Ongkos Kirim</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Pembayaran</td>
                                <td class="text-end fw-bold text-primary">Rp
                                    {{ number_format($order->final_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Metode Pembayaran</dt>
                            <dd class="col-sm-7">{{ $order->payment_method ?? '-' }}</dd>

                            <dt class="col-sm-5">Status Pembayaran</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ $order->payment_status == 'paid' ? 'Lunas' : 'Menunggu Pembayaran' }}
                                </span>
                            </dd>

                            <dt class="col-sm-5">Tanggal Pembayaran</dt>
                            <dd class="col-sm-7">
                                {{ $order->payment_date ? $order->payment_date->format('d M Y H:i') : '-' }}</dd>

                            <dt class="col-sm-5">Referensi Pembayaran</dt>
                            <dd class="col-sm-7">{{ $order->payment_reference ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header {
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }

        .timeline-steps {
            display: flex;
            flex-direction: column;
            padding-left: 1.5rem;
        }

        .timeline-step {
            position: relative;
            padding: 0.5rem 0;
        }

        .timeline-step::before {
            content: "";
            position: absolute;
            left: -1.5rem;
            top: 1rem;
            width: 1px;
            height: 100%;
            background-color: #dee2e6;
        }

        .timeline-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: -2.5rem;
            top: 0.5rem;
        }

        .timeline-step.active .timeline-icon {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
        }

        .timeline-content {
            margin-left: 1rem;
        }

        table.table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Add dynamic status update functionality
        document.querySelectorAll('.status-action').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                const newStatus = this.dataset.status;

                // Implement AJAX status update here
                console.log(`Update order ${orderId} to ${newStatus}`);
            });
        });
    </script>
@endsection
