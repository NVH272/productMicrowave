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

        .chatbox {
            position: fixed;
            bottom: -500px;
            /* ẩn dưới màn hình */
            right: 20px;
            width: 320px;
            height: 420px;
            z-index: 1100;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            transition: bottom 0.15s ease-in-out;
            /* hiệu ứng trượt */
        }

        .chatbox.show {
            bottom: 20px;
            /* trượt lên khi mở */
        }
    </style>
</head>

<body>
    @php
    // Lấy ID admin đầu tiên (null nếu chưa có admin nào)
    $chatAdminId = \App\Models\User::where('role', 'admin')->value('id');
    @endphp
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
                            Giỏ hàng
                            <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span> -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wishlist.index') }}">Wishlist</a>
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

    <!-- Nút chat nổi -->
    <div id="chat-toggle"
        class="btn btn-lg rounded-circle d-flex align-items-center justify-content-center"
        style="background-color:#0084FF; color:#fff; position:fixed; bottom:20px; right:20px; width:60px; height:60px; z-index:1050; box-shadow:0 4px 10px rgba(0,0,0,0.2); cursor:pointer;">
        <i class="fas fa-comment-dots fa-lg"></i>
    </div>

    <!-- Chatbox -->
    <div id="chat-box-container" class="chatbox card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>💬 Hỗ trợ</span>
            <button id="chat-close" class="btn btn-sm btn-light">×</button>
        </div>

        <div class="card-body" id="chat-box" style="overflow-y: auto; flex: 1;">
            <div id="chat-messages">
                {{-- Render messages --}}
            </div>
        </div>

        <div class="card-footer">
            <form id="chat-form" action="{{ route('chat.send') }}" method="POST" class="d-flex">
                @csrf
                <input type="hidden" id="chat-receiver" name="receiver_id" value="{{ $chatAdminId }}">
                <input type="text" name="message" class="form-control me-2" placeholder="Nhập tin nhắn..." required>
                <button class="btn btn-primary">Gửi</button>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatToggle = document.getElementById("chat-toggle");
            const chatBox = document.getElementById("chat-box-container");
            const chatClose = document.getElementById("chat-close");
            const chatMessages = document.getElementById("chat-messages");
            const chatForm = document.getElementById("chat-form");

            // ===== Hàm cuộn xuống cuối =====
            function scrollToBottom(smooth = true) {
                if (smooth) {
                    chatMessages.scrollTo({
                        top: chatMessages.scrollHeight,
                        behavior: "smooth"
                    });
                } else {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }

            // ===== Load tin nhắn =====
            function loadMessages() {
                fetch("{{ route('chat.index') }}", {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        chatMessages.innerHTML = html;
                        scrollToBottom(false); // cuộn ngay lập tức xuống cuối
                    });
            }

            // ===== Hàm cuộn xuống cuối =====
            function scrollToBottom(smooth = true) {
                if (smooth) {
                    chatMessages.scrollTo({
                        top: chatMessages.scrollHeight,
                        behavior: "smooth"
                    });
                } else {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }

            // ===== Mở chat =====
            chatToggle.addEventListener("click", function() {
                chatBox.classList.add("show");
                chatToggle.style.display = "none";

                loadMessages();

                // Đợi loadMessages() render xong rồi cuộn
                setTimeout(() => scrollToBottom(false), 300);
            });


            // ===== Đóng chat =====
            chatClose.addEventListener("click", function() {
                chatBox.classList.remove("show");
                chatToggle.style.display = "flex";
            });

            // ===== Gửi tin nhắn AJAX =====
            chatForm.addEventListener("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(chatForm);

                fetch("{{ route('chat.send') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            chatForm.reset();
                            loadMessages();
                            setTimeout(() => scrollToBottom(true), 100); // cuộn mượt sau khi gửi
                        }
                    })
                    .catch(err => console.error("Lỗi gửi tin:", err));
            });

            // ===== Tự refresh tin nhắn mỗi 5 giây =====
            setInterval(() => {
                if (chatBox.classList.contains("show")) {
                    loadMessages();
                }
            }, 5000);
        });
    </script>




    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} - NVH Store | E-Commerce Style</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>