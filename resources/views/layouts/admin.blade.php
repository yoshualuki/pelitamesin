<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 60px;
            --transition-speed: 0.3s;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --sidebar-bg: #343a40;
            --sidebar-active-bg: #4e73df;
            --sidebar-text: rgba(255, 255, 255, 0.8);
            --sidebar-active-text: #ffffff;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }

        body {
            overflow-x: hidden;
            padding-top: 56px;
        }

        .badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.35em 0.5em;
        }

        .badge-warning {
            background-color: var(--warning-color);
        }

        .badge-primary {
            background-color: var(--primary-color);
        }

        .badge-info {
            background-color: var(--info-color);
        }

        .badge-secondary {
            background-color: var(--secondary-color);
        }

        .badge-success {
            background-color: var(--success-color);
        }

        .badge-danger {
            background-color: var(--danger-color);
        }

        .badge-dark {
            background-color: var(--dark-color);
        }

        #sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - 56px);
            position: fixed;
            left: 0;
            top: 56px;
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-y: auto;
            background: var(--sidebar-bg);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        #sidebar.collapsed .sidebar-text,
        #sidebar.collapsed .sidebar-header,
        #sidebar.collapsed .badge {
            display: none;
        }

        #content {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
            min-height: calc(100vh - 56px);
            padding: 20px;
            background-color: #f8f9fa;
        }

        /* Improved Sidebar Elements */
        .sidebar-header {
            color: rgba(255, 255, 255, 0.6);
            padding: 10px 15px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-link {
            color: var(--sidebar-text);
            padding: 12px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            color: var(--sidebar-active-text);
            background: rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .sidebar-link.active {
            color: var(--sidebar-active-text);
            background: var(--sidebar-active-bg);
            border-left-color: #ffffff;
        }

        .sidebar-icon {
            margin-right: 10px;
            font-size: 1.1rem;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-profile {
            padding: 15px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar-profile-name {
            font-weight: 600;
            color: white;
            font-size: 0.9rem;
        }

        .sidebar-profile-role {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }

        /* Badges */
        .badge {
            font-size: 0.65rem;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            #sidebar {
                left: -100%;
            }

            #sidebar.collapsed {
                left: 0;
                width: var(--sidebar-collapsed-width);
            }

            #content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-link text-white" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="ms-auto d-flex align-items-center">
                <a href="/logout" class="nav-link text-white">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-profile">
            <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session()->get('user')->name) }}&background=random"
                    class="sidebar-profile-img me-3" alt="User">
                <div>
                    <div class="sidebar-profile-name">{{ session()->get('user')->name }}</div>
                    <div class="sidebar-profile-role">{{ ucfirst(session()->get('user')->role) }}</div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="list-unstyled">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link @if (session()->get('menu') == 'dashboard') active @endif">
                        <i class="sidebar-icon fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-header">Management</li>

                @if (session()->get('user')->role == 'owner')
                    <li>
                        <a href="{{ route('admin.useradmin.index') }}"
                            class="sidebar-link @if (session()->get('menu') == 'admin') active @endif">
                            <i class="sidebar-icon fas fa-user"></i>
                            <span class="sidebar-text">Admin</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('admin.customer') }}"
                        class="sidebar-link @if (session()->get('menu') == 'users') active @endif">
                        <i class="sidebar-icon fas fa-users"></i>
                        <span class="sidebar-text">Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.product') }}"
                        class="sidebar-link @if (session()->get('menu') == 'products') active @endif">
                        <i class="sidebar-icon fas fa-box"></i>
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.orders') }}"
                        class="sidebar-link @if (session()->get('menu') == 'orders') active @endif">
                        <i class="sidebar-icon fas fa-shopping-cart"></i>
                        <span class="sidebar-text">Orders</span>

                    </a>
                </li>
                @if (session()->get('user')->role == 'owner')
                    <li>
                        <a href="{{ route('admin.inventory') }}"
                            class="sidebar-link @if (session()->get('menu') == 'inventory') active @endif">
                            <i class="sidebar-icon fas fa-warehouse"></i>
                            <span class="sidebar-text">Inventory</span>
                        </a>
                    </li>
                @endif

                @if (session()->get('user')->role == 'owner')
                    <li class="sidebar-header">Reports</li>

                    <li>
                        <a href="{{ route('admin.reports.daily-transactions') }}"
                            class="sidebar-link @if (session()->get('menu') == 'daily-transactions') active @endif">
                            <i class="sidebar-icon fas fa-calendar-day"></i>
                            <span class="sidebar-text">Transaksi Harian</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.low-stock') }}"
                            class="sidebar-link @if (session()->get('menu') == 'low-stock') active @endif">
                            <i class="sidebar-icon fas fa-exclamation-triangle"></i>
                            <span class="sidebar-text">Stok Akan Habis</span>

                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.monthly-profit') }}"
                            class="sidebar-link @if (session()->get('menu') == 'monthly-profit') active @endif">
                            <i class="sidebar-icon fas fa-chart-line"></i>
                            <span class="sidebar-text">Keuntungan Bulanan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.product-returns') }}"
                            class="sidebar-link @if (session()->get('menu') == 'product-returns') active @endif">
                            <i class="sidebar-icon fas fa-undo"></i>
                            <span class="sidebar-text">Product dikembalikan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.top-products') }}"
                            class="sidebar-link @if (session()->get('menu') == 'top-products') active @endif">
                            <i class="sidebar-icon fas fa-star"></i>
                            <span class="sidebar-text">Produk Paling Laku</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.cancelled-orders') }}"
                            class="sidebar-link @if (session()->get('menu') == 'cancelled-orders') active @endif">
                            <i class="sidebar-icon fas fa-times-circle"></i>
                            <span class="sidebar-text">Order dibatalkan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.top-rated') }}"
                            class="sidebar-link @if (session()->get('menu') == 'top-rated') active @endif">
                            <i class="sidebar-icon fas fa-thumbs-up"></i>
                            <span class="sidebar-text">Produk Rating Terbaik</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.unsold-products') }}"
                            class="sidebar-link @if (session()->get('menu') == 'unsold-products') active @endif">
                            <i class="sidebar-icon fas fa-ban"></i>
                            <span class="sidebar-text">Produk Tidak Laku</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.payment-methods') }}"
                            class="sidebar-link @if (session()->get('menu') == 'payment-methods') active @endif">
                            <i class="sidebar-icon fas fa-credit-card"></i>
                            <span class="sidebar-text">Metode Pembayaran</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.low-rated') }}"
                            class="sidebar-link @if (session()->get('menu') == 'low-rated') active @endif">
                            <i class="sidebar-icon fas fa-thumbs-down"></i>
                            <span class="sidebar-text">Produk Rating Terendah</span>
                        </a>
                    </li>
                    <!-- Add other report links here -->
                @endif
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content" class="p-4">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('content').classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', document.getElementById('sidebar').classList.contains(
                'collapsed'));
        });

        // Restore sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('content').classList.add('collapsed');
            }

            // Make dropdown menus work in collapsed state
            const dropdowns = document.querySelectorAll('#sidebar .dropdown-toggle');
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function(e) {
                    if (document.getElementById('sidebar').classList.contains('collapsed')) {
                        e.preventDefault();
                        const submenu = this.nextElementSibling;
                        submenu.classList.toggle('show');
                    }
                });
            });
        });

        function formatNumber(value) {
            // Menghapus semua karakter non-digit
            value = value.replace(/\D/g, '');
            // Menambahkan pemisah ribuan
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    </script>
    @yield('scripts')
</body>

</html>
