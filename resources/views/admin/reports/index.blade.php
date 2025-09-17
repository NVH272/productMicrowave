@extends('layouts.app')

@section('content')
<style>
    /* Admin Reports Styles - simplified */
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
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: background-color 0.15s ease-in-out;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
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
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
    }

    .btn-outline-admin:hover {
        background: #007bff;
        color: white;
    }

    .stat-card {
        background: #fff;
        border-left: 4px solid #007bff;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        transition: box-shadow 0.15s ease-in-out;
    }

    .stat-card:hover {
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .stat-card.success {
        border-left-color: #28a745;
    }

    .stat-card.info {
        border-left-color: #17a2b8;
    }

    .stat-card.warning {
        border-left-color: #ffc107;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #495057;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        font-size: 2rem;
        color: #dee2e6;
    }
</style>

<div class="container-fluid">
    <!-- Admin Header -->
    <div class="admin-header text-center">
        <div class="container">
            <h2 class="mb-2">📊 Báo cáo tổng quan</h2>
            <p class="mb-3 text-muted">Thống kê và phân tích hệ thống</p>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.reports.charts') }}" class="btn-outline-admin">
                    <i class="fas fa-chart-pie"></i> Biểu đồ
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn-outline-admin">
                    <i class="fas fa-shopping-cart"></i> Đơn hàng
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Tổng đơn hàng</div>
                        <div class="stat-number">{{ number_format($totalOrders) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card success">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Đơn đã thanh toán</div>
                        <div class="stat-number">{{ number_format($paidOrders) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card info">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Tổng khách hàng</div>
                        <div class="stat-number">{{ number_format($totalCustomers) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card warning">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Tổng doanh thu</div>
                        <div class="stat-number">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Doanh thu theo danh mục -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-chart-pie"></i> Doanh thu theo danh mục
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th>Tổng doanh thu</th>
                                    <th>Số lượng bán</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryRevenue as $revenue)
                                <tr>
                                    <td>
                                        <i class="fas fa-tag text-primary"></i>
                                        {{ $revenue->category_name ?? 'Danh mục #' . $revenue->category_id }}
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VNĐ
                                    </td>
                                    <td>{{ number_format($revenue->total_qty) }} sản phẩm</td>
                                    <td>
                                        @php
                                        $percentage = $totalRevenue > 0 ? ($revenue->total_revenue / $totalRevenue) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $percentage }}%"
                                                aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> Chưa có dữ liệu doanh thu theo danh mục
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Doanh thu theo ngày -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-calendar-day"></i> Doanh thu theo ngày (7 ngày gần nhất)
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Tổng doanh thu</th>
                                    <th>Số đơn hàng</th>
                                    <th>Trung bình/đơn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByDate->take(7) as $revenue)
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar text-info"></i>
                                        @php
                                        try {
                                        $d = \Carbon\Carbon::parse($revenue->date)->format('d/m/Y');
                                        } catch (\Exception $e) {
                                        $d = $revenue->date;
                                        }
                                        @endphp
                                        {{ $d }}
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VNĐ
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $revenue->order_count }}</span>
                                    </td>
                                    <td>
                                        {{ number_format((float) $revenue->total_revenue / max($revenue->order_count, 1), 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> Chưa có dữ liệu doanh thu theo ngày
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Doanh thu theo tháng và năm -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-calendar-alt"></i> Doanh thu theo tháng
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tháng</th>
                                    <th>Doanh thu</th>
                                    <th>Đơn hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByMonth->take(6) as $revenue)
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar text-warning"></i>
                                        @php
                                        $m = str_pad($revenue->month ?? '', 2, '0', STR_PAD_LEFT);
                                        $label = isset($revenue->year) ? ($m . '/' . $revenue->year) : $m;
                                        @endphp
                                        {{ $label }}
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VNĐ
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $revenue->order_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> Chưa có dữ liệu
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-calendar"></i> Doanh thu theo năm
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Năm</th>
                                    <th>Doanh thu</th>
                                    <th>Đơn hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByYear as $revenue)
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar text-danger"></i>
                                        {{ $revenue->year }}
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VNĐ
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $revenue->order_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i> Chưa có dữ liệu
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Khách hàng tiềm năng và Sản phẩm bán chạy -->
    <div class="row mb-4">
        <!-- Khách hàng tiềm năng -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-star"></i> Khách hàng tiềm năng (Top 10)
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Số đơn</th>
                                    <th>Tổng chi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $index => $customer)
                                <tr>
                                    <td>
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 30px; height: 30px; font-size: 12px; font-weight: bold;">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $customer->name }}</div>
                                            <small class="text-muted">{{ $customer->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $customer->total_orders }}</span>
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($customer->total_spent, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-users"></i> Chưa có dữ liệu khách hàng
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sản phẩm bán chạy -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-fire"></i> Sản phẩm bán chạy (Top 10)
                </div>
                <div class="admin-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Đã bán</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSellingProducts as $index => $product)
                                <tr>
                                    <td>
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 30px; height: 30px; font-size: 12px; font-weight: bold;">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ Str::limit($product->name, 25) }}</div>
                                            <small class="text-muted">{{ number_format($product->price, 0, ',', '.') }} VNĐ</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{ $product->total_sold }}</span>
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($product->total_revenue, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-box"></i> Chưa có dữ liệu sản phẩm
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <!-- <div class="text-center mt-3">
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                <i class="fas fa-tachometer-alt"></i> Quay lại Dashboard
            </a>
            <a href="{{ route('admin.reports.charts') }}" class="btn-outline-admin">
                <i class="fas fa-chart-pie"></i> Xem Biểu đồ
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn-outline-admin">
                <i class="fas fa-shopping-cart"></i> Quản lý Đơn hàng
            </a>
        </div>
    </div> -->
</div>
@endsection