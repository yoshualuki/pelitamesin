<!-- resources/views/admin/orders/partials/detail.blade.php -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Pesanan</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="120">ID Pesanan</th>
                        <td>#{{ $order->order_id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-@switch($order->status)
                                @case('completed')success @break
                                @case('processing')primary @break
                                @case('shipped')info @break
                                @case('waiting_payment')warning @break
                                @case('waiting_confirmation')secondary @break
                                @case('cancelled')danger @break
                                @defaultsecondary @endswitch">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->payment_method)
                    <tr>
                        <th>Pembayaran</th>
                        <td>{{ $order->payment_method }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Pengiriman</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="120">Nama</th>
                        <td>{{ $order->shipping_address->name ?? $order->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>
                            {{ $order->shipping_address->address ?? '-' }}<br>
                            {{ $order->shipping_address->city ?? '' }}, 
                            {{ $order->shipping_address->province ?? '' }}<br>
                            {{ $order->shipping_address->postal_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $order->shipping_address->phone ?? $order->user->phone }}</td>
                    </tr>
                    @if($order->shipping_number)
                    <tr>
                        <th>Resi</th>
                        <td>
                            {{ $order->shipping_number }} ({{ $order->shipping_courier }})<br>
                            <small class="text-muted">{{ $order->shipping_notes }}</small>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Produk Dipesan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Produk</th>
                                <th width="80" class="text-end">Harga</th>
                                <th width="60" class="text-center">Qty</th>
                                <th width="80" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url ?? asset('images/default-product.png') }}" 
                                             class="rounded me-2" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="3" class="text-end">Subtotal</th>
                                <th class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Ongkir</th>
                                <th class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</th>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <th colspan="3" class="text-end">Diskon</th>
                                <th class="text-end">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>