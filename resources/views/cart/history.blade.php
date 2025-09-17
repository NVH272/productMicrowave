@extends('layouts.user')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🧾 Chi tiết đơn hàng #{{ $order->id }}</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Thông tin người nhận</h5>
            <p><strong>Họ tên:</strong> {{ $order->name }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Danh sách sản phẩm</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                    </tr>

                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Tổng đơn hàng:</th>
                        <th>{{ number_format($order->total_price, 0, ',', '.') }} đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <a href="{{ route('user.orders.index') }}" class="btn btn-secondary mt-3">← Quay lại lịch sử đơn</a>
</div>
@endsection