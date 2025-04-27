@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Manajemen Refund</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Permintaan Refund</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Refund</th>
                                <th>Order ID</th>
                                <th>Pelanggan</th>
                                <th>Total Refund</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refunds as $refund)
                                <tr>
                                    <td>{{ $refund->refund_id }}</td>
                                    <td>{{ $refund->order->order_id }}</td>
                                    <td>{{ $refund->user->name }}</td>
                                    <td>Rp {{ number_format($refund->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                    @if ($refund->status == 'pending') badge-warning
                                    @elseif($refund->status == 'approved') badge-success
                                    @else badge-danger @endif">
                                            {{ ucfirst($refund->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $refund->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-detail" data-id="{{ $refund->id }}"
                                            data-bs-toggle="modal" data-bs-target="#refundDetailModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if ($refund->status == 'pending')
                                            <button class="btn btn-sm btn-success approve-btn" data-id="{{ $refund->id }}"
                                                title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $refund->id }}"
                                                title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Refund -->
    <div class="modal fade" id="refundDetailModal" tabindex="-1" aria-labelledby="refundDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="refundDetailModalLabel">Detail Refund</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>ID Refund:</strong> <span id="modalRefundId"></span></p>
                            <p><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
                            <p><strong>Pelanggan:</strong> <span id="modalCustomer"></span></p>

                            <p><strong>Metode Pengembalian:</strong> <span id="modalRefundMethod"></span></p>
                            <p><strong>Rekening Tujuan:</strong> <span id="modalBankInfo"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Refund:</strong> <span id="modalAmount"></span></p>
                            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                            <p><strong>Tanggal:</strong> <span id="modalDate"></span></p>
                            <p><strong>Waktu Diproses:</strong> <span id="modalProcessedAt"></span></p>
                            <p><strong>Bukti Transfer Refund:</strong><br>
                                <span id="modalReceiptImage"></span>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3">Detail Produk Refund</h6>
                    <div id="modalProducts"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">Konfirmasi Setujui Refund</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menyetujui refund ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmApproveBtn">Setujui</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Refund</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="rejectForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        </div>
                        <input type="hidden" id="rejectRefundId" name="refund_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#dataTable').DataTable({
                responsive: true,
                // language: {
                //     url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json'
                // }
            });

            let selectedRefundId = null;

            // Approve button click
            $('.approve-btn').on('click', function() {
                selectedRefundId = $(this).data('id');
                $('#approveModal').modal('show');
            });
            // Reject button click
            $('.reject-btn').on('click', function() {
                selectedRefundId = $(this).data('id');
                $('#rejectModal').modal('show');
            });

            // Confirm approve
            $('#confirmApproveBtn').on('click', function(e) {
                if (!selectedRefundId) return;
                e.preventDefault();
                $.ajax({
                    url: '/admin/refund/' + selectedRefundId + '/approve',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Refund berhasil disetujui!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update status di tabel
                        var row = $('button.approve-btn[data-id="' + selectedRefundId + '"]')
                            .closest('tr');
                        row.find('span.badge')
                            .removeClass('badge-warning badge-danger')
                            .addClass('badge-success')
                            .text('Approved');
                        // Hilangkan tombol aksi
                        row.find('.approve-btn, .reject-btn').remove();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.message || 'Gagal konfirmasi order',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    complete: function() {
                        $('#approveModal').modal('hide');
                        selectedRefundId = null;
                    }
                });
            });

            // Submit reject form
            $('#rejectForm').on('submit', function(e) {
                if (!selectedRefundId) return;
                e.preventDefault();
                let reason = $('#rejection_reason').val();
                $.ajax({
                    url: '/admin/refund/' + selectedRefundId + '/reject',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rejection_reason: reason
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Konfirmasi Berhasil!',
                            text: 'Refund telah ditolak!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update status di tabel
                        var row = $('button.reject-btn[data-id="' + selectedRefundId + '"]')
                            .closest(
                                'tr');
                        row.find('span.badge')
                            .removeClass('badge-warning badge-success')
                            .addClass('badge-danger')
                            .text('Rejected');
                        // Hilangkan tombol aksi
                        row.find('.approve-btn, .reject-btn').remove();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.message || 'Gagal menolak order',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    complete: function() {
                        selectedRefundId = null;
                        $('#rejectModal').modal('hide');
                    }
                });
            });

            $('.view-detail').click(function() {
                var refundId = $(this).data('id');

                $.ajax({
                    url: '/admin/refund/' + refundId + '/detail',
                    method: 'GET',
                    success: function(response) {
                        $('#modalRefundId').text(response.refund_id);
                        $('#modalOrderId').text(response.order.order_id);
                        $('#modalCustomer').text(response.user.name);
                        $('#modalAmount').text('Rp ' + response.amount.toLocaleString());
                        $('#modalStatus').text(response.status);
                        $('#modalDate').text(new Date(response.created_at).toLocaleString());
                        $('#modalReason').text(response.reason ?? '-');
                        $('#modalRefundMethod').text(response.refund_method ?? '-');
                        if (response.bank_name && response.bank_account && response
                            .account_name) {
                            $('#modalBankInfo').text(response.bank_name + ' - ' + response
                                .bank_account + ' a.n. ' + response.account_name);
                        } else {
                            $('#modalBankInfo').text('-');
                        }
                        $('#modalProcessedAt').text(response.processed_at ? new Date(response
                            .processed_at).toLocaleString() : '-');
                        if (response.receipt_image) {
                            $('#modalReceiptImage').html('<img src="' + response.receipt_image +
                                '" class="img-fluid rounded" style="max-width:200px;">');
                        } else {
                            $('#modalReceiptImage').text('-');
                        }

                        // Produk refund & bukti media per produk
                        var productsHtml = '';
                        response.items.forEach(function(item, idx) {
                            productsHtml += '<div class="mb-3 border-bottom pb-2">';
                            productsHtml += '<div class="fw-bold">' + (idx + 1) + '. ' +
                                item.product_name + ' (Qty: ' + item.quantity +
                                ')</div>';
                            productsHtml += '<div>Alasan: ' + (item.reason ?? '-') +
                                '</div>';
                            productsHtml += '<div>Kondisi: ' + (item.condition_text ??
                                item.condition ?? '-') + '</div>';
                            productsHtml += '<div>Nominal Refund: Rp ' + (item
                                .refund_amount ? item.refund_amount
                                .toLocaleString() : '-') + '</div>';
                            // Bukti gambar/video per produk
                            if (item.images && item.images.length > 0) {
                                productsHtml += '<div class="row g-2 mt-2">';
                                item.images.forEach(function(img) {
                                    if (/\.(mp4|webm|ogg|mpeg)$/i.test(img)) {
                                        productsHtml +=
                                            '<div class="col-md-3 mb-2"><video controls width="100%" class="rounded border"><source src="' +
                                            img +
                                            '" type="video/mp4"></video></div>';
                                    } else {
                                        productsHtml +=
                                            '<div class="col-md-3 mb-2"><img src="' +
                                            img +
                                            '" class="img-fluid rounded border" style="max-height:120px"></div>';
                                    }
                                });
                                productsHtml += '</div>';
                            } else {
                                productsHtml +=
                                    '<div class="text-muted">Tidak ada bukti gambar/video untuk produk ini</div>';
                            }
                            productsHtml += '</div>';
                        });
                        $('#modalProducts').html(productsHtml);
                        // Kosongkan area media global
                        $('#modalProofMedia').html('');
                    }
                });
            });
        });
    </script>
@endsection
