@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Daftar Akun</div>
                <div class="card-body">
                    <form method="POST" id="registerForm"">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Daftar</button>
                        <a href="{{ route('login') }}" class="btn btn-secondary">Sudah punya akun?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section('scripts')
    {{-- $(document).ready(function() { --}}
        $('#registerForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form default
            let token = $("meta[name='csrf-token']").attr("content");
            var formData = new FormData($('#registerForm')[0]);
            $.ajax({
                type: 'POST',
                url: "{{ route('register.submit') }}",
                cache: false,
                processData: false,
                contentType: false,
                data: formData,
                headers: {'X-CSRF-TOKEN': token },
                success: function(response) {
                    //show success message
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response['success']}`,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    window.location.href = "{{ route('register') }}"; 
                },
                error: function(xhr) {
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
@endsection
