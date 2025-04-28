@extends('layouts.admin')

@section('title', 'Laporan Refund Produk')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 col-md-4 mb-2">
                <div class="card text-bg-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Total Refund</h5>
                        <h3 class="card-text">{{ $totalRefunds }}</h3>
                    </div>
            </div>
            </div>
            <div class="col-12 col-md-4 mb-2">
                <div class="card text-bg-success h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Total Nominal Refund</h5>
                        <h3 class="card-text">Rp {{ number_format($totalRefundAmount, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Daftar Refund Produk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="refundsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>ID Refund</th>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Metode Refund</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $refund)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $refund->id }}</td>
                                    <td>{{ $refund->order?->order_id ?? '-' }}</td>
                                    <td>{{ $refund->user?->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($refund->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $refund->status === \App\Models\OrderRefund::STATUS_PENDING
                                                ? 'warning'
                                                : ($refund->status === \App\Models\OrderRefund::STATUS_APPROVED
                                                    ? 'info'
                                                    : ($refund->status === \App\Models\OrderRefund::STATUS_PROCESSED
                                                        ? 'success'
                                                        : 'secondary')) }}">
                                            @if ($refund->status === \App\Models\OrderRefund::STATUS_PENDING)
                                                Menunggu Persetujuan
                                            @elseif($refund->status === \App\Models\OrderRefund::STATUS_APPROVED)
                                                Disetujui
                                            @elseif($refund->status === \App\Models\OrderRefund::STATUS_PROCESSED)
                                                Diproses
                                            @else
                                                {{ ucfirst($refund->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $refund->refund_method ?? '-' }}</td>
                                    <td>{{ $refund->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                            data-bs-target="#refundProducts{{ $refund->id }}">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="refundProducts{{ $refund->id }}">
                                    <td colspan="9">
                                        <div class="p-2">
                                            <strong>Detail Produk Refund:</strong>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Nominal Refund</th>
                                                            <th>Alasan</th>
                                                            <th>Kondisi</th>
                                                            <th>Bukti</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($refund->items as $item)
                                                            <tr>
                                                                <td>
                                                                    {{ $item->orderItem?->products?->name ?? '-' }}
                                                                </td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>Rp
                                                                    {{ number_format($item->refund_amount, 0, ',', '.') }}
                                                                </td>
                                                                <td>{{ $item->reason }}</td>
                                                                <td>{{ $item->condition_text }}</td>
                                                                <td>
                                                                    @if ($item->images && is_array($item->images))
                                                                        <div class="d-flex flex-wrap gap-1">
                                                                            @foreach ($item->images as $img)
                                                                                @php
                                                                                    $isVideo = preg_match(
                                                                                        '/\.(mp4|webm|ogg|mpeg)$/i',
                                                                                        $img,
                                                                                    );
                                                                                    $videoId = 'videoModal' . md5($img);
                                                                                @endphp
                                                                                @if ($isVideo)
                                                                                    <a href="#" data-bs-toggle="modal"
                                                                                        data-bs-target="#{{ $videoId }}">
                                                                                        <video
                                                                                            style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                                                                                            <source
                                                                                                src="{{ asset($img) }}">
                                                                                        </video>
                                                                                    </a>
                                                                                    <!-- Modal Video -->
                                                                                    <div class="modal fade"
                                                                                        id="{{ $videoId }}"
                                                                                        tabindex="-1"
                                                                                        aria-labelledby="{{ $videoId }}Label"
                                                                                        aria-hidden="true">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-centered modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title"
                                                                                                        id="{{ $videoId }}Label">
                                                                                                        Pratinjau Video</h5>
                                                                                                    <button type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Tutup"></button>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="modal-body text-center">
                                                                                                    <video controls
                                                                                                        style="max-width:100%;max-height:70vh;">
                                                                                                        <source
                                                                                                            src="{{ asset($img) }}">
                                                                                                        Browser tidak
                                                                                                        mendukung video.
                                                                                                    </video>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <a href="{{ asset($img) }}"
                                                                                        target="_blank">
                                                                                        <img src="{{ asset($img) }}"
                                                                                            alt="Bukti Refund"
                                                                                            style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                                                                                    </a>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Data refund tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- DataTables Script --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#refundsTable').DataTable({
                    "order": [],
                    "pageLength": 10,
                    "language": {
                        "search": "Cari:",
                        "lengthMenu": "Tampilkan _MENU_ entri",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        "paginate": {
                            "previous": "Sebelumnya",
                            "next": "Berikutnya"
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
