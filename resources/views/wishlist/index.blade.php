@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">💖 Danh sách sản phẩm yêu thích</h2>

    @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($wishlists as $item)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card card-store h-100 d-flex flex-column position-relative">
                {{-- Nút xóa khỏi wishlist --}}
                <form action="{{ route('wishlist.destroy', $item->product->id) }}" method="POST"
                    class="position-absolute top-0 end-0 m-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </form>

                {{-- Ảnh sản phẩm --}}
                <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none text-dark">
                    @if($item->product->image)
                    <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                        class="card-img-top"
                        alt="{{ $item->product->name }}"
                        style="height: 200px; object-fit: cover;">
                    @else
                    <div class="d-flex align-items-center justify-content-center bg-light"
                        style="height:200px; border-bottom:1px solid #eee;">
                        <span class="text-muted">Không có ảnh</span>
                    </div>
                    @endif
                </a>

                {{-- Thông tin sản phẩm --}}
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $item->product->name }}</h5>
                    <p class="fw-bold text-success mb-1">
                        {{ number_format($item->product->price, 0, ',', '.') }} VND
                    </p>
                    <p class="text-muted mb-1">Dung tích: {{ $item->product->capacity ?? 'Không rõ' }} lít</p>
                    <p class="text-muted mb-1">Bảo hành: {{ $item->product->warranty ?? 'Không rõ' }} tháng</p>
                    <p class="small text-secondary mb-1">Kho: {{ $item->product->stock }}</p>
                    <p class="badge bg-light text-dark">
                        {{ $item->product->category->name ?? 'Chưa phân loại' }}
                    </p>

                    {{-- Nút thêm vào giỏ hàng --}}
                    <div class="mt-auto">
                        <form action="{{ route('user.cart.add', $item->product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-store w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Thêm vào giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-muted">Bạn chưa yêu thích sản phẩm nào.</p>
        @endforelse
    </div>
</div>
@endsection