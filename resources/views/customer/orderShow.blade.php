@extends('customer.template')
@php
    // Tambahkan di bagian @php di blade
    function formatPaymentExpiry($expiry)
    {
        if (!$expiry) {
            return '-';
        }

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
                {{ $order->courier == 'self_pickup' && $order->status == 'shipped' ? 'Siap Di Pickup' : $statuses[$order->status] ?? $order->status }}
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
                                    <img src="{{ asset($item->products->image) }}" alt="{{ $item->products->name }}"
                                        class="img-fluid rounded" style="max-height: 80px; width: auto;">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">{{ $item->products->name }}</h6>
                                    <p class="mb-1 text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    <p class="mb-0">Jumlah: {{ $item->quantity }}</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-0 fw-bold">Rp
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mt-3">
                            <div class="col-6">
                                <p>Subtotal Produk</p>
                            </div>
                            <div class="col-6 text-end">
                                <p>Rp
                                    {{ number_format($order->items->sum(function ($item) {return $item->price * $item->quantity;}),0,',','.') }}
                                </p>
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
                        @if ($order->status == 'waiting_payment')
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
                            @if ($order->payment_method)
                                {{ $order->payment_method }}
                                @if ($order->payment_code)
                                    <span class="badge bg-light text-dark ms-2">{{ $order->payment_code }}</span>
                                @endif
                            @else
                                Belum dipilih
                            @endif
                        </p>

                        @if ($order->status == 'pending' && $order->payment_code)
                            <div class="payment-instruction mt-4">
                                <h6 class="text-primary">Instruksi Pembayaran</h6>

                                @if (str_contains(strtolower($order->payment_method), 'virtual akun') ||
                                        str_contains(strtolower($order->payment_method), 'bank transfer'))
                                    <ol class="list-group list-group-numbered">
                                        <li class="list-group-item border-0 p-1 ps-4">Masuk ke aplikasi mobile banking atau
                                            ATM bank {{ str_replace('VA ', '', $order->payment_method) }}</li>
                                        <li class="list-group-item border-0 p-1 ps-4">Pilih menu
                                            <strong>Transfer/Pembayaran</strong>
                                        </li>
                                        <li class="list-group-item border-0 p-1 ps-4">Masukkan nomor Virtual Account:
                                            <strong>{{ $order->payment_code }}</strong>
                                        </li>
                                        <li class="list-group-item border-0 p-1 ps-4">Masukkan jumlah pembayaran: <strong>Rp
                                                {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                                        <li class="list-group-item border-0 p-1 ps-4">Ikuti instruksi selanjutnya untuk
                                            menyelesaikan pembayaran</li>
                                    </ol>
                                @elseif(str_contains(strtolower($order->payment_method), 'credit card'))
                                    <p>Silakan selesaikan pembayaran kartu kredit Anda melalui halaman pembayaran Midtrans.
                                    </p>
                                @endif

                                <div class="alert alert-warning mt-3 small">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Pesanan akan otomatis dibatalkan jika tidak dibayar dalam waktu 24 jam.
                                    <br />
                                    <p class="mb-2">
                                        Selesaikan pembayaran dalam:
                                        <strong id="payment-countdown">
                                            {{ $order->created_at->addHours(24)->diffForHumans() }}
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if ($order->payment_date)
                            <div class="alert alert-success mt-3">
                                <h6 class="alert-heading">Pembayaran Berhasil</h6>
                                <p class="mb-1">Metode: {{ $order->payment_method }}</p>
                                <p class="mb-1">Kode: {{ $order->payment_code ?? '-' }}</p>
                                <p class="mb-0">Waktu:
                                    {{ \Carbon\Carbon::parse($order->payment_date)->format('d M Y H:i') }}</p>
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
                                    <p class="text-muted small">
                                        {{ $order->waiting_payment_at != null ? $order->waiting_payment_at->format('d M Y H:i') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item {{ $order->status == 'processing' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Pesanan Diproses</h6>
                                    @if ($order->order_processed_at)
                                        <p class="text-muted small">{{ $order->order_processed_at->format('d M Y H:i') }}
                                        </p>
                                    @else
                                        <p class="text-muted small">-
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="timeline-item {{ $order->status == 'shipped' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Pesanan Dikirim</h6>
                                    @if ($order->order_sent_at)
                                        <p class="text-muted small">{{ $order->order_sent_at->format('d M Y H:i') }}</p>
                                    @else
                                        <p class="text-muted small">-
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="timeline-item {{ $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Pesanan Selesai</h6>
                                    @if ($order->completed_at)
                                        <p class="text-muted small">{{ $order->completed_at->format('d M Y H:i') }}</p>
                                    @else
                                        <p class="text-muted small">-
                                        </p>
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

                        @if ($order->courier != 'self_pickup')
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
                        @else
                            <h6>Metode Pengambilan</h6>
                            <p class="mb-3">Ambil ditempat</p>
                            <h6>Alamat Pengambilan</h6>
                            <p class="mb-3"><a href="https://maps.app.goo.gl/NzkN37NgnJVb1NzE7" target="_blank">Jl.
                                    Bubutan No.101A</a>
                            </p>
                        @endif

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
                        @if ($order->status == 'shipped')
                            <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#confirmDeliveryModal">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ $order->courier == 'self_pickup' ? 'Konfirmasi Pickup' : 'Konfirmasi Diterima' }}
                            </button>
                        @endif

                        @if (
                            $order->status == 'shipped' &&
                                $order->courier != 'self_pickup' &&
                                $order->order_sent_at != null &&
                                $order->order_sent_at->diffInDays(now()) >= 1)
                            <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#returnRequestModal">
                                <i class="fas fa-undo me-2"></i> Ajukan Retur
                            </button>
                        @endif

                        @if (in_array($order->status, ['waiting_payment', 'processing']))
                            <button class="btn btn-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#cancelOrderModal">
                                <i class="fas fa-times-circle me-2"></i> Batalkan Pesanan
                            </button>
                        @endif
                        @if (in_array($order->status, ['waiting_return']))
                            <button class="btn btn-success w-100" data-bs-toggle="modal"
                                data-bs-target="#modalResiPengiriman">
                                <i class="fas fa-check-circle me-2"></i> Konfirmasi Resi
                            </button>
                        @endif


                        @if ($order->status == 'completed')
                            @php
                                $hasReviewed = $order->hasRating();
                            @endphp
                            @if (!$hasReviewed)
                                <button class="btn btn-warning w-100 mt-2" data-bs-toggle="modal"
                                    data-bs-target="#confirmRatingModal" data-order-id="{{ $order->order_id }}">
                                    <i class="fas fa-star me-2"></i>Review
                                </button>
                            @endif
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Ajukan Pengembalian Dana</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="refundRequestForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                    <div class="modal-body">
                        <!-- Product Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Produk yang Dikembalikan</label>
                            @foreach ($order->items as $item)
                                <div class="card mb-2">
                                    <div class="card-body p-3">
                                        <div class="form-check">
                                            <input class="form-check-input refund-item" type="checkbox"
                                                name="items[{{ $item->id }}][selected]" value="1"
                                                id="item-{{ $item->id }}" data-item-id="{{ $item->id }}"
                                                onchange="toggleRefundFields(this)">
                                            <label class="form-check-label d-flex align-items-center"
                                                for="item-{{ $item->id }}">
                                                <img src="{{ asset($item->products->image) }}" class="img-thumbnail me-3"
                                                    width="60" alt="{{ $item->products->name }}">
                                                <div>
                                                    <h6 class="mb-1">{{ $item->products->name }}</h6>
                                                    <div class="text-muted small">
                                                        Qty: {{ $item->quantity }} |
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Refund Details (Hidden by default) -->
                                        <div id="refund-fields-{{ $item->id }}" class="mt-3 collapse">
                                            <div class="border-top pt-3">
                                                <!-- Quantity -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Jumlah yang Dikembalikan</label>
                                                        <select class="form-select"
                                                            name="items[{{ $item->id }}][quantity]">
                                                            @for ($i = 1; $i <= $item->quantity; $i++)
                                                                <option value="{{ $i }}">{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Kondisi Produk</label>
                                                        <select class="form-select"
                                                            name="items[{{ $item->id }}][condition]">
                                                            <option value="new">Masih Baru (Segel Utuh)</option>
                                                            <option value="opened">Sudah Dibuka</option>
                                                            <option value="damaged">Rusak</option>
                                                            <option value="defective">Cacat Produksi</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Evidence Upload -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Upload Bukti Produk</label>
                                                    <small class="text-muted d-block mb-2">
                                                        Wajib upload minimal 1 foto dan 1 video kondisi produk (maks. 5MB
                                                        per file)
                                                    </small>

                                                    <!-- Photo Upload -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Foto Produk (Min. 1 foto)</label>
                                                        <input type="file" name="items[{{ $item->id }}][photos][]"
                                                            class="form-control photo-upload" accept="image/*" multiple
                                                            data-item-id="{{ $item->id }}"
                                                            onchange="previewRefundFiles(this, 'photo')">
                                                        <div class="invalid-feedback">Harus upload minimal 1 foto</div>
                                                    </div>

                                                    <!-- Video Upload -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Video Produk (Min. 1 video)</label>
                                                        <input type="file" name="items[{{ $item->id }}][videos][]"
                                                            class="form-control video-upload" accept="video/*" multiple
                                                            data-item-id="{{ $item->id }}"
                                                            onchange="previewRefundFiles(this, 'video')">
                                                        <div class="invalid-feedback">Harus upload minimal 1 video</div>
                                                    </div>

                                                    <!-- Preview Area -->
                                                    <div class="row g-2" id="preview-container-{{ $item->id }}">
                                                    </div>
                                                </div>

                                                <!-- Reason -->
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Pengembalian</label>
                                                    <textarea class="form-control" name="items[{{ $item->id }}][reason]" rows="2"
                                                        placeholder="Jelaskan alasan pengembalian produk ini..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Refund Method -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Metode Pengembalian Dana</label>
                            @if (str_contains(strtolower($order->payment_method), 'virtual akun') ||
                                    str_contains(strtolower($order->payment_method), 'bank transfer'))
                                <div class="alert alert-info">
                                    Karena Anda membayar menggunakan {{ strtoupper($order->payment_method) }}, dana akan
                                    dikembalikan ke rekening bank Anda.
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bank Tujuan</label>
                                        <select class="form-select" name="bank_name" id="bankSelect">
                                            <option value="">Pilih Bank</option>
                                            <option value="BCA">BCA</option>
                                            <option value="Mandiri">Mandiri</option>
                                            <option value="BRI">BRI</option>
                                            <option value="BNI">BNI</option>
                                            <option value="CIMB">CIMB</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nomor Rekening</label>
                                        <input type="text" class="form-control" name="bank_account"
                                            placeholder="Contoh: 1234567890">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control" name="account_name"
                                            placeholder="Nama sesuai rekening">
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Dana akan dikembalikan ke Kartu Kredit atau melalui metode lain
                                    yang akan kami infokan.
                                </div>
                                <input type="hidden" name="bank_name" value="">
                                <input type="hidden" name="bank_account" value="">
                                <input type="hidden" name="account_name" value="">
                            @endif
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Tambahan</label>
                            <textarea class="form-control" name="note" rows="3"
                                placeholder="Tambahkan catatan lain jika diperlukan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white" id="submitRefundBtn">
                            <i class="fas fa-paper-plane me-1"></i> Ajukan Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Delivery Modal -->
    <div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Konfirmasi Pesanan Diterima</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('orders.confirm-pickup-done', $order->order_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Silakan konfirmasi bahwa Anda telah menerima pesanan ini. Setelah dikonfirmasi, pesanan akan
                            ditandai sebagai selesai dan tidak dapat dibatalkan.
                        </div>

                        <div class="mb-3">
                            <label for="deliveryNotes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="deliveryNotes" name="notes" rows="3"
                                placeholder="Masukkan catatan tambahan jika ada"></textarea>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                            <label for="cancelReason" class="form-label">Alasan Pembatalan <span
                                    class="text-danger">*</span></label>
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
                            <textarea class="form-control" id="cancelNotes" name="notes" rows="3"
                                placeholder="Jelaskan alasan pembatalan lebih detail"></textarea>
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

    <div class="modal fade" id="confirmRatingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Berikan Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deliveryRatingForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="selected_order_id">
                        <div id="products-rating-list"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim Penilaian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalResiPengiriman" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formResiPengiriman">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalResiLabel">Konfirmasi Resi Pengiriman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputResi" class="form-label">Nomor Resi</label>
                            <input type="text" class="form-control" id="inputResi" name="resi" required>
                            <input type="hidden" id="inputOrderId" name="order_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .file-upload-wrapper {
            position: relative;
        }

        .refund-preview {
            max-width: 100%;
            max-height: 120px;
            border-radius: 4px;
            object-fit: cover;
        }

        .video-preview-wrapper {
            position: relative;
            background: #000;
            border-radius: 4px;
            overflow: hidden;
        }

        .video-preview {
            width: 100%;
            max-height: 120px;
        }

        .preview-item {
            position: relative;
            margin-bottom: 10px;
        }

        .remove-preview {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .preview-badge {
            position: absolute;
            bottom: 5px;
            left: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

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

        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: start;
            gap: 8px;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            font-size: 2rem;
            color: #e4e4e4;
            cursor: pointer;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .rating-stars label:hover {
            transform: scale(1.2);
            color: #ffd700;
        }

        .rating-stars input:checked~label,
        .rating-stars label:hover,
        .rating-stars label:hover~label {
            color: #ffd700;
            animation: starBounce 0.5s ease;
        }

        @keyframes starBounce {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1.1);
            }
        }

        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .preview-item img,
        .preview-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-item .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 24px;
            height: 24px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .file-type-icon {
            font-size: 2rem;
            color: #6c757d;
        }
    </style>
@endsection

@php
    function getStatusColor($status)
    {
        switch ($status) {
            case 'completed':
                return 'success';
            case 'processing':
                return 'info';
            case 'shipped':
                return 'primary';
            case 'waiting_payment':
                return 'warning';
            case 'cancelled':
                return 'danger';
            case 'refunded':
                return 'dark';
            default:
                return 'secondary';
        }
    }
@endphp

@if ($order->status == 'pending' && $order->payment_expiry)
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

@section('scripts')
    <script>
        // Toggle refund fields when product is selected
        function toggleRefundFields(checkbox) {
            const itemId = checkbox.getAttribute('data-item-id');
            const refundFields = document.getElementById(`refund-fields-${itemId}`);

            if (checkbox.checked) {
                $(refundFields).collapse('show');
            } else {
                $(refundFields).collapse('hide');
                // Clear previews when unchecked
                document.getElementById(`preview-container-${itemId}`).innerHTML = '';
            }
        }

        // Preview uploaded files
        function previewRefundFiles(input, type) {
            const itemId = input.getAttribute('data-item-id');
            const previewContainer = document.getElementById(`preview-container-${itemId}`);

            // Clear existing previews for this type
            const existingPreviews = previewContainer.querySelectorAll(`.preview-${type}`);
            existingPreviews.forEach(preview => preview.remove());

            if (input.files) {
                // Validate file size first
                let hasInvalidFile = false;
                for (let i = 0; i < input.files.length; i++) {
                    if (input.files[i].size > 5 * 1024 * 1024) {
                        hasInvalidFile = true;
                        break;
                    }
                }

                if (hasInvalidFile) {
                    input.classList.add('is-invalid');
                    showToast('error', 'Ukuran file maksimal 5MB');
                    return;
                } else {
                    input.classList.remove('is-invalid');
                }

                // Process each file
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const previewId = `preview-${type}-${itemId}-${Date.now()}-${i}`;
                        let previewHtml = '';

                        if (type === 'photo') {
                            previewHtml = `
                            <div class="col-6 col-md-4 col-lg-3 preview-item preview-${type}" id="${previewId}">
                                <img src="${e.target.result}" class="refund-preview w-100 h-100">
                                <span class="preview-badge">Foto ${i+1}</span>
                                <span class="remove-preview" onclick="removeRefundPreview('${previewId}', '${itemId}', '${type}')">
                                    <i class="fas fa-times"></i>
                                </span>
                            </div>
                        `;
                        } else if (type === 'video') {
                            previewHtml = `
                            <div class="col-12 col-md-6 preview-item preview-${type}" id="${previewId}">
                                <div class="video-preview-wrapper">
                                    <video controls class="video-preview">
                                        <source src="${e.target.result}" type="${file.type}">
                                    </video>
                                    <span class="preview-badge">Video ${i+1}</span>
                                    <span class="remove-preview" onclick="removeRefundPreview('${previewId}', '${itemId}', '${type}')">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </div>
                            </div>
                        `;
                        }

                        previewContainer.insertAdjacentHTML('beforeend', previewHtml);
                    };

                    reader.readAsDataURL(file);
                }
            }
        }

        // Remove preview and clear file input
        function removeRefundPreview(previewId, itemId, type) {
            document.getElementById(previewId).remove();

            // Clear the corresponding file input
            const input = document.querySelector(`.${type}-upload[data-item-id="${itemId}"]`);
            if (input) {
                input.value = '';
                input.classList.remove('is-invalid');
            }
        }

        // Form submission with AJAX
        document.getElementById('refundRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitRefundBtn');

            // Validate at least one product selected
            const checkedItems = document.querySelectorAll('.refund-item:checked');
            if (checkedItems.length === 0) {
                showToast('error', 'Pilih minimal 1 produk untuk dikembalikan');
                return;
            }

            // Validate each selected product has required files
            let isValid = true;
            checkedItems.forEach(item => {
                const itemId = item.getAttribute('data-item-id');
                const photoInput = document.querySelector(`.photo-upload[data-item-id="${itemId}"]`);
                const videoInput = document.querySelector(`.video-upload[data-item-id="${itemId}"]`);

                if (!photoInput.files || photoInput.files.length === 0) {
                    photoInput.classList.add('is-invalid');
                    isValid = false;
                }

                if (!videoInput.files || videoInput.files.length === 0) {
                    videoInput.classList.add('is-invalid');
                    isValid = false;
                }
            });

            // Validate bank details if payment method requires it
            if (['virtual account', 'bank transfer'].some(method => '{{ strtolower($order->payment_method) }}'
                    .includes(method))) {
                const bankSelect = document.getElementById('bankSelect');
                const bankAccount = formData.get('bank_account');
                const accountName = formData.get('account_name');

                if (!bankSelect.value || !bankAccount || !accountName) {
                    showToast('error', 'Harap lengkapi detail rekening bank untuk pengembalian dana');
                    isValid = false;
                }
            }

            if (!isValid) {
                showToast('error', 'Harap lengkapi semua data yang diperlukan');
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengirim...';

            // AJAX request
            fetch('{{ route('orders.refund', $order->order_id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        $('#refundRequestModal').modal('hide');
                        // Refresh page or update UI as needed
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showToast('error', data.message || 'Terjadi kesalahan');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Ajukan Pengembalian';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Terjadi kesalahan saat mengirim permintaan');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Ajukan Pengembalian';
                });
        });

        // Helper function to show toast notifications
        function showToast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }
        $(document).ready(function() {
            $('#confirmRatingModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var orderId = button.data('order-id');
                $('#selected_order_id').val(orderId);
                // Find the order's products
                let order = @json($order);

                let html = '';
                if (order && order.items) {
                    order.items.forEach(function(item, idx) {
                        html += `
                <div class="mb-4 border-bottom pb-3">
                    <div class="d-flex align-items-center mb-2">
                        <img src="/${item.products.image ?? 'images/default-product.png'}" width="50" class="me-2 rounded">
                        <strong>${item.products.name}</strong>
                    </div>
                    <label>Rating (1-5):</label>
                    <div class="rating-stars mb-2">
                        ${[5,4,3,2,1].map(i => `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <input type="radio" id="star${i}_${idx}" name="ratings[${item.product_id}][rating]" value="${i}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label for="star${i}_${idx}"><i class="fas fa-star"></i></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `).join('')}
                    </div>
                    <label>Ulasan:</label>
                    <textarea name="ratings[${item.product_id}][review]" class="form-control mb-2" rows="2"></textarea>
                    <label>Upload Foto/Video:</label>
                    <input type="file" name="ratings[${item.product_id}][media][]" class="form-control mb-2 media-upload-input" 
                        data-preview-container="previewContainer_${item.product_id}" multiple accept="image/*,video/*">
                    <div class="preview-container mt-3 row g-2" id="previewContainer_${item.product_id}"></div>
                </div>
                `;
                    });
                }
                $('#products-rating-list').html(html);

                // Attach preview logic to each file input
                $('.media-upload-input').each(function() {
                    $(this).off('change').on('change', function(e) {
                        const containerId = $(this).data('preview-container');
                        const container = document.getElementById(containerId);
                        const maxFiles = 5;
                        const maxVideos = 1;
                        const files = Array.from(this.files);
                        let videoCount = 0;

                        // Reset preview
                        container.innerHTML = '';

                        // Count existing videos
                        files.forEach(file => {
                            if (file.type.startsWith('video/')) videoCount++;
                        });

                        // Validation
                        if (files.length > maxFiles) {
                            alert(`Maksimal ${maxFiles} file diperbolehkan`);
                            this.value = '';
                            return;
                        }

                        if (videoCount > maxVideos) {
                            alert(`Maksimal ${maxVideos} video diperbolehkan`);
                            this.value = '';
                            return;
                        }

                        // Create previews
                        files.forEach((file, index) => {
                            const reader = new FileReader();
                            const previewItem = document.createElement('div');
                            previewItem.className = 'col-auto preview-item';

                            if (file.type.startsWith('image/')) {
                                reader.onload = (e) => {
                                    previewItem.innerHTML = `
                                        <img src="${e.target.result}" alt="Preview">
                                        <div class="file-info">${formatFileSize(file.size)}</div>
                                    `;
                                }
                                reader.readAsDataURL(file);
                            } else if (file.type.startsWith('video/')) {
                                previewItem.innerHTML = `
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                        <i class="fas fa-file-video file-type-icon"></i>
                                        <small class="text-muted mt-1">${file.name}</small>
                                        <div class="file-info">${formatFileSize(file.size)}</div>
                                    </div>
                                `;
                            }

                            container.appendChild(previewItem);
                        });
                    });
                });
            });

            $('#confirmDeliveryModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var orderId = button.data('order-id');
                $('#selected_order_id').val(orderId);
                // Find the order's products
                let order = '{{ $order }}';
                let html = '';
                if (order && order.items) {
                    order.items.forEach(function(item, idx) {
                        html += `
                <div class="mb-4 border-bottom pb-3">
                    <div class="d-flex align-items-center mb-2">
                        <img src="/${item.products.image ?? 'images/default-product.png'}" width="50" class="me-2 rounded">
                        <strong>${item.products.name}</strong>
                    </div>
                    <label>Rating (1-5):</label>
                    <div class="rating-stars mb-2">
                        ${[5,4,3,2,1].map(i => `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <input type="radio" id="star${i}_${idx}" name="ratings[${item.product_id}][rating]" value="${i}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label for="star${i}_${idx}"><i class="fas fa-star"></i></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `).join('')}
                    </div>
                    <label>Ulasan:</label>
                    <textarea name="ratings[${item.product_id}][review]" class="form-control mb-2" rows="2"></textarea>
                    <label>Upload Foto/Video:</label>
                    <input type="file" name="ratings[${item.product_id}][media][]" class="form-control mb-2 media-upload-input" 
                        data-preview-container="previewContainer_${item.product_id}" multiple accept="image/*,video/*">
                    <div class="preview-container mt-3 row g-2" id="previewContainer_${item.product_id}"></div>
                </div>
                `;
                    });
                }
                $('#products-rating-list').html(html);

                // Attach preview logic to each file input
                $('.media-upload-input').each(function() {
                    $(this).off('change').on('change', function(e) {
                        const containerId = $(this).data('preview-container');
                        const container = document.getElementById(containerId);
                        const maxFiles = 5;
                        const maxVideos = 1;
                        const files = Array.from(this.files);
                        let videoCount = 0;

                        // Reset preview
                        container.innerHTML = '';

                        // Count existing videos
                        files.forEach(file => {
                            if (file.type.startsWith('video/')) videoCount++;
                        });

                        // Validation
                        if (files.length > maxFiles) {
                            alert(`Maksimal ${maxFiles} file diperbolehkan`);
                            this.value = '';
                            return;
                        }

                        if (videoCount > maxVideos) {
                            alert(`Maksimal ${maxVideos} video diperbolehkan`);
                            this.value = '';
                            return;
                        }

                        // Create previews
                        files.forEach((file, index) => {
                            const reader = new FileReader();
                            const previewItem = document.createElement('div');
                            previewItem.className = 'col-auto preview-item';

                            if (file.type.startsWith('image/')) {
                                reader.onload = (e) => {
                                    previewItem.innerHTML = `
                                        <img src="${e.target.result}" alt="Preview">
                                        <div class="file-info">${formatFileSize(file.size)}</div>
                                    `;
                                }
                                reader.readAsDataURL(file);
                            } else if (file.type.startsWith('video/')) {
                                previewItem.innerHTML = `
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                        <i class="fas fa-file-video file-type-icon"></i>
                                        <small class="text-muted mt-1">${file.name}</small>
                                        <div class="file-info">${formatFileSize(file.size)}</div>
                                    </div>
                                `;
                            }

                            container.appendChild(previewItem);
                        });
                    });
                });
            });

            $('#deliveryRatingForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let orderId = $('#selected_order_id').val();

                // Add CSRF token to the FormData
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '/orders/' + orderId + '/confirm-delivery',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#confirmDeliveryModal').modal('hide');
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengirim penilaian',
                        })
                    }
                });
            });
        })

        $('#modalResiPengiriman').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var orderId = button.data('order-id');
            $('#selected_order_id').val(orderId);
            alert('a');

        });
        $('#formResiPengiriman').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let orderId = $('#selected_order_id').val();

            // Add CSRF token to the FormData
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '/orders/' + orderId + '/update-resi',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#modalResiPengiriman').modal('hide');
                    Swal.fire({
                        title: 'Berhasil',
                        text: response.message,
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat mengirim penilaian',
                    })
                }
            });
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endsection
