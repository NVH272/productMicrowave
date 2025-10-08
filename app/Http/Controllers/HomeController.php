<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $newProducts = Product::orderBy('created_at', 'desc')->take(8)->get();

        $popularProducts = Product::withCount('wishlists')
            ->orderByDesc('wishlists_count')
            ->take(8)
            ->get();

        return view('home', compact('categories', 'newProducts', 'popularProducts'));
    }
}
