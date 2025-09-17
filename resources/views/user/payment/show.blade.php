@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-receipt me-2"></i>Chi tiết đơn hàng #{{ $order->id }}</h2>
        <div>
            <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Mua sắm
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin chính -->
        <div class="col-lg-8">
            <!-- Thông tin giao hàng -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Người nhận:</label>
                            <div class="form-control-plaintext">{{ $order->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số điện thoại:</label>
                            <div class="form-control-plaintext">{{ $order->phone }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ giao hàng:</label>
                        <div class="form-control-plaintext">{{ $order->address }}</div>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
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
                                                     class="img-thumbnail me-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</h6>
                                                @if($item->product && $item->product->category)
                                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-primary fs-6">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end align-middle">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                    <td class="text-end align-middle fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-end text-primary fs-5">{{ number_format($order->total_price, 0, ',', '.') }} đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar thông tin -->
        <div class="col-lg-4">
            <!-- Trạng thái đơn hàng -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái chung:</label>
                        <div class="form-control-plaintext">{{ $order->status }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái thanh toán:</label>
                        <div class="mt-2">
                            <span class="{{ $order->payment_status_badge_class }} fs-6">
                                {{ $order->payment_status_text }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phương thức thanh toán:</label>
                        <div class="mt-2">
                            <span class="badge bg-secondary fs-6">
                                {{ $order->payment_method_text }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tổng tiền:</label>
                        <div class="form-control-plaintext text-primary fs-4 fw-bold">
                            {{ number_format($order->total_price, 0, ',', '.') }} đ
                        </div>
                    </div>
                    
                    @if($order->transaction_id)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã giao dịch:</label>
                        <div class="form-control-plaintext">
                            <code class="bg-light p-2 rounded">{{ $order->transaction_id }}</code>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->paid_at)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thời gian thanh toán:</label>
                        <div class="form-control-plaintext">{{ $order->paid_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ngày đặt hàng:</label>
                        <div class="form-control-plaintext">{{ $order->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>

            <!-- Hành động -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Hành động</h5>
                </div>
                <div class="card-body">
                    @if($order->payment_status === 'pending' && $order->payment_method === 'momo')
                        <a href="{{ route('user.orders.momo.pay', $order) }}" 
                           class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-redo me-2"></i>Thanh toán lại MoMo
                        </a>
                    @endif
                    
                    @if($order->payment_status === 'pending' && $order->payment_method === 'bank')
                        <div class="alert alert-info p-3 mb-3">
                            <h6 class="alert-heading">Thông tin chuyển khoản</h6>
                            <hr>
                            <p class="mb-1"><strong>Ngân hàng:</strong> Vietcombank</p>
                            <p class="mb-1"><strong>Số tài khoản:</strong> 1234567890</p>
                            <p class="mb-1"><strong>Chủ tài khoản:</strong> CÔNG TY ABC</p>
                            <p class="mb-0"><strong>Nội dung:</strong> <code>{{ $order->id }}</code></p>
                        </div>
                    @endif
                    
                    @if($order->payment_status === 'paid')
                        <div class="alert alert-success p-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Đơn hàng đã được thanh toán thành công!</strong>
                        </div>
                    @endif
                    
                    @if($order->payment_status === 'failed')
                        <div class="alert alert-danger p-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Thanh toán thất bại!</strong>
                            <p class="mb-0 mt-2">Vui lòng thử lại hoặc liên hệ hỗ trợ.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
    font-size: 0.9rem;
}
.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    color: #212529;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}
</style>
@endsection
