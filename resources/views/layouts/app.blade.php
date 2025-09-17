<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lò vi sóng NVH - E-Commerce Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* 🎨 Custom Palette */
        :root {
            --brand-green: #00c4b3;
            --brand-orange: #ff6b35;
            --brand-dark: #1a1a1a;
            --brand-light: #f5f7fa;
            --brand-text: #333;
        }

        body {
            background: var(--brand-light);
            color: var(--brand-text);
            font-family: 'Segoe UI', Tahoma, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== Navbar ===== */
        .navbar-store {
            background: var(--brand-green);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: white !important;
            letter-spacing: 1px;
        }

        .search-bar {
            flex-grow: 1;
        }

        .search-bar input {
            border-radius: 30px;
            padding: 0.6rem 1rem;
            border: none;
        }

        .search-bar button {
            border-radius: 50px;
            background: var(--brand-orange);
            color: #fff;
            padding: 0.6rem 1.2rem;
            border: none;
        }

        /* ===== Navbar Links ===== */
        .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        /* ===== Buttons ===== */
        .btn-store {
            background: var(--brand-orange);
            color: #fff;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            transition: 0.3s;
            border: none;
        }

        .btn-store:hover {
            background: #ff874f;
        }

        /* ===== Cards ===== */
        .card-store {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #eee;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .card-store:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        /* ===== Footer ===== */
        footer {
            background: var(--brand-dark);
            color: #ccc;
            padding: 1.5rem;
            text-align: center;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        /* ===== Section Title ===== */
        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--brand-green);
            border-left: 6px solid var(--brand-orange);
            padding-left: 0.6rem;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-store py-3">
        <div class="container-fluid container-lg">
            <a class="navbar-brand" href="{{ url('/') }}">🛒 NVH Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search -->
                <form class="d-flex ms-lg-4 search-bar">
                    <input class="form-control me-2" type="search" placeholder="Tìm kiếm sản phẩm...">
                    <!-- <button class="btn" type="submit">Tìm</button> -->
                </form>

                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Danh mục</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('user.cart.index') }}">
                            🛍️ Giỏ hàng
                            <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span> -->
                        </a>
                    </li>

                    @guest
                    <li class="nav-item">
                        <a class="btn btn-store" href="{{ route('login') }}">Đăng nhập / Đăng ký</a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item fw-semibold" href="{{ route('admin.dashboard') }}">
                                    Quản trị Dashboard
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="container my-5 flex-grow-1">
        <!-- <h2 class="section-title mb-4">✨ Chào mừng đến với NVH Store</h2> -->
        <div class="row g-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} - NVH Store | E-Commerce Style</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>