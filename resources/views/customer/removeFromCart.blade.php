<div class="container mt-5">
    <h2>Remove From Cart</h2>
    <p>Produk berhasil dihapus dari keranjang</p>
    <a href="{{ route('customer.cart') }}" class="btn btn-primary">Kembali ke Keranjang</a>
    <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Kembali ke Toko</a>
    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
    {{-- <a href="{{ route('customer.checkout') }}" class="btn btn-success">Checkout</a> --}}
    {{-- <a href="{{ route('customer.addToCart') }}" class="btn btn-success">Tambahkan ke Keranjang</a> --}}

</div>
@endsection
