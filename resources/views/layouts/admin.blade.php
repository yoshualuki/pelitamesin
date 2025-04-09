<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --transition-speed: 0.3s;
        }
        
        body {
            overflow-x: hidden;
            padding-top: 56px; /* Add padding to body for fixed navbar */
        }
        
        #sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - 56px); /* Subtract navbar height */
            position: fixed;
            left: 0;
            top: 56px; /* Start below navbar */
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-y: auto;
            background: #343a40;
        }
        
        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        #sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        #sidebar.collapsed .sidebar-icon {
            margin-right: 0;
        }
        
        #sidebar.collapsed .dropdown-menu {
            position: absolute !important;
            left: var(--sidebar-collapsed-width);
            top: 0;
            width: 200px;
        }
        
        #content {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
            min-height: calc(100vh - 56px);
            padding: 20px;
        }
        
        #content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }
 
        #sidebar.collapsed .sidebar-header {
            display: none;
        }
        
        .sidebar-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }
        
        .sidebar-icon {
            margin-right: 10px;
            font-size: 1.2rem;
            min-width: 20px;
            text-align: center;
        }
        
        .sidebar-text {
            white-space: nowrap;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 10px 0;
        }
        
        .sidebar-header {
            color: rgba(255, 255, 255, 0.6);
            padding: 10px 15px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
        }
        .badge-warning { background-color: #f6c23e; color: #fff; }
        .badge-primary { background-color: #4e73df; color: #fff; }
        .badge-info { background-color: #36b9cc; color: #fff; }
        .badge-secondary { background-color: #858796; color: #fff; }
        .badge-success { background-color: #1cc88a; color: #fff; }
        .badge-danger { background-color: #e74a3b; color: #fff; }
        .badge-dark { background-color: #5a5c69; color: #fff; }
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
            
            #content.collapsed {
                margin-left: var(--sidebar-collapsed-width);
            }
        }
    </style>
    @yield('style')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="toggle-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="ms-auto d-flex align-items-center">
                <a href="/logout" class="nav-link text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header p-3">
            <div class="d-flex align-items-center">
                <img src="https://mastertondental.co.nz/wp-content/uploads/2022/12/team-profile-placeholder.jpg"  width="40" height="40" alt="User" class="rounded-circle me-2">
                <div class="sidebar-text">
                    <div class="fw-bold">Admin User</div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <ul class="list-unstyled">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link @if (session()->get('menu') =='dashboard')
                        active
                    @endif">
                        <i class="sidebar-icon fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-header">Management</li>
                
                <li>
                    <a href="{{ route('admin.customer') }}" class="sidebar-link @if (session()->get('menu') =='users')
                        active
                    @endif">
                        <i class="sidebar-icon fas fa-users"></i>
                        <span class="sidebar-text">Users</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.product') }}" class="sidebar-link @if (session()->get('menu') =='products')
                        active
                    @endif">
                        <i class="sidebar-icon fas fa-box"></i>
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="sidebar-link @if (session()->get('menu') =='orders')
                        active
                    @endif">
                        <i class="sidebar-icon fas fa-tasks"></i>
                        <span class="sidebar-text">Orders</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.inventory') }}" class="sidebar-link @if (session()->get('menu') =='inventory')
                        active
                    @endif">
                        <i class="sidebar-icon fas fa-box"></i>
                        <span class="sidebar-text">Inventory</span>
                    </a>
                </li>
                
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content" class="p-4">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('content').classList.toggle('collapsed');
            
            // Save state in localStorage
            const isCollapsed = document.getElementById('sidebar').classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
        
        // Check saved state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
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