@extends('layouts.app')

@section('content')
<style>
    .reviews-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 30px;
    }

    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .filter-tab:not(.active) {
        background: white;
        color: #666;
        border-color: #ddd;
    }

    .filter-tab:hover:not(.active) {
        border-color: #667eea;
        color: #667eea;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .review-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .review-card.unread {
        border-left: 4px solid #e74c3c;
    }

    .review-card.replied {
        border-left: 4px solid #27ae60;
    }

    .review-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .review-info {
        flex: 1;
    }

    .review-user {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .review-product {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .review-rating {
        display: flex;
        gap: 2px;
        margin-bottom: 10px;
    }

    .star {
        color: #f5a623;
        font-size: 18px;
    }

    .review-comment {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        font-style: italic;
    }

    .review-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-reply {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-reply:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-mark-read {
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9rem;
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9rem;
    }

    .admin-reply {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
    }

    .admin-reply-header {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .pagination {
        justify-content: center;
        margin-top: 30px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>

<div class="reviews-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-star me-2"></i>Quản lý Đánh giá</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('admin.reviews.index', ['filter' => 'all']) }}" 
           class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
            Tất cả ({{ $totalReviews }})
        </a>
        <a href="{{ route('admin.reviews.index', ['filter' => 'unread']) }}" 
           class="filter-tab {{ $filter === 'unread' ? 'active' : '' }}">
            Chưa đọc ({{ $unreadReviews }})
        </a>
        <a href="{{ route('admin.reviews.index', ['filter' => 'read']) }}" 
           class="filter-tab {{ $filter === 'read' ? 'active' : '' }}">
            Đã đọc
        </a>
        <a href="{{ route('admin.reviews.index', ['filter' => 'replied']) }}" 
           class="filter-tab {{ $filter === 'replied' ? 'active' : '' }}">
            Đã phản hồi ({{ $repliedReviews }})
        </a>
        <a href="{{ route('admin.reviews.index', ['filter' => 'unreplied']) }}" 
           class="filter-tab {{ $filter === 'unreplied' ? 'active' : '' }}">
            Chưa phản hồi
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-number text-primary">{{ $totalReviews }}</div>
            <div class="stat-label">Tổng đánh giá</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-danger">{{ $unreadReviews }}</div>
            <div class="stat-label">Chưa đọc</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-success">{{ $repliedReviews }}</div>
            <div class="stat-label">Đã phản hồi</div>
        </div>
    </div>

    <!-- Reviews List -->
    @if($reviews->count() > 0)
        @foreach($reviews as $review)
        <div class="review-card {{ !$review->admin_seen_at ? 'unread' : '' }} {{ $review->admin_reply ? 'replied' : '' }}">
            <div class="review-header">
                <div class="review-info">
                    <div class="review-user">{{ $review->user->name }}</div>
                    <div class="review-product">
                        <i class="fas fa-box me-1"></i>{{ $review->product->name }}
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star' }}"></span>
                        @endfor
                        <span class="ms-2 text-muted">{{ $review->rating }}/5 sao</span>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>{{ $review->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            @if($review->comment)
            <div class="review-comment">
                <i class="fas fa-quote-left me-2"></i>{{ $review->comment }}
            </div>
            @endif

            @if($review->admin_reply)
            <div class="admin-reply">
                <div class="admin-reply-header">
                    <i class="fas fa-reply me-2"></i>Phản hồi từ Admin
                    @if($review->admin)
                        ({{ $review->admin->name }})
                    @endif
                </div>
                <div>{{ $review->admin_reply }}</div>
                <small class="opacity-75">
                    <i class="fas fa-clock me-1"></i>{{ $review->replied_at->format('d/m/Y H:i') }}
                </small>
            </div>
            @endif

            <div class="review-actions">
                @if(!$review->admin_reply)
                <button type="button" class="btn-reply" data-bs-toggle="modal" data-bs-target="#replyModal{{ $review->id }}">
                    <i class="fas fa-reply me-1"></i>Phản hồi
                </button>
                @endif

                @if(!$review->admin_seen_at)
                <form action="{{ route('admin.reviews.mark-read', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-mark-read">
                        <i class="fas fa-check me-1"></i>Đánh dấu đã đọc
                    </button>
                </form>
                @endif

                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="fas fa-trash me-1"></i>Xóa
                    </button>
                </form>
            </div>
        </div>

        <!-- Reply Modal -->
        <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Phản hồi đánh giá</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Khách hàng:</label>
                                <div class="form-control-plaintext">{{ $review->user->name }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sản phẩm:</label>
                                <div class="form-control-plaintext">{{ $review->product->name }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đánh giá:</label>
                                <div class="form-control-plaintext">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star' }}"></span>
                                    @endfor
                                    {{ $review->rating }}/5 sao
                                </div>
                            </div>
                            @if($review->comment)
                            <div class="mb-3">
                                <label class="form-label">Nhận xét:</label>
                                <div class="form-control-plaintext">{{ $review->comment }}</div>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="admin_reply" class="form-label">Phản hồi của bạn:</label>
                                <textarea name="admin_reply" id="admin_reply" class="form-control" rows="4" 
                                          placeholder="Nhập phản hồi của bạn..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-star"></i>
            <h4>Không có đánh giá nào</h4>
            <p>Chưa có đánh giá nào phù hợp với bộ lọc hiện tại.</p>
        </div>
    @endif
</div>

@endsection
