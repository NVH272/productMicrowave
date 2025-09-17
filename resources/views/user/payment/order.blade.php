@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history me-2"></i>Lịch sử đơn hàng</h2>
        <div class="d-flex gap-2">
            @if(app()->environment('local'))
                <a href="{{ route('user.debug.momo') }}" class="btn btn-warning" target="_blank">
                    <i class="fas fa-bug me-2"></i>Debug MoMo
                </a>
            @endif
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Đơn hàng #{{ $order->id }}
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="{{ $order->payment_status_badge_class }}">
                                {{ $order->payment_status_text }}
                            </span>
                            <span class="badge bg-info">
                                {{ $order->payment_method_text }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <h6><i class="fas fa-user me-2"></i>Thông tin giao hàng</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Người nhận:</strong> {{ $order->name }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Số điện thoại:</strong> {{ $order->phone }}
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Địa chỉ:</strong> {{ $order->address }}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6><i class="fas fa-box me-2"></i>Sản phẩm đã đặt</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">SL</th>
                                                    <th class="text-end">Đơn giá</th>
                                                    <th class="text-end">Thành tiền</th>
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
                                                                     class="img-thumbnail me-2" 
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            @endif
                                                            <span>{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                    <td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h6>
                                        
                                        <div class="mb-2">
                                            <strong>Tổng tiền:</strong>
                                            <span class="text-primary fs-5 fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <strong>Trạng thái:</strong>
                                            <div class="mt-1">{{ $order->status }}</div>
                                        </div>
                                        
                                        @if($order->transaction_id)
                                        <div class="mb-2">
                                            <strong>Mã giao dịch:</strong>
                                            <div class="mt-1"><code>{{ $order->transaction_id }}</code></div>
                                        </div>
                                        @endif
                                        
                                        @if($order->paid_at)
                                        <div class="mb-2">
                                            <strong>Thời gian thanh toán:</strong>
                                            <div class="mt-1">{{ $order->paid_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            @if($order->payment_status === 'pending' && $order->payment_method === 'momo')
                                                <a href="{{ route('user.orders.momo.pay', $order) }}" 
                                                   class="btn btn-warning btn-sm w-100">
                                                    <i class="fas fa-redo me-2"></i>Thanh toán lại MoMo
                                                </a>
                                            @endif
                                            
                                            @if($order->payment_status === 'pending' && $order->payment_method === 'bank')
                                                <div class="alert alert-info p-2 mb-2">
                                                    <small>
                                                        <strong>Thông tin chuyển khoản:</strong><br>
                                                        Ngân hàng: Vietcombank<br>
                                                        Số TK: 1234567890<br>
                                                        Nội dung: {{ $order->id }}
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            <a href="{{ route('user.orders.show', $order) }}" 
                                               class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Bạn chưa có đơn hàng nào</h5>
            <p class="text-muted">Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
            </a>
        </div>
    @endif
</div>

<style>
.card {
    border: none;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.badge {
    font-size: 0.8rem;
}
.table-sm td, .table-sm th {
    padding: 0.5rem;
}
</style>
@endsection
