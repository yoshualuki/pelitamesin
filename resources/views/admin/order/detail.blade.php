@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <!-- Order Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-receipt me-2 text-primary"></i>Detail Pesanan #{{ $order->order_id }}
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Pesanan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak Invoice
                </button>
                <a href="{{ route('admin.orders') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-history me-2 text-primary"></i>Status Pesanan
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="timeline-steps">
                    @foreach ($statusHistory as $status)
                        <div class="timeline-step {{ $status['is_active'] ? 'active' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-icon bg-{{ $status['color'] }}" style="margin-left: 24px">
                                    <i class="{{ $status['icon'] }} text-white"></i>
                                </div>
                                <p class="mb-0 fw-bold" style="margin-left: 16px">{{ $status['label'] }}</p>
                                <small class="text-muted" style="margin-left: 16px">{{ $status['time'] }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="row g-4 mb-4">
            <!-- Customer Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-circle me-2 text-primary"></i>Informasi Pelanggan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg me-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(session()->get('user')->name) }}&background=random"
                                    class="sidebar-profile-img me-3" alt="User" height="80" width="80">
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                <small class="text-muted">ID: {{ $order->user->id }}</small>
                            </div>
                        </div>
                        <hr class="my-3">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                {{ $order->user->email }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-phone me-2 text-muted"></i>
                                {{ $order->recipient_phone }}
                            </li>
                            <li>
                                <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                Bergabung {{ $order->user->created_at->format('d M Y') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-truck me-2 text-primary"></i>Pengiriman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2">Alamat Pengiriman</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>{{ $order->recipient_name }}</strong></p>
                                <p class="mb-1">{{ $order->shipping_address }}</p>
                                <p class="mb-1">{{ $order->city }}, {{ $order->province }}</p>
                                <p class="mb-0">{{ $order->postal_code }}</p>
                            </div>
                        </div>
                        @if ($order->tracking_number)
                            <hr class="my-3">
                            <div>
                                <h6 class="fw-bold mb-2">Info Pengiriman</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-1"><strong>No. Resi:</strong> {{ $order->tracking_number }}</p>
                                        <p class="mb-0"><strong>Kurir:</strong> {{ strtoupper($order->courier) }} -
                                            {{ $order->service }}</p>
                                    </div>
                                    <a href="{{ $order->tracking_url ?? '#' }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        Lacak <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2">Status Pembayaran</h6>
                            <span class="badge bg-{{ $paymentStatus['color'] }} p-2 fs-6">
                                <i class="fas fa-{{ $paymentStatus['icon'] ?? 'money-bill-wave' }} me-1"></i>
                                {{ $paymentStatus['label'] }}
                            </span>
                        </div>
                        <hr class="my-3">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-wallet me-2 text-muted"></i>
                                <strong>Metode:</strong> {{ $order->payment_method ?? '-' }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar me-2 text-muted"></i>
                                <strong>Tanggal:</strong>
                                {{ Carbon\Carbon::parse($order->payment_date)->format('d M Y H:i') }}
                            </li>
                            <li>
                                <i class="fas fa-hashtag me-2 text-muted"></i>
                                <strong>Referensi:</strong> {{ $order->payment_reference ?? '-' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Ordered -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-box-open me-2 text-primary"></i>Produk Dipesan
                </h5>
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
                                        <img src="{{ asset($item->product_image) }}" class="rounded border"
                                            width="50" alt="{{ $item->product_name }}"
                                            onerror="this.src='{{ asset('images/default-product.png') }}'">
                                    </td>
                                    <td>
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <small class="text-muted">SKU: {{ $item->product_sku ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Subtotal Produk</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Biaya Pengiriman</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if ($order->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Diskon</td>
                                    <td class="text-end fw-bold text-danger">-Rp
                                        {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
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

        <!-- Order Notes -->
        @if ($order->notes || $order->cancel_reason)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-sticky-note me-2 text-primary"></i>Catatan Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    @if ($order->cancel_reason)
                        <div class="alert alert-danger">
                            <h6 class="fw-bold mb-2"><i class="fas fa-times-circle me-2"></i>Alasan Pembatalan</h6>
                            <p class="mb-0">{{ $order->cancel_reason }}</p>
                        </div>
                    @endif
                    @if ($order->notes)
                        <div class="alert alert-info">
                            <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Catatan Tambahan</h6>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@section('styles')
    <style>
        /* Timeline Styles */
        .timeline-steps {
            display: flex;
            flex-direction: column;
            padding-left: 2rem;
            position: relative;
        }

        .timeline-steps::before {
            content: "";
            position: absolute;
            left: 1.25rem;
            top: 0.5rem;
            width: 2px;
            height: calc(100% - 1rem);
            background-color: #e9ecef;
        }

        .timeline-step {
            position: relative;
            padding: 1rem 0;
        }

        .timeline-step.active .timeline-content {
            transform: translateX(5px);
        }

        .timeline-step.active .timeline-icon {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
            transform: scale(1.1);
        }

        .timeline-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: -3.25rem;
            top: 1rem;
            transition: all 0.3s ease;
        }

        .timeline-content {
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .timeline-step.active .timeline-content {
            background-color: rgba(13, 110, 253, 0.1);
        }

        /* Card Styles */
        .card {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Table Styles */
        .table th {
            white-space: nowrap;
            font-weight: 600;
            background-color: #f8f9fa !important;
        }

        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05) !important;
        }

        /* Avatar Styles */
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Badge Styles */
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .timeline-steps {
                padding-left: 1.5rem;
            }

            .timeline-icon {
                width: 2rem;
                height: 2rem;
                left: -2.75rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Print button functionality
        document.getElementById('printOrderBtn').addEventListener('click', function() {
            window.print();
        });

        // Tooltip initialization
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
