@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Produk Baru</h4>
        </div>
        <div>
            @if($errors->any())
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="number" name="price" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Berat</label>
                    <input type="number" name="weight" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Brand</label>
                    <input type="text" name="brand" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Gambar</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.product') }}" class="btn btn-secondary">Batal</a>
            </form>
            <script>
                $(document).ready(function() {
                    $('#productForm').submit(function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        $.ajax({
                            url: '{{ route('admin.product.store') }}',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                console.log(response);
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.success,
                                    icon: 'success'
                                });
                            

                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>
@endsection 