@extends('layouts.admins')

@section('content')
    @include('inventory.form', ['inventory' => $inventory, 'products' => $products])
@endsection