@extends('layouts.admin')


@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">Manajemen Pesanan</h5>
                        <div class="d-flex">
                            <div class="input-group me-3" style="width: 250px;">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="form-control date-range-picker" placeholder="Filter tanggal">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <li>
                                        <h6 class="dropdown-header">Status Pesanan</h6>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}">Semua Status</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    @foreach (['waiting_payment', 'waiting_confirmation', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                        <li>
                                            <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                href="{{ request()->fullUrlWithQuery(['status' => $status]) }}">
                                                <span>
                                                    @switch($status)
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
                                                @if (request('status') == $status)
                                                    <i class="fas fa-check text-primary"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="120">ID Order</th>
                                        <th>Customer</th>
                                        <th width="150">Tanggal</th>
                                        <th width="120">Total</th>
                                        <th width="150">Status</th>
                                        <th width="120" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr id="order-row-{{ $order->order_id }}"
                                            class="@if ($order->is_urgent) table-warning @endif">
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->order_id) }}"
                                                    class="fw-bold text-primary">
                                                    #{{ $order->order_id }}
                                                </a>
                                                @if ($order->is_urgent)
                                                    <span class="badge bg-danger ms-1">Penting</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="{{ $order->user->avatar ?? asset('https://mastertondental.co.nz/wp-content/uploads/2022/12/team-profile-placeholder.jpg') }}"
                                                            class="rounded-circle" width="40" height="40"
                                                            alt="Avatar">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0">{{ $order->user->name ?? 'N/A' }}</h6>
                                                        <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                                        @if ($order->user->phone)
                                                            <small class="d-block text-muted"><i
                                                                    class="fas fa-phone-alt me-1"></i>
                                                                {{ $order->user->phone }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ $order->created_at->format('d M Y') }}</small><br>
                                                <small>{{ $order->created_at->format('H:i') }}</small>
                                            </td>
                                            <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill bg-@switch($order->status)
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
                                                        @case('waiting_payment')
                                                            <i class="fas fa-clock me-1"></i> Menunggu Pembayaran
                                                        @break

                                                        @case('waiting_confirmation')
                                                            <i class="fas fa-hourglass-half me-1"></i> Menunggu Konfirmasi
                                                        @break

                                                        @case('processing')
                                                            <i class="fas fa-cog me-1"></i> Diproses
                                                        @break

                                                        @case('shipped')
                                                            <i class="fas fa-truck me-1"></i>
                                                            @if ($order->courier != 'self_pickup')
                                                                Dikirim
                                                            @else
                                                                Dapat dipickup
                                                            @endif
                                                        @break

                                                        @case('completed')
                                                            <i class="fas fa-check-circle me-1"></i> Selesai
                                                        @break

                                                        @case('cancelled')
                                                            <i class="fas fa-times-circle me-1"></i> Dibatalkan
                                                        @break

                                                        @case('partially_refunded')
                                                            <i class="fas fa-exchange-alt me-1"></i> Pengembalian Sebagian
                                                        @break

                                                        @case('refunded')
                                                            <i class="fas fa-undo me-1"></i> Dikembalikan
                                                        @break

                                                        @default
                                                            <i class="fas fa-info-circle me-1"></i> {{ ucfirst($order->status) }}
                                                    @endswitch
                                                </span>
                                                @if ($order->payment_method)
                                                    <small class="d-block mt-1 text-muted">
                                                        <i class="fas fa-credit-card me-1"></i>
                                                        {{ $order->payment_method }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" id="actionDropdown{{ $order->order_id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="actionDropdown{{ $order->order_id }}">
                                                        <li>
                                                            <button class="dropdown-item view-detail-btn"
                                                                data-id="{{ $order->order_id }}">
                                                                <i class="fas fa-eye me-2"></i> Detail
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.orders.invoice', $order->order_id) }}"
                                                                target="_blank">
                                                                <i class="fas fa-file-invoice me-2"></i> Invoice
                                                            </a>
                                                        </li>
                                                        @if ($order->status == 'waiting_confirmation')
                                                            <li>
                                                                <button class="dropdown-item confirm-btn"
                                                                    data-id="{{ $order->order_id }}">
                                                                    <i class="fas fa-check-circle me-2"></i> Konfirmasi
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if ($order->status == 'processing')
                                                            <li>
                                                                @if ($order->courier != 'self_pickup')
                                                                    <button class="dropdown-item shipping-btn"
                                                                        data-id="{{ $order->order_id }}">
                                                                        <i class="fas fa-truck me-2"></i> Input Resi
                                                                    </button>
                                                                @else
                                                                    <button class="dropdown-item pickup-btn"
                                                                        data-id="{{ $order->order_id }}">
                                                                        <i class="fas fa-truck me-2"></i> Konfirmasi Pickup
                                                                    </button>
                                                                @endif
                                                            </li>
                                                        @endif
                                                        @if (in_array($order->status, ['shipped']) && $order->courier == 'self_pickup')
                                                            <li>
                                                                <button class="dropdown-item complete-btn"
                                                                    data-id="{{ $order->order_id }}">
                                                                    <i class="fas fa-check me-2"></i> Sudah dipickup
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if (in_array($order->status, ['waiting_payment', 'waiting_confirmation', 'processing']))
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger cancel-btn"
                                                                    data-id="{{ $order->order_id }}">
                                                                    <i class="fas fa-times-circle me-2"></i> Batalkan
                                                                    Pesanan
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                                                        <h5 class="text-muted">Tidak ada pesanan ditemukan</h5>
                                                        <small class="text-muted">Coba gunakan filter berbeda</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                                    {{ $orders->total() }} pesanan
                                </div>
                                <div>
                                    {{ $orders->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Order Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> Konfirmasi Pesanan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p>Apakah Anda yakin ingin mengkonfirmasi pesanan ini?</p>
                        <p class="small text-muted">Pesanan akan diproses dan status akan berubah menjadi "Diproses".</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="confirmOrderBtn" class="btn btn-primary">
                            <i class="fas fa-check-circle me-1"></i> Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Pickup Order Modal -->
        <div class="modal fade" id="confirmPickupModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> Konfirmasi Pickup Pesanan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p>Apakah Anda yakin ingin mengkonfirmasi pesanan ini bisa di pickup?</p>
                        <p class="small text-muted">Pesanan akan diproses dan status akan berubah menjadi "Dapat di pickup".
                        </p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="confirmOrderBtn" class="btn btn-primary">
                            <i class="fas fa-check-circle me-1"></i> Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Modal -->
        <div class="modal fade" id="shippingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-truck me-2"></i> Input Resi Pengiriman</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="shippingForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body py-4">
                            <div class="mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Informasi Pengiriman:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Kurir:</strong></p>
                                                <p id="courierInfo" class="text-primary">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Layanan:</strong></p>
                                                <p id="serviceInfo" class="text-primary">-</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="shipping_number" class="form-label">Nomor Resi</label>
                                <input type="text" class="form-control" id="shipping_number" name="shipping_number"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-save me-1"></i> Simpan Resi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cancel Order Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i> Batalkan Pesanan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="cancelForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body py-4">
                            <div class="mb-3">
                                <label for="cancel_reason" class="form-label">Alasan Pembatalan</label>
                                <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times-circle me-1"></i> Batalkan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Success Toast Notification -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="successToast" class="toast align-items-center text-white bg-success" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="successMessage">Pesanan berhasil dikonfirmasi!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Error Toast Notification -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="errorToast" class="toast align-items-center text-white bg-danger" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="errorMessage">Terjadi kesalahan saat mengkonfirmasi pesanan!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Order Detail Modal -->
        <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="orderDetailModalLabel">Detail Pesanan #<span id="modalOrderId"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="orderDetailContent">
                        <!-- Konten akan diisi via AJAX -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat detail pesanan...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('admin.orders.invoice', $order->order_id) }}" type="button" target="_blank"
                            class="btn btn-primary">
                            <i class="fas fa-print me-1"></i> Cetak Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                // Initialize toasts
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                // Pastikan daterangepicker tersedia
                if (typeof $.fn.daterangepicker === 'function') {
                    $('.date-range-picker').daterangepicker({
                        opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                            format: 'DD/MM/YYYY',
                            applyLabel: 'Terapkan',
                            cancelLabel: 'Batal',
                            fromLabel: 'Dari',
                            toLabel: 'Sampai',
                            customRangeLabel: 'Custom',
                            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                            ],
                            firstDay: 1
                        }
                    });

                    $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                            'DD/MM/YYYY'));

                        // Kirim filter ke server
                        const dates = {
                            start_date: picker.startDate.format('YYYY-MM-DD'),
                            end_date: picker.endDate.format('YYYY-MM-DD')
                        };

                        // Reload table dengan parameter filter
                        reloadOrdersTable(dates);
                    });

                    $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');

                        // Hapus filter tanggal
                        reloadOrdersTable({
                            clear_date_filter: true
                        });
                    });
                } else {
                    console.error('DateRangePicker not loaded!');
                    // Fallback: tampilkan input biasa
                    $('.date-range-picker').attr('type', 'text');
                }

                function reloadOrdersTable(params = {}) {
                    // Gabungkan dengan filter yang ada
                    const queryParams = new URLSearchParams(window.location.search);

                    // Tambahkan parameter baru
                    for (const key in params) {
                        if (params[key] !== undefined) {
                            queryParams.set(key, params[key]);
                        }
                    }

                    // Hapus parameter jika clear filter
                    if (params.clear_date_filter) {
                        queryParams.delete('start_date');
                        queryParams.delete('end_date');
                    }

                    // Reload halaman dengan parameter baru
                    window.location.search = queryParams.toString();

                    // Atau gunakan AJAX untuk update table saja:
                    // $.get(window.location.pathname + '?' + queryParams.toString(), function(data) {
                    //     $('#ordersTable').html($(data).find('#ordersTable').html());
                    // });
                }

                const orderDetailModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
                let currentOrderId = null;

                // Handle view detail button click
                $(document).on('click', '.view-detail-btn', function() {
                    currentOrderId = $(this).data('id');
                    $('#modalOrderId').text(currentOrderId);

                    // Load order details via AJAX
                    $.ajax({
                        url: `/admin/orders/${currentOrderId}`,
                        type: 'GET',
                        beforeSend: function() {
                            $('#orderDetailContent').html(`
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat detail pesanan...</p>
                        </div>
                    `);
                        },
                        success: function(response) {
                            $('#orderDetailContent').html(response);
                        },
                        error: function(xhr) {
                            $('#orderDetailContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Gagal memuat detail pesanan. Silakan coba lagi.
                        </div>
                    `);
                        }
                    });

                    orderDetailModal.show();
                });

                // Print button handler
                $('#printOrderBtn').click(function() {
                    if (currentOrderId) {
                        window.open(`/admin/orders/${currentOrderId}/invoice`, '_blank');
                    }
                });


                // Confirm order flow
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

                $('.confirm-btn').click(function() {
                    currentOrderId = $(this).data('id');
                    confirmModal.show();
                });

                $('#confirmOrderBtn').click(function() {
                    if (!currentOrderId) return;
                    var url = "{{ route('admin.orders.confirm', ':orderId') }}";
                    url = url.replace(":orderId", currentOrderId);

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'PUT'
                        },
                        beforeSend: function() {
                            $('#confirmOrderBtn').prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'
                            );
                        },
                        success: function(response) {
                            if (response.success) {
                                const row = $('#order-row-' + currentOrderId);

                                // Update status badge
                                row.find('.badge')
                                    .removeClass('bg-secondary')
                                    .addClass('bg-primary')
                                    .html('<i class="fas fa-cog me-1"></i> Diproses');

                                // Remove confirm button from dropdown
                                row.find('.confirm-btn').closest('li').remove();

                                // Show success message
                                $('#successMessage').text(response.message);
                                successToast.show();
                            } else {
                                $('#errorMessage').text(response.message || 'Terjadi kesalahan');
                                errorToast.show();
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message ||
                                'Terjadi kesalahan server';
                            $('#errorMessage').text(errorMessage);
                            errorToast.show();
                        },
                        complete: function() {
                            $('#confirmOrderBtn').prop('disabled', false).html(
                                '<i class="fas fa-check-circle me-1"></i> Konfirmasi');
                            confirmModal.hide();
                            currentOrderId = null;
                        }
                    });
                });

                // Shipping flow
                const shippingModal = new bootstrap.Modal(document.getElementById('shippingModal'));

                $('.shipping-btn').click(function() {
                    const orderId = $(this).data('id');

                    // Fetch order shipping details
                    $.ajax({
                        url: `/admin/orders/${orderId}/shipping-info`,
                        type: 'GET',
                        success: function(response) {
                            // Update the modal with shipping information
                            $('#courierInfo').text(response.courier.toUpperCase() || '-');
                            $('#serviceInfo').text(response.service || '-');
                            $('#estimatedDeliveryInfo').text(response.estimated_delivery || '-');

                            // Update form action URL
                            $('#shippingForm').attr('action', `/admin/orders/${orderId}/ship`);

                            // Show the modal
                            $('#shippingModal').modal('show');
                        },
                        error: function(xhr) {
                            // Handle error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memuat informasi pengiriman'
                            });
                        }
                    });
                });

                // ... existing code ...
                const confirmPickupModal = new bootstrap.Modal(document.getElementById('confirmPickupModal'));

                $('.pickup-btn').click(function() {
                    currentOrderId = $(this).data('id');
                    confirmPickupModal.show();
                });

                // Handler for confirm pickup button in modal
                $('#confirmPickupModal #confirmOrderBtn').off('click').on('click', function() {
                    if (!currentOrderId) return;
                    // Adjust the route as needed (assuming /orders/{orderid}/confirm-pickup)
                    $.ajax({
                        url: `/orders/${currentOrderId}/confirm-pickup`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            $('#confirmPickupModal #confirmOrderBtn').prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'
                            );
                        },
                        success: function(response) {
                            // Handle JSON response
                            if (response.message) {
                                // Update status badge in table
                                const row = $('#order-row-' + currentOrderId);
                                row.find('.badge')
                                    .removeClass('bg-primary')
                                    .addClass('bg-info')
                                    .html('<i class="fas fa-truck me-1"></i> Dikirim');
                                // Remove pickup button from dropdown
                                row.find('.pickup-btn').closest('li').remove();
                                // Show success toast
                                $('#successMessage').text(response.message);
                                successToast.show();
                            } else {
                                $('#errorMessage').text(response.error || 'Terjadi kesalahan');
                                errorToast.show();
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.error ||
                                'Terjadi kesalahan server';
                            $('#errorMessage').text(errorMessage);
                            errorToast.show();
                        },
                        complete: function() {
                            $('#confirmPickupModal #confirmOrderBtn').prop('disabled', false).html(
                                '<i class="fas fa-check-circle me-1"></i> Konfirmasi'
                            );
                            confirmPickupModal.hide();
                            currentOrderId = null;
                        }
                    });
                });

                $('#shippingForm').submit(function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitBtn = form.find('button[type="submit"]');
                    const orderId = url.split('/').slice(-2)[0]; // Extract order ID from URL

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: formData,
                        beforeSend: function() {
                            submitBtn.prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'
                            );
                        },
                        success: function(response) {
                            if (response.success) {
                                const row = $('#order-row-' + orderId);

                                // Update status badge
                                row.find('.badge')
                                    .removeClass('bg-primary')
                                    .addClass('bg-info')
                                    .html('<i class="fas fa-truck me-1"></i> Dikirim');

                                // Remove shipping button from dropdown
                                row.find('.shipping-btn').closest('li').remove();

                                // Show success message
                                $('#successMessage').text(response.message);
                                successToast.show();

                                // Close modal and refresh page
                                $('#shippingModal').modal('hide');

                                // Refresh the page after a short delay
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message ||
                                'Terjadi kesalahan server';
                            $('#errorMessage').text(errorMessage);
                            errorToast.show();
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html(
                                '<i class="fas fa-save me-1"></i> Simpan Resi'
                            );
                        }
                    });
                });


                // Cancel order flow
                const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));

                $('.cancel-btn').click(function() {
                    currentOrderId = $(this).data('id');
                    $('#cancelForm').attr('action', `/admin/orders/${currentOrderId}/cancel`);
                    cancelModal.show();
                });

                $('#cancelForm').submit(function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitBtn = form.find('button[type="submit"]');

                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: formData,
                        beforeSend: function() {
                            submitBtn.prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...'
                            );
                        },
                        success: function(response) {
                            if (response.success) {
                                const row = $('#order-row-' + currentOrderId);

                                // Update status badge
                                row.find('.badge')
                                    .removeClass('bg-primary bg-secondary bg-warning')
                                    .addClass('bg-danger')
                                    .html('<i class="fas fa-times-circle me-1"></i> Dibatalkan');

                                // Remove action buttons
                                row.find('.cancel-btn, .confirm-btn, .shipping-btn').closest('li')
                                    .remove();

                                // Show success message
                                $('#successMessage').text(response.message);
                                successToast.show();

                                // Close modal
                                cancelModal.hide();

                                // Refresh the page after a short delay
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message ||
                                'Terjadi kesalahan server';
                            $('#errorMessage').text(errorMessage);
                            errorToast.show();
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html(
                                '<i class="fas fa-times-circle me-1"></i> Batalkan Pesanan'
                            );
                            currentOrderId = null;
                        }
                    });
                });


                // Complete order flow
                $('.complete-btn').click(function() {
                    const orderId = $(this).data('id');

                    Swal.fire({
                        title: 'Tandai Selesai?',
                        text: "Pesanan akan ditandai sebagai selesai",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Selesaikan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/admin/orders/${orderId}/complete`,
                                type: 'PUT',
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    if (response.success) {
                                        const row = $('#order-row-' + orderId);

                                        // Update status badge
                                        row.find('.badge')
                                            .removeClass('bg-info')
                                            .addClass('bg-success')
                                            .html(
                                                '<i class="fas fa-check-circle me-1"></i> Selesai'
                                            );

                                        // Remove complete button from dropdown
                                        row.find('.complete-btn').closest('li').remove();

                                        // Show success message
                                        $('#successMessage').text(
                                            'Pesanan berhasil diselesaikan!');
                                        successToast.show();

                                        // Refresh the page after a short delay
                                        setTimeout(() => {
                                            location.reload();
                                        }, 1500);
                                    }
                                },
                                error: function(xhr) {
                                    const errorMessage = xhr.responseJSON?.message ||
                                        'Terjadi kesalahan server';
                                    $('#errorMessage').text(errorMessage);
                                    errorToast.show();
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endsection

    @section('styles')
        <style>
            .table-hover tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.02);
            }

            .badge {
                font-size: 0.8rem;
                font-weight: 500;
            }

            .dropdown-menu {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            }

            .toast {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            .table th {
                white-space: nowrap;
            }

            .card-header {
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .modal-content {
                border: none;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            .modal-header {
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .modal-footer {
                border-top: 1px solid rgba(0, 0, 0, 0.05);
            }

            #orderDetailContent {
                max-height: 70vh;
                overflow-y: auto;
            }

            /* Untuk layar kecil */
            @media (max-width: 768px) {
                #orderDetailContent {
                    max-height: 60vh;
                }
            }
        </style>
    @endsection
