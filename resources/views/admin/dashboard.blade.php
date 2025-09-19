@extends('layouts.admin')

@section('content')
<style>
    /* Modern Admin Dashboard Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .dashboard-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        margin: 20px;
        padding: 30px;
    }

    .welcome-header {
        text-align: center;
        margin-bottom: 40px;
        padding: 30px;
        background: var(--primary-gradient);
        border-radius: 20px;
        color: white;
        box-shadow: var(--card-shadow);
    }

    .welcome-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .welcome-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
    }

    .stat-card.success::before {
        background: var(--success-gradient);
    }

    .stat-card.warning::before {
        background: var(--warning-gradient);
    }

    .stat-card.info::before {
        background: var(--info-gradient);
    }

    .stat-card.dark::before {
        background: var(--dark-gradient);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 20px;
        background: var(--primary-gradient);
    }

    .stat-card.success .stat-icon {
        background: var(--success-gradient);
    }

    .stat-card.warning .stat-icon {
        background: var(--warning-gradient);
    }

    .stat-card.info .stat-icon {
        background: var(--info-gradient);
    }

    .stat-card.dark .stat-icon {
        background: var(--dark-gradient);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 1rem;
        color: #7f8c8d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .management-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .management-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .management-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        transition: height 0.3s ease;
    }

    .management-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
        color: inherit;
        text-decoration: none;
    }

    .management-card:hover::before {
        height: 8px;
    }

    .management-card.success::before {
        background: var(--success-gradient);
    }

    .management-card.warning::before {
        background: var(--warning-gradient);
    }

    .management-card.info::before {
        background: var(--info-gradient);
    }

    .management-card.dark::before {
        background: var(--dark-gradient);
    }

    .management-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-bottom: 20px;
        background: var(--primary-gradient);
    }

    .management-card.success .management-icon {
        background: var(--success-gradient);
    }

    .management-card.warning .management-icon {
        background: var(--warning-gradient);
    }

    .management-card.info .management-icon {
        background: var(--info-gradient);
    }

    .management-card.dark .management-icon {
        background: var(--dark-gradient);
    }

    .management-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .management-subtitle {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 15px;
    }

    .management-count {
        font-size: 1.1rem;
        font-weight: 600;
        color: #34495e;
    }

    .unread-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #e74c3c;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .quick-actions {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--card-shadow);
    }

    .quick-actions h3 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 25px;
        font-size: 1.4rem;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .action-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 15px 20px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    .action-btn.success {
        background: var(--success-gradient);
    }

    .action-btn.warning {
        background: var(--warning-gradient);
    }

    .action-btn.info {
        background: var(--info-gradient);
    }

    @media (max-width: 768px) {
        .dashboard-container {
            margin: 10px;
            padding: 20px;
        }

        .welcome-header h1 {
            font-size: 2rem;
        }

        .stats-grid,
        .management-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Header -->
    <!-- <div class="welcome-header">
        <h1>🎯 Admin Dashboard</h1>
        <p>Chào mừng {{ Auth::user()->name ?? 'Admin' }}, đây là trang tổng quan hệ thống</p>
        <small>{{ now()->format('d/m/Y H:i') }}</small>
    </div> -->

    <!-- Statistics Cards -->
    <!-- <div class="stats-grid">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-number">{{ $totalProducts }}</div>
                <div class="stat-label">Sản phẩm</div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number">{{ $totalCategories }}</div>
                <div class="stat-label">Danh mục</div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Đơn hàng</div>
            </div>
            
            <div class="stat-card dark">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Người dùng</div>
            </div>
        </div> -->

    <!-- Management Cards -->
    <div class="management-grid">
        <a href="{{ route('admin.categories') }}" class="management-card warning">
            <div class="management-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="management-title">Quản lý Danh mục</div>
            <div class="management-subtitle">Thêm, sửa, xóa danh mục sản phẩm</div>
            <div class="management-count">{{ $totalCategories }} danh mục</div>
        </a>

        <a href="{{ route('admin.products') }}" class="management-card success">
            <div class="management-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="management-title">Quản lý Sản phẩm</div>
            <div class="management-subtitle">Thêm, sửa, xóa sản phẩm</div>
            <div class="management-count">{{ $totalProducts }} sản phẩm</div>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="management-card info">
            <div class="management-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="management-title">Quản lý Đơn hàng</div>
            <div class="management-subtitle">Theo dõi và cập nhật trạng thái đơn hàng</div>
            <div class="management-count">{{ $totalOrders }} đơn hàng</div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="management-card dark">
            <div class="management-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="management-title">Quản lý Người dùng</div>
            <div class="management-subtitle">Quản lý tài khoản khách hàng</div>
            <div class="management-count">{{ $totalUsers }} người dùng</div>
        </a>

        <a href="{{ route('admin.reviews.index') }}" class="management-card warning">
            <div class="management-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="management-title">Quản lý Đánh giá</div>
            <div class="management-subtitle">Xem và phản hồi đánh giá khách hàng</div>
            <div class="management-count">Đánh giá & Phản hồi</div>
            @if(isset($unreadReviews) && $unreadReviews > 0)
            <div class="unread-badge">{{ $unreadReviews }}</div>
            @endif
        </a>

        <a href="{{ route('admin.reports.index') }}" class="management-card success">
            <div class="management-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="management-title">Báo cáo & Thống kê</div>
            <div class="management-subtitle">Xem chi tiết doanh thu và báo cáo</div>
            <div class="management-count">Phân tích dữ liệu</div>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h3>
        <div class="action-buttons">
            <a href="{{ route('admin.products') }}" class="action-btn success">
                <i class="fas fa-plus"></i>
                Thêm sản phẩm
            </a>
            <a href="{{ route('admin.categories') }}" class="action-btn warning">
                <i class="fas fa-plus"></i>
                Thêm danh mục
            </a>
            <a href="{{ route('admin.orders.index') }}" class="action-btn info">
                <i class="fas fa-eye"></i>
                Xem đơn hàng
            </a>
            <a href="{{ route('admin.reports.index') }}" class="action-btn">
                <i class="fas fa-chart-line"></i>
                Xem báo cáo
            </a>
        </div>
    </div>
</div>

@endsection