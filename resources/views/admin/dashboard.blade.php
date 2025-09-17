@extends('layouts.app')

@section('content')
<style>
    /* Admin Dashboard Styles - simplified */
    .admin-header {
        background: #f8f9fa;
        color: #495057;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }

    .admin-card {
        background: #fff;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.15s ease-in-out;
        margin-bottom: 1.5rem;
        height: 100%;
    }

    .admin-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .admin-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem 0.375rem 0 0;
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .admin-card-body {
        padding: 1rem;
    }

    .btn-admin {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 0.25rem;
        padding: 1rem;
        font-weight: 500;
        transition: background-color 0.15s ease-in-out;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        min-height: 100px;
        text-align: center;
    }

    .btn-admin:hover {
        background: #0056b3;
        color: white;
    }

    .btn-outline-admin {
        background: transparent;
        color: #007bff;
        border: 1px solid #007bff;
        border-radius: 0.25rem;
        padding: 1rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        min-height: 100px;
        text-align: center;
    }

    .btn-outline-admin:hover {
        background: #007bff;
        color: white;
    }

    .btn-admin i, .btn-outline-admin i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .btn-admin span, .btn-outline-admin span {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .btn-admin small, .btn-outline-admin small {
        font-size: 0.75rem;
        opacity: 0.8;
    }
</style>

<div class="container-fluid">
    <!-- Admin Header -->
    <div class="admin-header text-center">
        <div class="container">
            <h2 class="mb-2">🏠 Admin Dashboard</h2>
            <p class="mb-3 text-muted">Chào mừng {{ Auth::user()->name ?? 'Admin' }}, đây là trang tổng quan hệ thống</p>
            <small class="text-muted">{{ now()->format('d/m/Y H:i') }}</small>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('admin.categories') }}" class="btn-admin">
                <i class="fas fa-tags"></i>
                <span>Quản lý Danh mục</span>
                <small>{{ $totalCategories }} danh mục</small>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('admin.products') }}" class="btn-admin">
                <i class="fas fa-box"></i>
                <span>Quản lý Sản phẩm</span>
                <small>{{ $totalProducts }} sản phẩm</small>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('admin.orders.index') }}" class="btn-admin">
                <i class="fas fa-shopping-cart"></i>
                <span>Quản lý Đơn hàng</span>
                <small>{{ $totalOrders }} đơn hàng</small>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('admin.users.index') }}" class="btn-admin">
                <i class="fas fa-users"></i>
                <span>Quản lý Người dùng</span>
                <small>{{ $totalUsers }} người dùng</small>
            </a>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-3">
            <a href="{{ route('admin.reports.index') }}" class="btn-outline-admin">
                <i class="fas fa-chart-bar"></i>
                <span>Báo cáo & Thống kê</span>
                <small>Xem chi tiết doanh thu</small>
            </a>
        </div>
        
        <div class="col-lg-6 col-md-6 mb-3">
            <a href="{{ route('admin.reports.charts') }}" class="btn-outline-admin">
                <i class="fas fa-chart-pie"></i>
                <span>Biểu đồ Doanh thu</span>
                <small>Xem biểu đồ trực quan</small>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-bolt"></i> Thao tác nhanh
                </div>
                <div class="admin-card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.products') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-plus"></i> Thêm sản phẩm
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.categories') }}" class="btn btn-outline-success btn-sm w-100">
                                <i class="fas fa-plus"></i> Thêm danh mục
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-eye"></i> Xem đơn hàng
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-warning btn-sm w-100">
                                <i class="fas fa-chart-line"></i> Xem báo cáo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection