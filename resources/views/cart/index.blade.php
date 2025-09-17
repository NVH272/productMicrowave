@extends('layouts.user')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h2>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Danh mục</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if(isset($item['image']) && $item['image'])
                                                <img src="{{ asset('uploads/products/' . $item['image']) }}" 
                                                     alt="{{ $item['name'] }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                                            <small class="text-muted">ID: {{ $id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item['category'] }}</span>
                                </td>
                                <td class="text-center">{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99"
                                                   class="form-control text-center quantity-input" 
                                                   data-product-id="{{ $id }}"
                                                   data-price="{{ $item['price'] }}">
                                            <button type="button" class="btn btn-outline-primary btn-sm update-quantity-btn"
                                                    data-product-id="{{ $id }}">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-danger btn-sm remove-item-btn" 
                                            data-product-id="{{ $id }}"
                                            data-product-name="{{ $item['name'] }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                @php $total += $item['price'] * $item['quantity']; @endphp
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Tổng cộng:</th>
                                <th class="text-end text-primary fs-5 cart-total">{{ number_format($total, 0, ',', '.') }} đ</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button class="btn btn-warning clear-cart-btn">
                        <i class="fas fa-trash me-2"></i>Xóa toàn bộ giỏ hàng
                    </button>

                    <a href="{{ route('user.payment.index') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-credit-card me-2"></i>Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Giỏ hàng của bạn đang trống</h5>
            <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    @endif

    {{-- Lịch sử đơn hàng gần đây --}}
    @if(isset($recentOrders) && $recentOrders->count() > 0)
        <div class="mt-5">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Lịch sử đơn hàng gần đây
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentOrders as $order)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-receipt me-1"></i>Đơn #{{ $order->id }}
                                        </h6>
                                        <span class="{{ $order->payment_status_badge_class }} small">
                                            {{ $order->payment_status_text }}
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong class="text-primary">{{ number_format($order->total_price, 0, ',', '.') }} đ</strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-box me-1"></i>
                                            {{ $order->items->count() }} sản phẩm
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('user.orders.show', $order) }}" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                        
                                        @if($order->payment_status === 'pending' && $order->payment_method === 'momo')
                                            <a href="{{ route('user.orders.momo.pay', $order) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>Xem tất cả đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.card {
    border: none;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.badge {
    font-size: 0.8rem;
}
.input-group-sm .form-control {
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token cho AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Cập nhật số lượng sản phẩm
    document.querySelectorAll('.update-quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
            const quantity = quantityInput.value;
            
            if (quantity < 1 || quantity > 99) {
                alert('Số lượng phải từ 1 đến 99');
                return;
            }
            
            updateQuantity(productId, quantity);
        });
    });
    
    // Xóa sản phẩm khỏi giỏ hàng
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            
            if (confirm(`Bạn có chắc muốn xóa "${productName}" khỏi giỏ hàng?`)) {
                removeItem(productId);
            }
        });
    });
    
    // Xóa toàn bộ giỏ hàng
    document.querySelector('.clear-cart-btn')?.addEventListener('click', function() {
        if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
            clearCart();
        }
    });
    
    // Enter để cập nhật số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const productId = this.getAttribute('data-product-id');
                const quantity = this.value;
                
                if (quantity >= 1 && quantity <= 99) {
                    updateQuantity(productId, quantity);
                } else {
                    alert('Số lượng phải từ 1 đến 99');
                }
            }
        });
    });
    
    function updateQuantity(productId, quantity) {
        fetch(`/user/cart/update/${productId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật tổng tiền của sản phẩm
                const row = document.querySelector(`input[data-product-id="${productId}"]`).closest('tr');
                const priceCell = row.querySelector('td:nth-child(3)');
                const totalCell = row.querySelector('td:nth-child(5)');
                const price = parseFloat(priceCell.textContent.replace(/[^\d]/g, ''));
                
                totalCell.textContent = data.item_total + ' đ';
                document.querySelector('.cart-total').textContent = data.cart_total + ' đ';
                
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Có lỗi xảy ra khi cập nhật số lượng');
        });
    }
    
    function removeItem(productId) {
        fetch(`/user/cart/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Xóa dòng sản phẩm khỏi bảng
                const row = document.querySelector(`input[data-product-id="${productId}"]`).closest('tr');
                row.remove();
                
                // Cập nhật tổng tiền
                document.querySelector('.cart-total').textContent = data.cart_total + ' đ';
                
                // Nếu giỏ hàng trống, reload trang
                if (data.cart_count === 0) {
                    location.reload();
                }
                
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Có lỗi xảy ra khi xóa sản phẩm');
        });
    }
    
    function clearCart() {
        fetch('/user/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Có lỗi xảy ra khi xóa giỏ hàng');
        });
    }
    
    function showAlert(type, message) {
        // Tạo alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Thêm vào đầu container
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
@endsection