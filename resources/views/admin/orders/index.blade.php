<!-- views/admin/orders/index.blade.php -->
@extends('layouts.app')

@section('content')
<style>
    /* Admin Orders Styles - simplified */
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

    .stat-card.warning {
        border-left-color: #ffc107;
    }

    .stat-card.info {
        border-left-color: #17a2b8;
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

    .shipping-status-select {
        min-width: 150px;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
    }

    .shipping-status-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

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
        color: #007bff;
        margin-bottom: 1rem;
    }

    .loading-spinner p {
        margin: 0;
        color: #495057;
        font-weight: 500;
    }

    .table th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        font-weight: 600;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
        border-color: #dee2e6;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>

<div class="container-fluid">
    <!-- Admin Header -->
    <div class="admin-header text-center">
        <div class="container">
            <h2 class="mb-2">📦 Quản lý Đơn hàng</h2>
            <p class="mb-3 text-muted">Quản lý và theo dõi tất cả đơn hàng trong hệ thống</p>
            
            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn-outline-admin">
                    <i class="fas fa-chart-bar"></i> Báo cáo
                </a>
                <a href="{{ route('admin.reports.charts') }}" class="btn-outline-admin">
                    <i class="fas fa-chart-pie"></i> Biểu đồ
                </a>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <!-- Thống kê nhanh -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Tổng đơn hàng</div>
                        <div class="stat-number">{{ $orders->count() }}</div>
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
                        <div class="stat-label">Đã hoàn thành</div>
                        <div class="stat-number">{{ $orders->where('shipping_status', 'đã giao')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card warning">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Đang xử lý</div>
                        <div class="stat-number">{{ $orders->whereIn('shipping_status', ['packaged', 'shipping'])->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card info">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-label">Tổng doanh thu</div>
                        <div class="stat-number">{{ number_format($orders->sum('total_price'), 0, ',', '.') }} VNĐ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng đơn hàng -->
    @if($orders->count() > 0)
    <div class="admin-card">
        <div class="admin-card-header">
            <i class="fas fa-list"></i> Danh sách đơn hàng
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>SĐT</th>
                            <th>Địa chỉ</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái giao hàng</th>
                            <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $index => $order)
                        <tr>
                            <td class="text-center">
                                <span class="badge badge-secondary">{{ $index + 1 }}</span>
                            </td>
                            <td>
                                <strong class="text-primary">#{{ $order->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <div class="font-weight-bold">{{ $order->name }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $order->phone }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($order->address, 30) }}</small>
                            </td>
                            <td>
                                <strong class="text-success">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</strong>
                            </td>
                            <td>
                                @php
                                $statusClass = match($order->payment_status) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                'cancelled' => 'secondary',
                                default => 'secondary'
                                };
                                @endphp
                                <form action="{{ route('admin.orders.payment-status.update', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="payment_status"
                                        class="form-control form-control-sm shipping-status-select"
                                        onchange="updatePaymentStatus(this, '{{ $order->id }}')">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                            ⏳ Chờ thanh toán
                                        </option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                            ✅ Đã thanh toán
                                        </option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                            ❌ Thất bại
                                        </option>
                                        <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>
                                            🚫 Hủy
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="shipping_status"
                                        class="form-control form-control-sm shipping-status-select"
                                        onchange="updateShippingStatus(this, '{{ $order->id }}')">

                                        <option value="pending" {{ $order->shipping_status == 'pending' ? 'selected' : '' }}>
                                            ⏳ Chờ xử lý
                                        </option>
                                        <option value="packaged" {{ $order->shipping_status == 'packaged' ? 'selected' : '' }}>
                                            📦 Đã đóng gói
                                        </option>
                                        <option value="shipping" {{ $order->shipping_status == 'shipping' ? 'selected' : '' }}>
                                            🚚 Đang vận chuyển
                                        </option>
                                        <option value="đã giao" {{ $order->shipping_status == 'đã giao' ? 'selected' : '' }}>
                                            ✅ Đã giao hàng
                                        </option>
                                        <option value="cancelled" {{ $order->shipping_status == 'cancelled' ? 'selected' : '' }}>
                                            ❌ Hủy
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-info btn-sm"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="admin-card">
        <div class="admin-card-body text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Chưa có đơn hàng nào</h5>
            <p class="text-muted">Khi có đơn hàng mới, chúng sẽ hiển thị ở đây.</p>
        </div>
    </div>
    @endif

    <!-- Navigation Buttons -->
    <!-- <div class="text-center mt-3">
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                <i class="fas fa-tachometer-alt"></i> Quay lại Dashboard
            </a>
            <a href="{{ route('admin.reports.index') }}" class="btn-outline-admin">
                <i class="fas fa-chart-bar"></i> Xem Báo cáo
            </a>
        </div>
    </div> -->
</div>

<!-- Loading overlay -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="loading-spinner">
        <i class="fas fa-spinner fa-spin fa-2x"></i>
        <p>Đang cập nhật...</p>
    </div>
</div>

<script>
    function updateShippingStatus(selectElement, orderId) {
        // Hiển thị loading
        document.getElementById('loadingOverlay').style.display = 'flex';

        // Lấy form chứa select
        const form = selectElement.closest('form');

        // Tạo FormData
        const formData = new FormData(form);

        // Gửi request AJAX
        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                // Ẩn loading
                document.getElementById('loadingOverlay').style.display = 'none';

                if (data.success) {
                    // Hiển thị thông báo thành công
                    showNotification('Cập nhật trạng thái giao hàng thành công!', 'success');
                } else {
                    // Hiển thị thông báo lỗi
                    showNotification('Có lỗi xảy ra khi cập nhật trạng thái!', 'error');
                    // Khôi phục giá trị cũ
                    selectElement.value = selectElement.dataset.oldValue;
                }
            })
            .catch(error => {
                // Ẩn loading
                document.getElementById('loadingOverlay').style.display = 'none';

                // Hiển thị thông báo lỗi
                showNotification('Có lỗi xảy ra khi cập nhật trạng thái!', 'error');

                // Khôi phục giá trị cũ
                selectElement.value = selectElement.dataset.oldValue;
            });
    }

    function updatePaymentStatus(selectElement, orderId) {
        // Hiển thị loading
        document.getElementById('loadingOverlay').style.display = 'flex';

        // Lấy form chứa select
        const form = selectElement.closest('form');

        // Tạo FormData
        const formData = new FormData(form);

        // Gửi request AJAX
        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                // Ẩn loading
                document.getElementById('loadingOverlay').style.display = 'none';

                if (data.success) {
                    // Hiển thị thông báo thành công
                    showNotification('Cập nhật trạng thái thanh toán thành công!', 'success');
                } else {
                    // Hiển thị thông báo lỗi
                    showNotification('Có lỗi xảy ra khi cập nhật trạng thái thanh toán!', 'error');
                    // Khôi phục giá trị cũ
                    selectElement.value = selectElement.dataset.oldValue;
                }
            })
            .catch(error => {
                // Ẩn loading
                document.getElementById('loadingOverlay').style.display = 'none';

                // Hiển thị thông báo lỗi
                showNotification('Có lỗi xảy ra khi cập nhật trạng thái thanh toán!', 'error');

                // Khôi phục giá trị cũ
                selectElement.value = selectElement.dataset.oldValue;
            });
    }

    function showNotification(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
        notification.innerHTML = `
        <i class="${icon}"></i> ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;

        document.body.appendChild(notification);

        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    // Lưu giá trị cũ của select khi focus
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.shipping-status-select');
        selects.forEach(select => {
            select.addEventListener('focus', function() {
                this.dataset.oldValue = this.value;
            });
        });
    });
</script>
@endsection