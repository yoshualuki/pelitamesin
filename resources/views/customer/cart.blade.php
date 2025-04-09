@extends('customer.template')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>
    
    @if ($products && count($products) > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Produk</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="mb-0">Total: Rp {{ number_format($total, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @foreach ($products as $product)
            <div class="row align-items-center mb-4 pb-3 border-bottom">
                <!-- Gambar Produk -->
                <div class="col-md-2 mb-3 mb-md-0">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                         class="img-fluid rounded" style="max-height: 120px; width: auto;">
                </div>
                
                <!-- Detail Produk -->
                <div class="col-md-4">
                    <h5 class="mb-1">{{ $product->name }}</h5>
                    <p class="mb-1 text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="mb-0"><small>Jumlah: {{ $product->quantity }}</small></p>
                </div>
                
                <!-- Subtotal -->
                <div class="col-md-2 text-md-center">
                    <p class="mb-0 fw-bold">Rp {{ number_format($product->price * $product->quantity, 0, ',', '.') }}</p>
                </div>
                
                <!-- Aksi -->
                <div class="col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <form action="{{ route('cart.update', $product->id) }}" method="POST" class="d-flex">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $product->quantity }}" 
                                   min="1" max="{{ $product->stock }}" class="form-control form-control-sm" style="width: 70px;">
                            <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="bi bi-arrow-clockwise"></i> Perbarui
                            </button>
                        </form>
                        
                        <form action="{{ route('cart.remove', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Ringkasan Keranjang -->
            <div class="row mt-4">
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 3rem; color: #6c757d;"></i>
            <h4 class="mt-3">Keranjang Anda Kosong</h4>
            <p class="text-muted">Mulai berbelanja untuk menambahkan produk ke keranjang</p>
            <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                <i class="bi bi-shop"></i> Lihat Produk
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .quantity-input {
        width: 70px;
        text-align: center;
    }
    .product-img {
        max-height: 120px;
        width: auto;
        object-fit: contain;
    }
    .border-bottom {
        border-bottom: 1px solid #eee !important;
    }
</style>
@endsection

@section('scripts')
<script>
    // Validasi jumlah produk
    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        input.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            if (this.value > max) {
                this.value = max;
                alert('Jumlah tidak boleh melebihi stok yang tersedia');
            }
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Cek error dari server
        @if(session('error'))
            showSwalError('{{ session('error') }}');
        @endif
        
        // Cek error validasi
        @if($errors->any())
            @foreach($errors->all() as $error)
                showSwalError('{{ $error }}');
            @endforeach
        @endif
    });
</script>
@endsection