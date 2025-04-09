@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Produk</h5>
        </div>
        <div class="card-body">
            
        @if (session()->has('error') || isset($error) && $error)
            
            <div class="alert alert-danger fade slide-fade-in" id="errorAlert">
                <i class="fas fa-exclamation-circle"></i>
                {{ session()->has('error') ? session()->get('error') : $error }}
            </div>
        @endif

            <form action="{{ route('admin.product.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label>Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Berat (gram)</label>
                    <input type="number" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Gambar Produk</label>
                    <input type="file" name="image" class="form-control">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                </div>

                <button type="submit" class="btn btn-primary">Update Produk</button>
                <a href="{{ route('admin.product') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection 