@extends('customer.template')

@section('content')
    @php
        function getStatusIcon($status)
        {
            return match ($status) {
                'completed' => 'fa-check-circle',
                'processing' => 'fa-cog',
                'shipped' => 'fa-shipping-fast',
                'waiting_payment' => 'fa-clock',
                'waiting_confirmation' => 'fa-hourglass-half',
                'cancelled' => 'fa-times-circle',
                'refunded' => 'fa-undo',
                default => 'fa-info-circle',
            };
        }

        function getStatusColor($status)
        {
            return match ($status) {
                'completed' => 'success',
                'processing' => 'info',
                'shipped' => 'primary',
                'waiting_payment' => 'warning',
                'waiting_confirmation' => 'secondary',
                'cancelled' => 'danger',
                'refunded' => 'dark',
                default => 'secondary',
            };
        }
    @endphp
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Pesanan Saya</h1>
                <p class="text-muted mb-0">Kelola dan pantau status pesanan Anda</p>
            </div>

            <div class="col-md-4">
                <form action="{{ route('orders') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari kode order/nama produk..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="filter-bar mb-4">
        <div class="d-flex flex-column gap-2">
            <h4 class="text-muted mb-2 fs-6">Filter berdasarkan status:</h4>
            <div class="filter-nav d-flex align-items-center gap-2 overflow-x-auto pb-3">
                <a href="{{ route('orders') }}" class="filter-pill text-nowrap{{ !request('status') ? ' active' : '' }}">
                    <i class="fas fa-layer-group me-2"></i>Semua
                </a>
                @foreach ($statuses as $key => $status)
                    <a href="{{ route('orders', ['status' => $key]) }}"
                        class="filter-pill text-nowrap{{ request('status') === $key ? ' active' : '' }}">
                        <i class="fas {{ getStatusIcon($key) }} me-2"></i>
                        {{ $status }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>


    <div class="row g-4">
        @forelse($orders as $order)
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-shadow rounded-4 mb-2">
                    <div class="card-body p-4">
                        <div class="row align-items-center gy-3">
                            <!-- Order Header -->
                            <div class="col-lg-3 col-md-4">
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-shopping-bag text-primary me-2"></i>
                                        <span class="fw-medium">#{{ $order->order_id }}</span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $order->created_at->translatedFormat('d F Y') }}
                                        <span class="mx-1">•</span>
                                        <i class="far fa-clock me-1"></i>
                                        {{ $order->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            <!-- Order Items -->
                            <div class="col-lg-6 col-md-5">
                                <div class="products-preview bg-light rounded-3 p-3">
                                    @foreach ($order->items->take(2) as $item)
                                        <div
                                            class="product-item d-flex align-items-center {{ !$loop->last ? 'mb-2 pb-2 border-bottom' : '' }}">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset($item->products->image ?? 'images/default-product.png') }}"
                                                    class="rounded-2" width="60" height="60"
                                                    style="object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="product-name mb-1">{{ $item->products->name }}</h6>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-dark border me-2">
                                                        {{ $item->quantity }}x
                                                    </span>
                                                    <span class="text-muted small">
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-end ms-3">
                                                <span class="fw-medium text-primary">
                                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($order->items->count() > 2)
                                        <div class="text-center mt-2 pt-2 border-top">
                                            <a href="{{ route('orders.show', $order) }}" class="text-decoration-none">
                                                <span class="text-primary small fw-medium">
                                                    <i class="fas fa-plus-circle me-1"></i>
                                                    {{ $order->items->count() - 2 }} item lainnya
                                                </span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Order Actions -->
                            <div class="col-lg-3 col-md-3">
                                <div class="d-flex flex-column h-100 justify-content-between">
                                    <div>
                                        <span
                                            class="badge bg-{{ getStatusColor($order->status) }} rounded-pill d-block mb-2">
                                            <i class="fas {{ getStatusIcon($order->status) }} me-1"></i>
                                            {{ $statuses[$order->status] ?? $order->status }}
                                        </span>
                                        <div class="text-end">
                                            <div class="text-muted small mb-1">Total Belanja</div>
                                            <h5 class="mb-3 fw-bold text-primary">
                                                Rp
                                                {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        @if ($order->status === 'waiting_payment')
                                            <a href="{{ route('checkout.process-payment', $order) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-credit-card me-1"></i> Bayar
                                            </a>
                                        @elseif ($order->status === 'shipped')
                                            <button class="btn btn-success mt-2" data-bs-toggle="modal"
                                                data-bs-target="#confirmDeliveryModal"
                                                data-order-id="{{ $order->order_id }}">
                                                <i class="fas fa-check-circle me-1"></i>Konfirmasi Barang Diterima
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5">
                        <img src="{{ asset('images/empty-history.svg') }}" class="img-fluid mb-4"
                            style="max-height: 200px">
                        <h4 class="mb-2">Belum Ada Pesanan</h4>
                        <p class="text-muted mb-4">Mulai berbelanja dan temukan produk yang Anda butuhkan</p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($orders->count() > 0)
        <div class="d-flex justify-content-center mt-5">
            {{ $orders->links() }}
        </div>
    @endif
    </div>


    <div class="modal fade" id="confirmDeliveryModal" tabindex="-1">
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
@endsection

@section('styles')
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
        }

        .product-name {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 0.875rem;
            line-height: 1.5;
            margin: 0;
        }

        .products-preview {
            background: #f8f9fa;
        }

        .product-item:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .badge {
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .dropdown-item.active {
            background-color: #e9ecef;
            color: var(--bs-primary);
        }

        @media (max-width: 992px) {
            .product-item {
                padding: 0.5rem 0;
            }
        }

        .filter-chips {
            overflow-x: auto;
            padding: 4px;
            background: #000000;
            -webkit-overflow-scrolling: touch;
        }

        .filter-chips a {
            color: #fff;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 100px;
            background: #f8f9fa;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: 1px solid #dee2e6;
        }

        .chip:hover {
            background: #e9ecef;
            color: var(--bs-primary);
            border-color: #ced4da;
        }

        .chip.active {
            background: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
        }

        .chip .counter {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.125rem 0.5rem;
            border-radius: 100px;
            font-size: 0.75rem;
        }

        .chip:not(.active) .counter {
            background: #e9ecef;
        }

        @media (max-width: 768px) {
            .filter-chips {
                border-bottom: 1px solid #eee;
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            .status-chip {
                display: inline-block;
                padding: 6px 16px;
                border-radius: 100px;
                font-size: 14px;
                color: #666;
                background: #f5f5f5;
                text-decoration: none;
                white-space: nowrap;
                transition: all 0.2s ease;
            }

            .status-chip:hover {
                background: #e9ecef;
                color: #333;
            }

            .status-chip.active {
                background: #e8f3ff;
                color: #0095f6;
            }

            .reset-filter {
                color: #00a65a;
                text-decoration: none;
                font-size: 14px;
                white-space: nowrap;
            }

            .reset-filter:hover {
                text-decoration: underline;
            }

            /* Remove old chip styles */
            .chip,
            .chip:hover,
            .chip.active,
            .chip .counter {
                all: unset;
            }
        }

        /* Improved Filter Pills */
        .filter-nav {
            scrollbar-width: thin;
            scrollbar-color: #e4e4e4 transparent;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            color: #4b5563;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            text-decoration: none !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .filter-pill:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .filter-pill.active {
            background: #ffffff;
            border: 2px solid #3b82f6;
            color: #1d4ed8;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);
        }

        .filter-pill i {
            font-size: 0.9em;
            width: 18px;
            text-align: center;
        }

        /* Scrollbar styling */
        .filter-nav::-webkit-scrollbar {
            height: 4px;
        }

        .filter-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .filter-pill {
                padding: 6px 16px;
                font-size: 13px;
            }

            .filter-bar {
                margin: 0 -1rem;
                padding: 0 1rem;
            }
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


@section('scripts')
    <script>
        $(document).ready(function() {
            let ordersData = @json($orders->keyBy('order_id'));
            $('#confirmDeliveryModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var orderId = button.data('order-id');
                $('#selected_order_id').val(orderId);
                // Find the order's products
                let order = ordersData[orderId];
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
