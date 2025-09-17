@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h2 class="mb-0">🎉 Đặt hàng thành công!</h2>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <p class="lead">Cảm ơn bạn đã đặt hàng! Đơn hàng của bạn đã được ghi nhận.</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-receipt me-2"></i>Thông tin đơn hàng</h6>
                                    <p class="mb-1"><strong>Mã đơn hàng:</strong> #{{ $order['id'] ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Ngày đặt:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0"><strong>Trạng thái:</strong> 
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-credit-card me-2"></i>Thông tin thanh toán</h6>
                                    <p class="mb-1"><strong>Phương thức:</strong> 
                                        @if(isset($order['payment_method']))
                                            @if($order['payment_method'] === 'cod')
                                                <span class="badge bg-info">Thanh toán khi nhận hàng</span>
                                            @elseif($order['payment_method'] === 'momo')
                                                <span class="badge bg-danger">Ví MoMo</span>
                                            @elseif($order['payment_method'] === 'bank')
                                                <span class="badge bg-primary">Chuyển khoản ngân hàng</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Chưa xác định</span>
                                        @endif
                                    </p>
                                    <p class="mb-0"><strong>Trạng thái thanh toán:</strong> 
                                        <span class="badge bg-warning">Chờ thanh toán</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($order['items']) && count($order['items']) > 0)
                    <div class="mb-4">
                        <h5><i class="fas fa-shopping-cart me-2"></i>Sản phẩm đã đặt</h5>
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
                                    @foreach ($order['items'] as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if(isset($item['image']) && $item['image'])
                                                        <img src="{{ asset('uploads/products/' . $item['image']) }}" 
                                                             alt="{{ $item['name'] ?? 'Sản phẩm' }}" 
                                                             class="img-thumbnail" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $item['name'] ?? 'Sản phẩm không xác định' }}</h6>
                                                    @if(isset($item['category']))
                                                        <small class="text-muted">{{ $item['category'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6">{{ $item['quantity'] ?? 0 }}</span>
                                        </td>
                                        <td class="text-end">{{ number_format($item['price'] ?? 0, 0, ',', '.') }} đ</td>
                                        <td class="text-end fw-bold">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }} đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Tổng cộng:</th>
                                        <th class="text-end text-primary fs-5">{{ number_format($order['total'] ?? 0, 0, ',', '.') }} đ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Bạn có thể theo dõi trạng thái đơn hàng trong phần "Lịch sử đơn hàng"
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                            </a>
                            <a href="{{ route('user.orders.index') }}" class="btn btn-success">
                                <i class="fas fa-history me-2"></i>Xem lịch sử đơn hàng
                            </a>
                        </div>
                    </div>
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
</style>
@endsection