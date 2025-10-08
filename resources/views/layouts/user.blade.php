{{-- resources/views/user.blade.php --}}
@extends('layouts.app')

@section('title', 'Trang người dùng')

@section('content')
<div class="text-center">
    <h1 class="mb-4">👤 Trang Người Dùng</h1>
    <p>Chào mừng <strong>{{ Auth::user()->name ?? 'Khách' }}</strong> đến với MyShop 💖</p>

    <div class="mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-custom me-2">
            <i class="bi bi-bag"></i> Sản phẩm
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-custom me-2">
            <i class="bi bi-grid"></i> Danh mục
        </a>
        <a href="{{ route('logout') }}" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </div>
</div>

{{-- Nút chat nổi --}}
<a href="{{ route('chat.index') }}"
    class="btn btn-primary rounded-circle shadow-lg"
    style="position: fixed; bottom: 20px; left: 20px; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; z-index: 1000;">
    <i class="bi bi-chat-dots"></i>
</a>
@endsection