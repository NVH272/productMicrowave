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
@endsection