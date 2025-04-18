<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Pesanan</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="140">Status</th>
                        <td>
                            <span
                                class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">
                                {{ $lastStatus }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $order->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>{{ $order->payment_method ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Pengiriman</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="140">Penerima</th>
                        <td>{{ $order->recipient_name }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $order->recipient_phone }}</td>
                    </tr>
                    @if ($order->tracking_number)
                        <tr>
                            <th>No. Resi</th>
                            <td>
                                {{ $order->tracking_number }}<br>
                                <small class="text-muted">{{ strtoupper($order->courier) }} -
                                    {{ $order->service }}</small>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Products -->
<div class="card">
    <div class="card-header bg-light">
        <h6 class="mb-0">Produk Dipesan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Produk</th>
                        <th width="100" class="text-end">Harga</th>
                        <th width="80" class="text-center">Qty</th>
                        <th width="120" class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $item->product_image) }}"
                                        alt="{{ $item->product_name }}" class="rounded me-2" width="40"
                                        height="40" onerror="this.src='{{ asset('images/default-product.png') }}'">
                                    <div>
                                        <h6 class="mb-0">{{ $item->product_name }}</h6>
                                    </div>
                                </div>
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
                        <th colspan="3" class="text-end">Subtotal</th>
                        <th class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Ongkir</th>
                        <th class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">Rp {{ number_format($order->final_amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
</style>
