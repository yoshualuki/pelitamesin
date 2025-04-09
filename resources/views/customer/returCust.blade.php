@extends('layouts.app')
@section('content')

<div class="container">
    <h1 style="margin-top: 20px;">Form Retur Produk</h1>
    <form action="{{ route('customer.retur.index') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf
            <div class="form-group">
                <label for="name">Nomor Pesanan</label>
                <input type="text" class="form-control" id="product_id" name="product_id" required>
            </div>
            <div class="form-group">
                <label for="email">Nama Produk</label>
                <input type="text" class="form-control" id="product_id" name="product_id" required>
            </div>
            <div class="form-group">
                <label for="phone">Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="form-group">
                <label for="reason">Alasan Retur: </label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>
        <button type="submit" class="btn btn-primary" name="submit" style="margin-top: 10px;">Kirim Form Retur</button>
        <a href="{{ route('home') }}" class="btn btn-secondary" style="margin-top: 10px;">Kembali</a>
    </form>
</div>

@endsection
<script>
    function validateForm() {
        var order_id = document.getElementById('order_id').value;
        if (isNaN(order_id) || order_id <= 0) {
            alert("Nomor pesanan harus berupa angka dan lebih besar dari 0.");
            return false;
        }
        return true;
    }

    function validateProductId() {
        var product_id = document.getElementById('product_id').value;
        if (product_id === "") {
            alert("Nama produk tidak boleh kosong.");
            return false;
        }
        return true;
    }

</script>


