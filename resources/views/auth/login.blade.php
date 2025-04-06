@extends('customer.template')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-5">
    <h2>Login</h2>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
        @csrf
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{ route('register') }}" class="btn btn-secondary">Belum punya akun?</a>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form default
            let token = $("meta[name='csrf-token']").attr("content");
            var formData = new FormData($('#loginForm')[0]);
            $.ajax({
                type: 'POST',
                url: "{{ route('login.submit') }}",
                cache: false,
                processData: false,
                contentType: false,
                data: formData,
                headers: {'X-CSRF-TOKEN': token },
                success: function(response) {
                    showSwalSuccess(`${response['success']}`);
                    
                    // Add delay before redirect
                    setTimeout(() => {
                        window.location.href = response['route'];
                    }, 2000); // 2000ms = 2 seconds
                },
                error: function(xhr) {
                    showSwalError(`${xhr.responseJSON['error']}`);
                }
            });
        });
    </script>
@endsection
