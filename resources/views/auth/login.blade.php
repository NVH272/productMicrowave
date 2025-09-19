{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Hiển thị thông báo session --}}
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient text-white"
                    style="background: linear-gradient(135deg, #ff9a9e, #fad0c4);">
                    <h4 class="mb-0" style="color: black;">Đăng nhập</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn w-100 text-white fw-bold"
                            style="background: linear-gradient(135deg, #ff9a9e, #fad0c4); border: none; hover: background: rgb(248, 6, 14);">
                            Đăng nhập
                        </button>
                    </form>
                    <p class="text-center mt-3">
                        Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection