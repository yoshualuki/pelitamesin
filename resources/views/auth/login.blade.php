@extends('customer.template')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Masuk ke Akun Anda</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="loginForm" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required autocomplete="email" autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required
                                        autocomplete="current-password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    Masuk
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa Password?</a>
                                <span class="mx-2">•</span>
                                <a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .toggle-password:hover {
            cursor: pointer;
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 10px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password').click(function() {
                const input = $(this).siblings('input');
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Form submission with validation
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = $('#submitBtn');
                const spinner = submitBtn.find('.spinner-border');

                // Show loading state
                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('login.submit') }}",
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.two_factor) {
                            // Handle 2FA case if implemented
                            window.location.href = response.redirect;
                        } else {
                            showSwalSuccess(response.message || 'Login berhasil!');

                            setTimeout(() => {
                                window.location.href = response.route;
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Email atau password salah. Silakan coba lagi.';

                        if (xhr.status === 422) {
                            // Laravel validation errors
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors)[0][0];

                            // Highlight invalid fields
                            $.each(errors, function(key, value) {
                                const input = $(`[name="${key}"]`);
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Login',
                            text: errorMessage,
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });

            // Clear validation errors when user starts typing
            $('input').on('input', function() {
                $(this).removeClass('is-invalid');
            });

            // Helper functions for Swal
            function showSwalSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: message,
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            function showSwalError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            }
        });
    </script>
@endsection
