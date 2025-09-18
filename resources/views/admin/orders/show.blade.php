@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Chi tiết đơn hàng #{{ $order->id }}</h1>
            <p class="mb-0 text-muted">Thông tin chi tiết đơn hàng</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Thông tin đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Mã đơn hàng:</td>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Tên khách hàng:</td>
                                    <td>{{ $order->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Số điện thoại:</td>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Địa chỉ:</td>
                                    <td>{{ $order->address }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Trạng thái:</td>
                                    <td>
                                        @php
                                        $statusClass = match($order->status) {
                                        'đã thanh toán' => 'success',
                                        'đã đặt (COD)' => 'info',
                                        'chờ xử lý' => 'warning',
                                        default => 'secondary'
                                        };
                                        @endphp
                                        <span class="badge badge-{{ $statusClass }}">{{ $order->status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Phương thức thanh toán:</td>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Trạng thái giao hàng:</td>
                                    <td>
                                        @if($order->shipping_status)
                                        @php
                                        $shippingClass = match($order->shipping_status) {
                                        'completed' => 'success',
                                        'shipping' => 'info',
                                        'packaged' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                        };
                                        @endphp
                                        <span class="badge badge-{{ $shippingClass }}">{{ $order->shipping_status }}</span>
                                        @else
                                        <span class="badge badge-secondary">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Ngày đặt:</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart"></i> Chi tiết sản phẩm
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                            <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}"
                                                class="img-thumbnail"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            @endif
                                            <div class="ml-3">
                                                <div class="font-weight-bold">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                                                @if($item->product && $item->product->category)
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3" class="text-right">Tổng cộng:</th>
                                    <th class="text-success">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Không có sản phẩm nào trong đơn hàng</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông tin bổ sung -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calculator"></i> Tổng kết
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="h2 font-weight-bold text-success mb-2">
                            {{ number_format($order->total_price, 0, ',', '.') }} VNĐ
                        </div>
                        <p class="text-muted mb-3">Tổng giá trị đơn hàng</p>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 font-weight-bold text-primary">{{ $order->items->count() }}</div>
                                <small class="text-muted">Sản phẩm</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 font-weight-bold text-info">{{ $order->items->sum('quantity') }}</div>
                                <small class="text-muted">Tổng số lượng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->note)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sticky-note"></i> Ghi chú
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->note }}</p>
                </div>
            </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Lịch sử
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Đơn hàng được tạo</h6>
                                <p class="text-muted mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @if($order->updated_at != $order->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Cập nhật lần cuối</h6>
                                <p class="text-muted mb-0">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for order show page */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .timeline-content p {
        font-size: 12px;
    }

    .badge-success {
        color: #fff;
        background-color: #1cc88a;
    }

    .badge-info {
        color: #fff;
        background-color: #36b9cc;
    }

    .badge-warning {
        color: #fff;
        background-color: #f6c23e;
    }

    .badge-secondary {
        color: #fff;
        background-color: #858796;
    }

    .badge-danger {
        color: #fff;
        background-color: #e74a3b;
    }

    .badge-primary {
        color: #fff;
        background-color: #4e73df;
    }

    .table-active {
        background-color: rgba(0, 0, 0, .075);
    }

    .img-thumbnail {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .timeline {
            padding-left: 20px;
        }

        .timeline-marker {
            left: -20px;
        }
    }
</style>
@endsection