@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Chỉnh sửa đơn hàng #{{ $order->id }}</h1>
            <p class="mb-0 text-muted">Cập nhật thông tin đơn hàng</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Xem chi tiết
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Thông tin đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Tên khách hàng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $order->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="font-weight-bold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $order->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="font-weight-bold">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address', $order->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_price" class="font-weight-bold">Tổng tiền <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('total_price') is-invalid @enderror" 
                                               id="total_price" name="total_price" 
                                               value="{{ old('total_price', $order->total_price) }}" 
                                               min="0" step="1000" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>
                                    @error('total_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="chờ xử lý" {{ old('status', $order->status) == 'chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="đã thanh toán" {{ old('status', $order->status) == 'đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="đã đặt (COD)" {{ old('status', $order->status) == 'đã đặt (COD)' ? 'selected' : '' }}>Đã đặt (COD)</option>
                                        <option value="đang giao hàng" {{ old('status', $order->status) == 'đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                        <option value="đã giao hàng" {{ old('status', $order->status) == 'đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                                        <option value="đã hủy" {{ old('status', $order->status) == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_method" class="font-weight-bold">Phương thức thanh toán <span class="text-danger">*</span></label>
                                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">-- Chọn phương thức --</option>
                                        <option value="cod" {{ old('payment_method', $order->payment_method) == 'cod' ? 'selected' : '' }}>COD (Thanh toán khi nhận hàng)</option>
                                        <option value="momo" {{ old('payment_method', $order->payment_method) == 'momo' ? 'selected' : '' }}>MoMo</option>
                                        <option value="bank" {{ old('payment_method', $order->payment_method) == 'bank' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shipping_status" class="font-weight-bold">Trạng thái giao hàng</label>
                            <select class="form-control @error('shipping_status') is-invalid @enderror" 
                                    id="shipping_status" name="shipping_status">
                                <option value="">-- Chọn trạng thái giao hàng --</option>
                                <option value="packaged" {{ old('shipping_status', $order->shipping_status) == 'packaged' ? 'selected' : '' }}>Đã đóng gói</option>
                                <option value="shipping" {{ old('shipping_status', $order->shipping_status) == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ old('shipping_status', $order->shipping_status) == 'completed' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ old('shipping_status', $order->shipping_status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('shipping_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($order->note)
                        <div class="form-group">
                            <label for="note" class="font-weight-bold">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="3">{{ old('note', $order->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật đơn hàng
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Thông tin bổ sung -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Thông tin hiện tại
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="font-weight-bold">Mã đơn:</td>
                            <td>#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Ngày tạo:</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cập nhật cuối:</td>
                            <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số sản phẩm:</td>
                            <td>{{ $order->items->count() }} sản phẩm</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart"></i> Sản phẩm trong đơn
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->items->count() > 0)
                        @foreach($order->items as $item)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                                <small class="text-muted">
                                    {{ number_format($item->price, 0, ',', '.') }} VNĐ x {{ $item->quantity }}
                                </small>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold text-success">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">Tổng cộng:</span>
                            <span class="font-weight-bold text-success">
                                {{ number_format($order->total_price, 0, ',', '.') }} VNĐ
                            </span>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <p>Không có sản phẩm</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle"></i> Lưu ý
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            <strong>Lưu ý:</strong> Việc thay đổi thông tin đơn hàng có thể ảnh hưởng đến trạng thái thanh toán và giao hàng. 
                            Hãy cẩn thận khi cập nhật.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for order edit page */
.form-group label {
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.input-group-text {
    background-color: #f8f9fc;
    border-color: #d1d3e2;
    color: #5a5c69;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.table-sm td {
    padding: 0.25rem 0;
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn:last-child {
        margin-bottom: 0;
    }
}

/* Form validation styles */
.is-invalid {
    border-color: #e74a3b;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #e74a3b;
}

/* Button styles */
.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-info {
    background-color: #36b9cc;
    border-color: #36b9cc;
}
</style>
@endsection
