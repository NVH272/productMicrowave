@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Hero Section --}}
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-5 fw-bold">Chào mừng đến với NVH Store</h1>
            <p class="col-md-8 fs-5 mx-auto">
                Cửa hàng điện tử uy tín - chất lượng. Mua sắm dễ dàng, giao hàng nhanh chóng, dịch vụ tận tâm.
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-shopping-bag me-2"></i> Mua sắm ngay
            </a>
        </div>
    </div>

    {{-- Danh mục nổi bật --}}
    <h2 class="section-title mb-4">Danh mục nổi bật</h2>
    <div class="row text-center mb-5">
        @foreach($categories as $category)
        <div class="col-md-3 mb-3">
            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-tags fa-2x text-primary mb-2"></i>
                        <h5 class="card-title">{{ $category->name }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- Sản phẩm mới nhất --}}
    <h2 class="section-title mb-4">🆕 Sản phẩm mới</h2>
    <div class="row">
        @foreach($newProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset('uploads/products/' . $product->image) }}"
                        class="card-img-top"
                        style="height: 200px; object-fit: cover;"
                        alt="{{ $product->name }}">
                </a>
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="fw-bold text-success">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-primary">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Sản phẩm yêu thích nhiều nhất (nếu có dữ liệu wishlist) --}}
    <h2 class="section-title mb-4">💖 Sản phẩm được yêu thích</h2>
    <div class="row">
        @forelse($popularProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset('uploads/products/' . $product->image) }}"
                        class="card-img-top"
                        style="height: 200px; object-fit: cover;"
                        alt="{{ $product->name }}">
                </a>
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="fw-bold text-success">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-heart"></i> Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted">Chưa có sản phẩm nào được yêu thích.</p>
        @endforelse
    </div>
</div>
@endsection