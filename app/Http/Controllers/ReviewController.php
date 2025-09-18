<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\OrderItems;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá sản phẩm
     */
    public function store(Request $request, Product $product)
    {
        // Validate input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra user đã mua và đơn hàng đã giao + đã thanh toán
        $hasBought = OrderItems::where('product_id', $product->id)
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id())
                    ->where('payment_status', 'paid')
                    ->where('shipping_status', 'đã giao');
            })
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua và giao thành công.');
        }

        // Kiểm tra user đã từng đánh giá sản phẩm này chưa
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        // Tạo review
        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    /**
     * Admin trả lời đánh giá
     */
    public function reply(Request $request, Review $review)
    {
        // Chỉ admin mới được trả lời (middleware đã chặn, đây chỉ là phòng hờ)
        if (!auth()->user() || !method_exists(auth()->user(), 'isAdmin') || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);

        $review->admin_reply = $request->admin_reply;
        $review->admin_id = auth()->id();
        $review->replied_at = now();
        $review->save();

        return back()->with('success', 'Đã trả lời đánh giá.');
    }
}
