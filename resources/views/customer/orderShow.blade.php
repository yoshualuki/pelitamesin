@extends('customer.template')
@php
// Tambahkan di bagian @php di blade
function formatPaymentExpiry($expiry) {
    if (!$expiry) return '-';
    
    $now = now();
    if ($now > $expiry) {
        return '<span class="text-danger">Expired</span>';
    }
    
    return $expiry->format('d M Y H:i') . ' (' . $now->diffInHours($expiry) . ' jam lagi)';
}
@endphp

@push('style')
    .payment-instruction {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border-left: 4px solid #0d6efd;
    }

    .payment-instruction h6 {
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .list-group-numbered {
        counter-reset: item;
    }

    .list-group-numbered li:before {
        content: counter(item) ".";
        counter-increment: item;
        position: absolute;
        left: 0;
        font-weight: bold;
    }
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Pesanan #{{ $order->order_id }}</h2>
        <span class="badge bg-{{ getStatusColor($order->status) }} fs-6">
            {{ $statuses[$order->status] ?? $order->status }}
        </span>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Produk Pesanan</h5>
                </div>
                <div class="card-body">
                    @foreach ($order->items as $item)
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            <img src="{{ asset($item->product->image) }}" 
                                 alt="{{ $item->product->name }}"
                                 class="img-fluid rounded" 
                                 style="max-height: 80px; width: auto;">
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <p class="mb-1 text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            <p class="mb-0">Jumlah: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="mb-0 fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-6">
                            <p>Subtotal Produk</p>
                        </div>
                        <div class="col-6 text-end">
                            <p>Rp {{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <p>Biaya Pengiriman</p>
                        </div>
                        <div class="col-6 text-end">
                            <p>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="row border-top pt-2 mt-2">
                        <div class="col-6">
                            <h5>Total Pembayaran</h5>
                        </div>
                        <div class="col-6 text-end">
                            <h5>Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information Section -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($order->status == 'waiting_payment')
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Batas Waktu Pembayaran</h6>
                            <p class="mb-2">
                                <i class="fas fa-clock me-2"></i>
                                Selesaikan pembayaran sebelum: 
                                <strong>{{ $order->created_at->addHours(24)->format('d M Y H:i') }}</strong>
                            </p>
                            <p class="small mb-0">
                                ({{ $order->created_at->addHours(24)->diffForHumans() }})
                            </p>
                        </div>
                    @endif

                    <h6>Metode Pembayaran</h6>
                    <p class="mb-3">
                        @if($order->payment_method)
                            {{ $order->payment_method }}
                            @if($order->payment_code)
                                <span class="badge bg-light text-dark ms-2">{{ $order->payment_code }}</span>
                            @endif
                        @else
                            Belum dipilih
                        @endif
                    </p>

                    @if($order->status == 'pending' && $order->payment_code)
                        <div class="payment-instruction mt-4">
                            <h6 class="text-primary">Instruksi Pembayaran</h6>
                            
                            @if(str_contains(strtolower($order->payment_method), 'virtual akun'))
                                <ol class="list-group list-group-numbered">
                                    <li class="list-group-item border-0 p-1 ps-4">Masuk ke aplikasi mobile banking atau ATM bank {{ str_replace('VA ', '', $order->payment_method) }}</li>
                                    <li class="list-group-item border-0 p-1 ps-4">Pilih menu <strong>Transfer/Pembayaran</strong></li>
                                    <li class="list-group-item border-0 p-1 ps-4">Masukkan nomor Virtual Account: <strong>{{ $order->payment_code }}</strong></li>
                                    <li class="list-group-item border-0 p-1 ps-4">Masukkan jumlah pembayaran: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                                    <li class="list-group-item border-0 p-1 ps-4">Ikuti instruksi selanjutnya untuk menyelesaikan pembayaran</li>
                                </ol>
                            @elseif(str_contains(strtolower($order->payment_method), 'credit card'))
                                <p>Silakan selesaikan pembayaran kartu kredit Anda melalui halaman pembayaran Midtrans.</p>
                            @endif
                            
                            <div class="alert alert-warning mt-3 small">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Pesanan akan otomatis dibatalkan jika tidak dibayar dalam waktu 24 jam.
                                <br/>
                                <p class="mb-2">
                                    Selesaikan pembayaran dalam: 
                                    <strong id="payment-countdown">
                                        {{ $order->created_at->addHours(24)->diffForHumans() }}
                                    </strong>
                                </p>
                            </div>
                        </div>
                    @endif
                    @if($order->payment_date)
                        <div class="alert alert-success mt-3">
                            <h6 class="alert-heading">Pembayaran Berhasil</h6>
                            <p class="mb-1">Metode: {{ $order->payment_method }}</p>
                            <p class="mb-1">Kode: {{ $order->payment_code ?? '-' }}</p>
                            <p class="mb-0">Waktu: {{ \Carbon\Carbon::parse($order->payment_date)->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Order Status Timeline -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $order->status == 'waiting_payment' ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Menunggu Pembayaran</h6>
                                <p class="text-muted small">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status == 'processing' ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Pesanan Diproses</h6>
                                @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed')
                                <p class="text-muted small">{{ $order->updated_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status == 'shipped' ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Pesanan Dikirim</h6>
                                @if($order->status == 'shipped' || $order->status == 'completed')
                                <p class="text-muted small">{{ $order->updated_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status == 'completed' ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Pesanan Selesai</h6>
                                @if($order->status == 'completed')
                                <p class="text-muted small">{{ $order->updated_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <h6>Kurir</h6>
                    <p class="mb-3">{{ strtoupper($order->courier) }}</p>
                    
                    <h6>Layanan</h6>
                    <p class="mb-3">{{ $order->service }}</p>
                    
                    <h6>Estimasi Sampai</h6>
                    <p class="mb-3">{{ $order->estimated_delivery }} hari</p>
                    
                    <h6>No. Resi</h6>
                    <p class="mb-3">{{ $order->tracking_number ?? 'Belum tersedia' }}</p>
                    
                    <h6>Alamat Pengiriman</h6>
                    <p class="mb-0">
                        {{ $order->shipping_address }}<br>
                        {{ $order->city }}, {{ $order->province }}
                    </p>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <h6>Nama Penerima</h6>
                    <p class="mb-3">{{ $order->recipient_name }}</p>
                    
                    <h6>No. Telepon</h6>
                    <p class="mb-3">{{ $order->recipient_phone }}</p>
                    
                    <h6>Email</h6>
                    <p class="mb-0">{{ $order->user->email }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    @if($order->status == 'shipped')
                    <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#confirmDeliveryModal">
                        <i class="fas fa-check-circle me-2"></i> Konfirmasi Diterima
                    </button>
                    @endif
                    
                    @if($order->status == 'completed' && $order->completed_at->diffInDays(now()) <= 7)
                    <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#returnRequestModal">
                        <i class="fas fa-undo me-2"></i> Ajukan Retur
                    </button>
                    @endif
                    
                    @if(in_array($order->status, ['waiting_payment', 'processing']))
                    <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        <i class="fas fa-times-circle me-2"></i> Batalkan Pesanan
                    </button>
                    @endif
                    
                    <a href="{{ route('orders') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return Request Modal -->
<div class="modal fade" id="returnRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajukan Retur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders', $order->order_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Produk</label>
                        @foreach($order->items as $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="items[]" value="{{ $item->id }}" id="item-{{ $item->id }}">
                            <label class="form-check-label" for="item-{{ $item->id }}">
                                {{ $item->product->name }} (Qty: {{ $item->quantity }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <label for="returnReason" class="form-label">Alasan Retur</label>
                        <select class="form-select" id="returnReason" name="reason" required>
                            <option value="">Pilih alasan</option>
                            <option value="damaged">Produk Rusak</option>
                            <option value="wrong_item">Barang Tidak Sesuai</option>
                            <option value="not_needed">Tidak Dibutuhkan</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="returnNote" class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" id="returnNote" name="note" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Retur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirm Delivery Modal -->
<!-- Confirm Delivery Modal -->
<div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Konfirmasi Pesanan Diterima</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.confirm-delivery', $order->order_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Silakan konfirmasi bahwa Anda telah menerima pesanan ini. Setelah dikonfirmasi, pesanan akan ditandai sebagai selesai.
                    </div>
                    
                    <div class="mb-3">
                        <label for="deliveryNotes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="deliveryNotes" name="notes" rows="3" placeholder="Masukkan catatan tambahan jika ada"></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmCondition" required>
                        <label class="form-check-label" for="confirmCondition">
                            Saya menyatakan bahwa pesanan telah diterima dalam kondisi baik dan sesuai
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> Konfirmasi Diterima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Batalkan Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.cancel', $order->order_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                        <select class="form-select" id="cancelReason" name="reason" required>
                            <option value="">Pilih alasan pembatalan</option>
                            <option value="change_mind">Saya berubah pikiran</option>
                            <option value="duplicate_order">Pesanan ganda</option>
                            <option value="shipping_too_long">Pengiriman terlalu lama</option>
                            <option value="found_cheaper">Menemukan harga lebih murah</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancelNotes" class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" id="cancelNotes" name="notes" rows="3" placeholder="Jelaskan alasan pembatalan lebih detail"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i> Batalkan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-left: 1.5rem;
        border-left: 2px solid #dee2e6;
    }
    .timeline-item:last-child {
        border-left: 2px solid transparent;
    }
    .timeline-item.active {
        border-left-color: #0d6efd;
    }
    .timeline-item.active .timeline-marker {
        background: #0d6efd;
        border-color: #0d6efd;
    }
    .timeline-marker {
        position: absolute;
        left: -8px;
        top: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        background: #fff;
    }
    .timeline-content {
        padding-left: 0.5rem;
    }
</style>
@endpush

@php
function getStatusColor($status) {
    switch($status) {
        case 'paid': return 'success';
        case 'processing': return 'info';
        case 'shipped': return 'primary';
        case 'waiting_payment': return 'warning';
        case 'cancelled': return 'danger';
        case 'refunded': return 'dark';
        default: return 'secondary';
    }
}
@endphp

@if($order->status == 'pending' && $order->payment_expiry)
@push('scripts')
<script>
// Countdown timer
function updatePaymentCountdown() {
    const expiryDate = new Date("{{ $order->payment_expiry->format('Y-m-d H:i:s') }}").getTime();
    const now = new Date().getTime();
    const distance = expiryDate - now;
    
    if (distance < 0) {
        document.getElementById("payment-countdown").innerHTML = "Waktu pembayaran telah habis";
        return;
    }
    
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    document.getElementById("payment-countdown").innerHTML = 
        hours + " jam " + minutes + " menit " + seconds + " detik";
}

// Update setiap 1 detik
setInterval(updatePaymentCountdown, 1000);
updatePaymentCountdown(); // Jalankan segera
</script>
@endpush
@endif