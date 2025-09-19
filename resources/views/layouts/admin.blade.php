<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - NVH Store</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Admin Styles -->
    <style>
        :root {
            --admin-primary: #4e73df;
            --admin-success: #1cc88a;
            --admin-info: #36b9cc;
            --admin-warning: #f6c23e;
            --admin-danger: #e74a3b;
            --admin-secondary: #858796;
            --admin-light: #f8f9fc;
            --admin-dark: #5a5c69;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--admin-primary) 10%, #224abe 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 224px;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: #fff;
        }

        .sidebar-brand-icon {
            font-size: 2rem;
        }

        .sidebar-divider {
            border-color: rgba(255, 255, 255, 0.15);
            margin: 0 1rem 1rem;
        }

        .sidebar-heading {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 1rem;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            font-weight: 400;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            font-size: 0.85rem;
            margin-right: 0.25rem;
        }

        .content-wrapper {
            margin-left: 224px;
            min-height: 100vh;
        }

        .topbar {
            height: 4.375rem;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-bottom: 1px solid #e3e6f0;
        }

        .topbar-brand {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--admin-primary);
            text-decoration: none;
        }

        .main-content {
            padding: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            color: var(--admin-dark);
        }

        .border-left-primary {
            border-left: 0.25rem solid var(--admin-primary) !important;
        }

        .border-left-success {
            border-left: 0.25rem solid var(--admin-success) !important;
        }

        .border-left-info {
            border-left: 0.25rem solid var(--admin-info) !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid var(--admin-warning) !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid var(--admin-danger) !important;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-gray-800 {
            color: var(--admin-dark) !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .btn-primary {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-success {
            background-color: var(--admin-success);
            border-color: var(--admin-success);
        }

        .btn-info {
            background-color: var(--admin-info);
            border-color: var(--admin-info);
        }

        .btn-warning {
            background-color: var(--admin-warning);
            border-color: var(--admin-warning);
        }

        .btn-danger {
            background-color: var(--admin-danger);
            border-color: var(--admin-danger);
        }

        /* Chart container styles */
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .chart-wrap {
            min-height: 360px;
            position: relative;
        }

        .chart-wrap canvas {
            width: 100% !important;
            height: 360px !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .main-content {
                padding: 1rem;
            }
        }

        /* Loading spinner */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .loading-spinner i {
            color: var(--admin-primary);
            margin-bottom: 1rem;
        }

        /* Sidebar cuộn theo trang */
        .sidebar {
            background: linear-gradient(180deg, var(--admin-primary) 10%, #224abe 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 224px;
            z-index: 1000;
            transition: all 0.3s;

            /* thêm thuộc tính này */
            overflow-y: auto;
        }

        /* Đảm bảo các nav-link có cùng độ rộng */
        .nav-link {
            display: block;
            /* chiếm full chiều ngang */
            width: 100%;
            /* full width */
            box-sizing: border-box;
            /* tính padding trong width */
            padding: 0.75rem 1rem;
            /* chỉnh padding cho đều */
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        /* Hover và active phủ full */
        .nav-link:hover,
        .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            /* nền hover/active */
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt sidebar-brand-icon"></i>
            <div>Admin Panel</div>
        </a>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Quản lý</div>

        <nav class="nav">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>

            <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                <i class="fas fa-tags"></i>
                Danh mục
            </a>

            <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                <i class="fas fa-box"></i>
                Sản phẩm
            </a>

            <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-cart"></i>
                Đơn hàng
            </a>

            <a class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                <i class="fas fa-star"></i>
                Đánh giá
            </a>

            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                Người dùng
            </a>
        </nav>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Báo cáo</div>

        <nav class="nav">
            <a class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                <i class="fas fa-chart-bar"></i>
                Báo cáo tổng quan
            </a>

            <a class="nav-link {{ request()->routeIs('admin.reports.charts') ? 'active' : '' }}" href="{{ route('admin.reports.charts') }}">
                <i class="fas fa-chart-pie"></i>
                Biểu đồ
            </a>
        </nav>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Hệ thống</div>

        <nav class="nav">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-cog"></i>
                Cài đặt
            </a>

            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Đăng xuất
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar removed for simplified layout -->

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        // Chart.js default configuration
        Chart.defaults.font.family = "'Nunito', sans-serif";
        Chart.defaults.color = '#5a5c69';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 20;
    </script>

    @stack('modals')
    @stack('scripts')
</body>

</html>