<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // public function index()
    // {
    //     $wishlists = Wishlist::with('product')
    //         ->where('user_id', Auth::id())
    //         ->get();

    //     return view('wishlist.index', compact('wishlists'));
    // }

    public function store(Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return back();
    }

    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back();
    }
    public function index()
    {
        $wishlists = Wishlist::with('product.category')
            ->where('user_id', auth()->id())
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
