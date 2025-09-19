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

        // Kiểm tra user đã mua và đơn hàng đã giao
        $hasBought = OrderItems::where('product_id', $product->id)
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id())
                    ->where('shipping_status', 'đã giao');
            })
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua khi đơn hàng đã được giao.');
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

}
