@extends('layouts.admin')

@section('content')
    @include('admin.inventory.form', ['products' => $products])
@endsection