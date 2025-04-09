<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pelita Mesin Jahit - Toko mesin jahit terpercaya sejak 1980">
    <title>@yield('title', 'Pelita Mesin Jahit')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --success-color: #198754;
        }
        
        /* Base Styles */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
        }
        
        .container {
            flex: 1;
        }
        
        /* Navigation */
        .main-navbar {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
        }
        
        /* Banner */
        .banner-container {
            overflow: hidden;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        
        .banner {
            width: 100%;
            height: auto;
            aspect-ratio: 16 / 6;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .banner:hover {
            transform: scale(1.02);
        }
        
        /* Product Cards */
        .product-card {
            flex: 0 0 280px;
            transition: all 0.3s ease;
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-img-container {
            height: 200px;
            position: relative;
            overflow: hidden;
        }
        
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            font-size: 0.75rem;
        }
        
        /* Scroll Container */
        .scroll-container {
            position: relative;
            margin-bottom: 3rem;
        }
        
        .products-scroll {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 1rem 0;
            -ms-overflow-style: none;
            scrollbar-width: none;
            gap: 1rem;
        }
        
        .products-scroll::-webkit-scrollbar {
            display: none;
        }
        
        .scroll-item {
            flex: 0 0 calc(25% - 1rem);
        }
        
        /* Scroll Buttons */
        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: none;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .scroll-btn:hover {
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
            background: var(--primary-color);
            color: white;
        }
        
        .scroll-btn.prev {
            left: -20px;
        }
        
        .scroll-btn.next {
            right: -20px;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-links a {
            color: white;
            font-size: 1.25rem;
            margin-right: 1rem;
            transition: transform 0.3s ease;
        }
        
        .social-links a:hover {
            transform: translateY(-3px);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .scroll-item {
                flex: 0 0 calc(33.33% - 1rem);
            }
            
            .banner {
                aspect-ratio: 16 / 7;
            }
        }
        
        @media (max-width: 768px) {
            .scroll-item {
                flex: 0 0 calc(50% - 1rem);
            }
            
            .banner {
                aspect-ratio: 16 / 8;
            }
        }
        
        @media (max-width: 576px) {
            .scroll-item {
                flex: 0 0 calc(100% - 1rem);
            }
            
            .scroll-btn {
                width: 30px;
                height: 30px;
            }
            
            .banner {
                aspect-ratio: 16 / 9;
            }
        }
        
        /* Utility Classes */
        .section-title {
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .text-underline {
            text-decoration: underline;
            text-decoration-color: var(--primary-color);
            text-underline-offset: 0.3rem;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light main-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-sewing-machine me-2"></i>Pelita Mesin Jahit
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('/')) active @endif" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('products*')) active @endif" href="{{ route('products') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('cart*')) active @endif" href="{{ route('cart') }}" id="cart-container">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            @if(session()->get('cart'))
                                <span id="cart-count" class="badge bg-primary ms-1"> {{  count(session()->get('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    @if(session()->get('user'))
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('orders*')) active @endif" href="{{ route('orders') }}">
                            <i class="fas fa-clipboard-list"></i> Pesanan
                        </a>
                    </li>
                    @endif
                </ul>
                
                <form class="d-flex me-3" action="{{ route('products') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="Cari produk..." aria-label="Search">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                @auth
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="User" class="rounded-circle me-2" width="32" height="32">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders') }}"><i class="fas fa-clipboard-list me-2"></i> Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    @if(session()->get('user'))
                        <a href="{{ route('logout') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    @else
                        <div class="d-flex">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i> Daftar
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="text-underline">Tentang Kami</h5>
                    <p>Pelita Mesin Jahit - Toko mesin jahit terpercaya sejak 1980 dengan berbagai pilihan merek dan model terbaik.</p>
                    <div class="mt-3">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Pelita Mesin Jahit" style="max-height: 50px;">
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <h5 class="text-underline">Hubungi Kami</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i> Jl. Bubutan No.101A, Surabaya
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone-alt me-2"></i> (031) 99250845
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i> pelita.sewing@gmail.com
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2"></i> Buka: Senin-Sabtu, 08:00 - 17:00
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h5 class="text-underline">Ikuti Kami</h5>
                    <div class="social-links mb-3">
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                    
                    <h6 class="mb-3">Pembayaran Aman</h6>
                    <div class="payment-methods">
                        <img src="{{ asset('images/payment/bca.webp') }}" alt="BCA" class="me-2" height="30">
                        <img src="{{ asset('images/payment/mandiri.webp') }}" alt="Mandiri" class="me-2" height="30">
                        <img src="{{ asset('images/payment/bni.jpeg') }}" alt="BNI" class="me-2" height="30">
                        <img src="{{ asset('images/payment/visa.webp') }}" alt="VISA" class="me-2" height="30">
                        <img src="{{ asset('images/payment/mastercard.webp') }}" alt="MasterCard" class="me-2" height="30">
                    </div>
                </div>
            </div>
            
            <hr class="my-4 bg-light opacity-25">
            
            <div class="text-center">
                <p class="mb-0">&copy; 2025 Pelita Mesin Jahit. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript"
		src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        // Initialize tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
        

        // SweetAlert2 error toast function
        function showSwalError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }

        // You can also add a success version if needed
        function showSwalSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#4BB543',
                iconColor: 'white',
                color: 'white',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }

        // Cart count update
        function updateCartCount() {
            $.get('{{ route("cart.count") }}', function(data) {
                $('#cart-count').text(data.count);
            });
        }
        
        // Initialize cart count on page load
        $(document).ready(function() {
            updateCartCount();
        });
    </script>
    @yield('scripts')
</body>
</html>