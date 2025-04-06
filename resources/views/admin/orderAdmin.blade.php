@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Manajemen Pesanan</h5>
                </div>
                <div class="card-body">
                    <!-- Filter and Table content remains the same as your original -->
                    <!-- ... -->

                    <!-- Orders Table -->
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
                                    <tr id="order-row-{{ $order->order_id }}">
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->order_id) }}" class="fw-bold text-primary">
                                                #{{ $order->order_id }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $order->user->name ?? 'N/A' }}</h6>
                                                    <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small><br>
                                            <small>{{ $order->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-@switch($order->status)
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
                                                @case('waiting_payment') <i class="fas fa-clock me-1"></i> Menunggu Pembayaran @break
                                                @case('waiting_confirmation') <i class="fas fa-hourglass-half me-1"></i> Menunggu Konfirmasi @break
                                                @case('processing') <i class="fas fa-cog me-1"></i> Diproses @break
                                                @case('shipped') <i class="fas fa-truck me-1"></i> Dikirim @break
                                                @case('completed') <i class="fas fa-check-circle me-1"></i> Selesai @break
                                                @case('cancelled') <i class="fas fa-times-circle me-1"></i> Dibatalkan @break
                                                @case('partially_refunded') <i class="fas fa-exchange-alt me-1"></i> Pengembalian Sebagian @break
                                                @case('refunded') <i class="fas fa-undo me-1"></i> Dikembalikan @break
                                                @default <i class="fas fa-info-circle me-1"></i> {{ ucfirst($order->status) }} @endswitch
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown{{ $order->order_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $order->order_id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.orders.show', $order->order_id) }}">
                                                            <i class="fas fa-eye me-2"></i> Detail
                                                        </a>
                                                    </li>
                                                    @if($order->status == 'waiting_confirmation')
                                                    <li>
                                                        <button class="dropdown-item confirm-btn" data-id="{{ $order->order_id }}">
                                                            <i class="fas fa-check-circle me-2"></i> Konfirmasi
                                                        </button>
                                                    </li>
                                                    @endif
                                                    @if($order->status == 'processing')
                                                    <li>
                                                        <button class="dropdown-item shipping-btn" data-id="{{ $order->order_id }}">
                                                            <i class="fas fa-truck me-2"></i> Input Resi
                                                        </button>
                                                    </li>
                                                    @endif
                                                    @if(in_array($order->status, ['processing', 'shipped']))
                                                    <li>
                                                        <button class="dropdown-item complete-btn" data-id="{{ $order->order_id }}">
                                                            <i class="fas fa-check me-2"></i> Tandai Selesai
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

                    <!-- Pagination remains the same -->
                    <!-- ... -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Order Modal (Simplified for AJAX) -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> Konfirmasi Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

<!-- Success Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast align-items-center text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="successMessage">Pesanan berhasil dikonfirmasi!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Error Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="errorToast" class="toast align-items-center text-white bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="errorMessage">Terjadi kesalahan saat mengkonfirmasi pesanan!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Other modals (shipping, complete, cancel) remain the same -->
<!-- ... -->

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize toasts
        const successToast = new bootstrap.Toast(document.getElementById('successToast'));
        const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
        
        let currentOrderId = null;
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        
        // Confirm button click handler
        $('.confirm-btn').click(function() {            
            currentOrderId = $(this).data('id');
            confirmModal.show();
        });

        // Handle confirm order via AJAX
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
                    $('#confirmOrderBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
                },
                success: function(response) {
                    if (response.success) {
                        // Update the row in the table
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
                    const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan server';
                    $('#errorMessage').text(errorMessage);
                    errorToast.show();
                },
                complete: function() {
                    $('#confirmOrderBtn').prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Konfirmasi');
                    confirmModal.hide();
                    currentOrderId = null;
                }
            });
        });

        // Shipping button click handler (AJAX example)
        $('.shipping-btn').click(function() {
            const orderId = $(this).data('id');
            const shippingModal = new bootstrap.Modal(document.getElementById('shippingModal'));
            var url = "{{ route('admin.orders.ship', ':orderId') }}";
            url = url.replace(":orderId", orderId);
            // Reset form
            $('#shippingForm')[0].reset();
            $('#shippingForm').attr('action', url);
            
            shippingModal.show();
        });

        // Handle shipping form submission via AJAX
        $('#shippingForm').submit(function(e) {
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
                    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
                },
                success: function(response) {
                    if (response.success) {
                        // Update the row in the table
                        const row = $('#order-row-' + response.order.id);
                        
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
                        
                        // Close modal
                        $('#shippingModal').modal('hide');
                    } else {
                        $('#errorMessage').text(response.message || 'Terjadi kesalahan');
                        errorToast.show();
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan server';
                    $('#errorMessage').text(errorMessage);
                    errorToast.show();
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Resi');
                }
            });
        });

        // Similar AJAX implementation for complete and cancel actions
        // ...
    });
</script>
@endsection