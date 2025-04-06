@extends('layouts.admin')

@section('content')

@php
    use App\Helpers\NumberHelper;
    use Illuminate\Support\Str;
@endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="alert-container">
        @if (session()->has('success') || isset($success) && $success)
            <div class="alert alert-success fade slide-fade-in" id="successAlert">
                <i class="fas fa-check-circle"></i>
                {{ session()->has('success') ? session()->get('success') : $success }}
            </div>
        @endif
        @if (session()->has('error') || isset($error) && $error)
        
            <div class="alert alert-danger fade slide-fade-in" id="errorAlert">
                <i class="fas fa-exclamation-circle"></i>
                {{ session()->has('error') ? session()->get('error') : $error }}
            </div>
        @endif
    </div>
    <div class="container"> 
        <div class="row">
            <div class="col-flex">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Daftar Produk</span>
                        {{-- <button type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#addProductModal">Tambah</button> --}}
                        <a href="{{ route('admin.product.create') }}"  class="btn btn-success" 
                            >Tambah</a>
                    </div>
                    <div class="card-body">
                        <form id="searchForm" action="{{ route('admin.product') }}" method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari produk berdasarkan nama atau brand..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Berat</th>
                                    <th>Brand</th>
                                    <th>Deskripsi</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>{{ $product->weight }} Gram</td>
                                    <td>{{ $product->brand }}</td>
                                    <td>{{ Str::limit($product->description, 100) }}</td>
                                    <td>
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="productForm"  method="POST" enctype="multipart/form-data">
                            {{-- @csrf --}}
                            {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="text" name="price" class="form-control" required oninput="this.value = formatNumber(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="stock">Stok</label>
                                <input type="text" name="stock" class="form-control" required oninput="this.value = formatNumber(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="weight">Berat</label>
                                <input type="text" name="weight" class="form-control" required oninput="this.value = formatNumber(this.value)">
                                
                            </div>
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" name="brand" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" class="form-control" id="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Gambar</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary ml-2 w-100 mt-4" id="btnaddProductLabel">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        @if (isset($product))
        <div class="modal" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="number" name="price" class="form-control" value="{{ NumberHelper::formatWithThousandSeparator($product->price) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stok</label>
                                <input type="number" name="stock" class="form-control" value="{{ NumberHelper::formatWithThousandSeparator($product->stock) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" class="form-control" id="description" required>{{ $product->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Gambar</label>
                                <input type="file" name="image" class="form-control">
                                @if(isset($product) && $product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="weight">Berat</label>
                                <input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight ?? '') }}" required oninput="this.value = formatNumber(this.value)">
                                @error('weight')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

@endsection

@section('scripts')
    {{-- $(document).ready(function() { --}}
        $('#productForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form default
            let token = $("meta[name='csrf-token']").attr("content");
            {{-- let name = $("input[name='name']").val(); // Mengambil nilai dari input dengan nama 'name'
            let price = parseFloat($("input[name='price']").val().replace(/[^0-9.-]+/g,"").replace(/,/g, '').replace(/\./g, '')); // Mengonversi nilai harga menjadi angka
            let stock = parseInt($("input[name='stock']").val().replace(/[^0-9]+/g,"").replace(/,/g, '').replace(/\./g, '')); // Mengonversi nilai stok menjadi angka
            let weight = parseInt($("input[name='weight']").val().replace(/[^0-9]+/g,"").replace(/,/g, '').replace(/\./g, '')); // Mengonversi nilai berat menjadi angka
            let brand = $("input[name='brand']").val(); // Mengambil nilai dari input dengan nama 'brand'
            let description = $("textarea[name='description']").val(); // Mengambil nilai dari textarea dengan nama 'description'
            let image = $("input[name='image']").prop('files')[0];
            


            var formData = new FormData();
            formData.append('name', name);
            formData.append('price', price);
            formData.append('stock', stock);
            formData.append('weight', weight);
            formData.append('brand', brand);
            formData.append('description', description);
            formData.append('image', image); --}}
            var formData = new FormData($('#productForm')[0]);
            var price = parseFloat(formData.get('price').replace(/,/g, '')); // Menghapus koma
            var stock = parseInt(formData.get('stock').replace(/,/g, ''));
            var weight = parseFloat(formData.get('weight').replace(/,/g, ''));


            formData.set('price', price); // Pastikan harga disimpan sebagai angka bulat
            formData.set('stock', stock);
            formData.set('weight', weight);
            
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.product.store') }}",
                cache: false,
                processData: false,
                contentType: false,
                data: formData,
                headers: {'X-CSRF-TOKEN': token },
                success: function(response) {
                    console.log(response);
                    //show success message
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response['success']}`,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $("#addProductModal").modal('hide');
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                    //show error message
                    Swal.fire({
                        type: 'error',
                        icon: 'error',
                        title: `${xhr.responseJSON['error']}`,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });
    {{-- }); --}}

    document.getElementById('searchForm').addEventListener('submit', function() {
        setTimeout(() => {
            document.querySelector('input[name="search"]').value = '';
        }, 1000); // Menghapus teks setelah 1 detik
    });
@endsection
