<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Chỉ admin mới vào được (middleware check ở Kernel.php)
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Thống kê tổng quan
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Orders::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        
        // Doanh thu
        $paidStatuses = ['đã thanh toán', 'đã đặt (COD)', 'chờ xử lý'];
        $totalRevenue = Orders::whereIn('status', $paidStatuses)->sum('total_price');
        $paidOrders = Orders::whereIn('status', $paidStatuses)->count();
        
        // Đơn hàng gần đây (5 đơn mới nhất)
        $recentOrders = Orders::with('items.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Sản phẩm bán chạy (top 5)
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->select('products.name', 'products.image', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
            
        // Thống kê theo tháng hiện tại
        $currentMonth = now()->format('Y-m');
        $monthlyRevenue = Orders::whereIn('status', $paidStatuses)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])
            ->sum('total_price');
        $monthlyOrders = Orders::whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])->count();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories', 
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'paidOrders',
            'recentOrders',
            'topProducts',
            'monthlyRevenue',
            'monthlyOrders'
        ));
    }

    // ========== QUẢN LÝ DANH MỤC ==========
    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories')->with('success', 'Thêm danh mục thành công!');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Xóa danh mục thành công!');
    }

    // ========== QUẢN LÝ SẢN PHẨM ==========
    public function products()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'power' => 'required|integer',
            'voltage' => 'nullable|integer',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string|max:255',
            'functions' => 'nullable|string',
            'warranty' => 'nullable|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Xử lý ảnh nếu có
        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = uniqid('prd_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $imageName);
        }

        Product::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'capacity' => $request->capacity,
            'power' => $request->power,
            'voltage' => $request->voltage ?? 220,
            'color' => $request->color,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'functions' => $request->functions,
            'warranty' => $request->warranty ?? 12,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('admin.products')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'power' => 'required|integer',
            'voltage' => 'nullable|integer',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string|max:255',
            'functions' => 'nullable|string',
            'warranty' => 'nullable|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Xử lý ảnh nếu có
        $imageName = $product->image;
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                $oldPath = public_path('uploads/products/' . $product->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('image');
            $imageName = uniqid('prd_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $imageName);
        }

        $product->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'capacity' => $request->capacity,
            'power' => $request->power,
            'voltage' => $request->voltage ?? 220,
            'color' => $request->color,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'functions' => $request->functions,
            'warranty' => $request->warranty ?? 12,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('admin.products')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteProduct(Product $product)
    {
        // Xóa ảnh nếu có
        if ($product->image) {
            $path = public_path('uploads/products/' . $product->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Xóa sản phẩm thành công!');
    }
}
