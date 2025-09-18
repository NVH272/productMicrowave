@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Chi tiết sản phẩm</h4>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-5">
                    <div class="d-flex align-items-center justify-content-center w-100 h-100" style="min-height: 400px;">
                        @if($product->image)
                        <img src="{{ asset('uploads/products/'.$product->image) }}"
                            class="img-fluid rounded shadow-sm"
                            alt="{{ $product->name }}"
                            style="max-height: 400px; object-fit: contain;">
                        @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded w-100"
                            style="height: 400px; border:1px solid #ddd;">
                            <span class="text-muted">Không có ảnh</span>
                        </div>
                        @endif
                    </div>
                </div>


                <!-- Thông tin sản phẩm -->
                <div class="col-md-7">
                    <h3 class="fw-bold">{{ $product->name }}</h3>
                    @php
                        $avg = round($product->reviews->avg('rating') ?? 0, 1);
                        $count = $product->reviews->count();
                    @endphp
                    <div class="mb-2">
                        <span class="me-2">Đánh giá trung bình:</span>
                        <strong class="text-warning">{{ $avg }} / 5</strong>
                        <span class="text-muted">({{ $count }} đánh giá)</span>
                    </div>
                    <p class="text-muted mb-1">Thương hiệu: {{ $product->brand ?? 'N/A' }}</p>
                    <p class="text-muted mb-1">Model: {{ $product->model ?? 'N/A' }}</p>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Dung tích:</strong> {{ $product->capacity ?? 'N/A' }}</p>
                            <p><strong>Công suất:</strong> {{ $product->power ?? 'N/A' }}</p>
                            <p><strong>Điện áp:</strong> {{ $product->voltage ?? 'N/A' }}</p>
                            <p><strong>Màu sắc:</strong> {{ $product->color ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>Trọng lượng:</strong> {{ $product->weight ?? 'N/A' }}</p>
                            <p><strong>Kích thước:</strong> {{ $product->dimensions ?? 'N/A' }}</p>
                            <p><strong>Bảo hành:</strong> {{ $product->warranty ?? 'N/A' }} tháng</p>
                            <p><strong>Kho:</strong> {{ $product->stock }}</p>
                        </div>
                    </div>

                    <p><strong>Chức năng:</strong> {{ $product->functions ?? 'N/A' }}</p>
                    <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>

                    <h4 class="text-danger fw-bold mt-3">
                        {{ number_format($product->price, 0, ',', '.') }} VND
                    </h4>

                    <!-- Nút thêm vào giỏ -->
                    @auth
                    <form action="{{ route('user.cart.add', $product->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-primary w-100">
                            🛒 Thêm vào giỏ hàng
                        </button>
                    </form>
                    @else
                    <div class="alert alert-warning mt-3">
                        Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để thêm sản phẩm vào giỏ hàng.
                    </div>
                    @endauth
                    <hr>
                    <!-- <h3>Đánh giá sản phẩm</h3> -->

                    <!-- @auth
                    <div class="alert alert-info">
                        Chỉ khách đã mua và nhận hàng thành công mới có thể đánh giá.
                        Hãy vào phần <a href="{{ route('user.orders.index') }}">Lịch sử đơn hàng</a> để đánh giá.
                    </div>
                    @endauth -->

                    <h4>Danh sách đánh giá:</h4>
                    @foreach($product->reviews as $review)
                    <div class="review border p-2 my-2">
                        <strong>{{ $review->user->name }}</strong> - {{ $review->rating }}⭐
                        <p>{{ $review->comment }}</p>
                        @if($review->admin_reply)
                        <div class="bg-light p-2 border rounded">
                            <strong>Phản hồi từ Admin:</strong>
                            <div>{{ $review->admin_reply }}</div>
                        </div>
                        @elseif(auth()->check() && auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('admin.reviews.reply', $review) }}" class="mt-2">
                            @csrf
                            <div class="mb-2">
                                <textarea name="admin_reply" class="form-control" rows="2" placeholder="Nhập phản hồi của admin..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Gửi phản hồi</button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection