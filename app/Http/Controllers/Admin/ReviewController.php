<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'admin'])->orderBy('created_at', 'desc');
        
        // Filter by status
        $filter = $request->get('filter', 'all');
        switch ($filter) {
            case 'unread':
                $query->whereNull('admin_seen_at');
                break;
            case 'read':
                $query->whereNotNull('admin_seen_at');
                break;
            case 'replied':
                $query->whereNotNull('admin_reply');
                break;
            case 'unreplied':
                $query->whereNull('admin_reply');
                break;
        }
        
        $reviews = $query->paginate(15);
        
        // Statistics
        $totalReviews = Review::count();
        $unreadReviews = Review::whereNull('admin_seen_at')->count();
        $repliedReviews = Review::whereNotNull('admin_reply')->count();
        
        return view('admin.reviews.index', compact('reviews', 'totalReviews', 'unreadReviews', 'repliedReviews', 'filter'));
    }
    
    public function show(Review $review)
    {
        // Mark as seen when admin views the review
        if (!$review->admin_seen_at) {
            $review->update(['admin_seen_at' => now()]);
        }
        
        $review->load(['user', 'product', 'admin']);
        return view('admin.reviews.show', compact('review'));
    }
    
    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);
        
        $review->update([
            'admin_reply' => $request->admin_reply,
            'admin_id' => auth()->id(),
            'replied_at' => now(),
            'admin_seen_at' => now() // Mark as seen when replying
        ]);
        
        return redirect()->route('admin.reviews.index')->with('success', 'Đã phản hồi đánh giá thành công!');
    }
    
    public function markAsRead(Review $review)
    {
        $review->update(['admin_seen_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')->with('success', 'Đã xóa đánh giá thành công!');
    }
}
