@extends('layouts.app')

@section('content')
<h2 class="section-title mb-4">Danh sách sản phẩm</h2>

@if(session('success'))
<div class="alert alert-success text-center">{{ session('success') }}</div>
@endif

@forelse($products as $product)
<div class="col-md-4 col-lg-3 mb-4">
    <div class="card card-store h-100 d-flex flex-column">
        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
            @if($product->image)
            <img src="{{ asset('uploads/products/' . $product->image) }}"
                class="card-img-top"
                alt="{{ $product->name }}"
                style="height: 200px; object-fit: cover;">
            @else
            <div class="d-flex align-items-center justify-content-center bg-light"
                style="height:200px; border-bottom:1px solid #eee;">
                <span class="text-muted">Không có ảnh</span>
            </div>
            @endif
            @auth
            @php
            $inWishlist = auth()->user()->wishlist()
            ->where('product_id', $product->id)
            ->exists();
            @endphp

            @if($inWishlist)
            <!-- Nút xóa khỏi wishlist -->
            <form action="{{ route('wishlist.destroy', $product->id) }}" method="POST"
                class="position-absolute top-0 end-0 m-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                    <i class="fas fa-heart text-danger"></i> {{-- trái tim đỏ --}}
                </button>
            </form>
            @else
            <!-- Nút thêm vào wishlist -->
            <form action="{{ route('wishlist.store', $product->id) }}" method="POST"
                class="position-absolute top-0 end-0 m-2">
                @csrf
                <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                    <i class="far fa-heart text-danger"></i> {{-- trái tim viền --}}
                </button>
            </form>
            @endif
            @endauth

        </a>

        <div class="card-body text-center d-flex flex-column">
            <h5 class="card-title fw-bold">{{ $product->name }}</h5>
            <p class="fw-bold text-success mb-1">
                {{ number_format($product->price, 0, ',', '.') }} VND
            </p>
            <p class="text-muted mb-1">Dung tích: {{ $product->capacity ?? 'Không rõ' }} lít</p>
            <p class="text-muted mb-1">Bảo hành: {{ $product->warranty ?? 'Không rõ' }} tháng</p>
            <p class="small text-secondary mb-1">Kho: {{ $product->stock }}</p>
            <p class="badge bg-light text-dark">
                {{ $product->category->name ?? 'Chưa phân loại' }}
            </p>

            @auth
            <!-- Nút luôn nằm cuối -->
            <div class="mt-auto">
                <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-store w-100">
                        Thêm vào giỏ hàng
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>

@empty
<p class="text-center">Chưa có sản phẩm nào.</p>
@endforelse
@endsection