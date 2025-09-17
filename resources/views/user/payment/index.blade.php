@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">💳 Thanh toán</h2>

    {{-- Hiển thị giỏ hàng --}}
    @php
    $cart = session()->get('cart', []);
    $total = 0;
    @endphp

    @if(count($cart) > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🛒 Giỏ hàng của bạn</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $item)
                        @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if(isset($item['image']))
                                            <img src="{{ asset('uploads/products/' . $item['image']) }}" 
                                                 alt="{{ $item['name'] }}" 
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
                                        <strong>{{ $item['name'] }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">{{ $item['category'] }}</span></td>
                            <td class="text-end">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-end fw-bold">{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Tổng cộng:</th>
                            <th class="text-end text-primary fs-5">{{ number_format($total, 0, ',', '.') }} đ</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Form thanh toán --}}
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">📝 Thông tin giao hàng & Thanh toán</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('user.payment.process') }}" method="POST">
                @csrf
                <input type="hidden" name="total_price" value="{{ $total }}">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Họ tên người nhận</label>
                            <input type="text" name="name" id="name" class="form-control" required 
                                   placeholder="Nhập họ tên đầy đủ">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" required 
                                   placeholder="Nhập số điện thoại">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label fw-bold">Địa chỉ giao hàng</label>
                    <textarea name="address" id="address" class="form-control" rows="3" required 
                              placeholder="Nhập địa chỉ chi tiết"></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">💳 Phương thức thanh toán</label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check border rounded p-3 h-100">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                                        <div>
                                            <strong>Thanh toán khi nhận hàng (COD)</strong>
                                            <br><small class="text-muted">Thanh toán tiền mặt khi nhận hàng</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check border rounded p-3 h-100">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="momo" value="momo">
                                <label class="form-check-label" for="momo">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-mobile-alt text-danger me-2"></i>
                                        <div>
                                            <strong>Ví MoMo</strong>
                                            <br><small class="text-muted">Thanh toán nhanh chóng qua ví MoMo</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check border rounded p-3 h-100">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="bank" value="bank">
                                <label class="form-check-label" for="bank">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-university text-primary me-2"></i>
                                        <div>
                                            <strong>Chuyển khoản ngân hàng</strong>
                                            <br><small class="text-muted">Chuyển khoản qua tài khoản ngân hàng</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('user.cart.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check me-2"></i>Xác nhận đặt hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-info text-center">
        <i class="fas fa-shopping-cart fa-2x mb-3"></i>
        <h5>Giỏ hàng của bạn đang trống.</h5>
        <a href="{{ route('home') }}" class="btn btn-primary mt-2">
            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    @endif
</div>

<style>
.form-check-input:checked + .form-check-label .border {
    border-color: #198754 !important;
    background-color: #f8fff9;
}
.form-check-input:checked + .form-check-label {
    color: #198754;
}
</style>
@endsection