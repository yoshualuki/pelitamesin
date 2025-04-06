@extends('layouts.admin')
@csrf
@section('content')
<div class="container">
    <h1>Retur Admin</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Retur</h4>
                    {{-- <form action="{{ route('admin.retur.search') }}" method="GET">
                        <input type="text" name="search" placeholder="Cari Retur">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form> -- --}}
                    {{-- <a href="{{ route('admin.retur.create') }}" class="btn btn-primary">Tambah Retur</a> --}}
                    <a href="{{ route('admin.product') }}" class="btn btn-secondary">Kembali</a>
                 </div>
                 <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Email</th>
                                <th>No Pesanan</th>
                                <th>Jumlah</th>
                                <th>Alasan</th>
                                <th>Image</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach ($retur as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->alamat }}</td>
                                </tr>
                            @endforeach 
                        </tbody> --}}

                    </table>

                    {{-- {{ $retur->links() }} --}}


                </div>
            </div>
        </div>  
    </div>

</div>
@endsection



