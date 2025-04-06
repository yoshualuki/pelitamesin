@extends('customer.template')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Pesanan Saya</h1>
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filter Status
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('orders') }}">Semua Pesanan</a></li>
                    @foreach($statuses as $key => $status)
                    <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('orders', ['status' => $key]) }}">
                        {{ $status }}
                        <span class="badge bg-{{ getStatusColor($key) }} ms-2">{{ $orderCounts[$key] ?? 0 }}</span>
                    </a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($orders as $order)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small">Pesanan #{{ $order->order_number }}</span>
                        <h5 class="mb-0">{{ $order->created_at->translatedFormat('d M Y') }}</h5>
                    </div>
                    <span class="badge rounded-pill bg-{{ getStatusColor($order->status) }} px-3 py-2">
                        {{ $statuses[$order->status] ?? $order->status }}
                    </span>
                </div>
                
                <div class="card-body">
                    <div class="mb-3">
                        @foreach($order->items->take(2) as $item)
                        <div class="d-flex mb-2">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($item->product->image ?? 'placeholder.jpg') }}" 
                                     class="rounded" width="60" height="60" style="object-fit: cover">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                <small class="text-muted">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                        <div class="text-center mt-2">
                            <small class="text-muted">+ {{ $order->items->count() - 2 }} item lainnya</small>
                        </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <div>
                            <small class="text-muted">Total Belanja</small>
                            <h5 class="mb-0">Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            @if($order->status === 'waiting_payment')
                            <a href="{{ route('checkout.process-payment', $order) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-credit-card me-1"></i> Bayar
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <img src="{{ asset('images/empty-history.svg') }}" class="img-fluid mb-4" style="max-height: 150px">
                    <h4 class="mb-3">Belum Ada Pesanan</h4>
                    <p class="text-muted mb-4">Anda belum melakukan pemesanan apapun</p>
                    <a href="{{ route('home') }}" class="btn btn-primary px-4">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    @if($orders->count() > 0)
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
// JavaScript untuk manajemen pesanan
</script>
@endpush
@endsection

@php
function getStatusColor($status) {
    switch($status) {
        case 'completed': return 'success';
        case 'processing': return 'info';
        case 'shipped': return 'primary';
        case 'waiting_payment': return 'warning';
        case 'waiting_confirmation': return 'secondary';
        case 'cancelled': return 'danger';
        case 'refunded': return 'dark';
        default: return 'light';
    }
}
@endphp