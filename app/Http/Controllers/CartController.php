<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Lấy lịch sử đơn hàng gần đây (5 đơn hàng mới nhất)
        $recentOrders = [];
        if (Auth::check()) {
            $recentOrders = Orders::where('user_id', Auth::id())
                ->with('items.product')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }
        
        return view('cart.index', compact('cart', 'recentOrders'));
    }
    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "id" => $product->id, // Thêm ID sản phẩm
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "category" => $product->category->name,
                "image" => $product->image // Thêm hình ảnh sản phẩm
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    // Xoá sản phẩm khỏi giỏ hàng
    public function remove(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
            
            // Tính tổng tiền toàn bộ giỏ hàng sau khi xóa
            $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $cartCount = count($cart);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được xoá khỏi giỏ hàng.',
                    'cart_total' => number_format($cartTotal, 0, ',', '.'),
                    'cart_count' => $cartCount,
                    'product_id' => $product->id
                ]);
            }
            
            return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được xoá khỏi giỏ hàng.');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
            ], 404);
        }
        
        return redirect()->route('user.cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            // Tính tổng tiền cho sản phẩm này
            $itemTotal = $cart[$product->id]['price'] * $cart[$product->id]['quantity'];
            
            // Tính tổng tiền toàn bộ giỏ hàng
            $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Số lượng sản phẩm đã được cập nhật.',
                    'item_total' => number_format($itemTotal, 0, ',', '.'),
                    'cart_total' => number_format($cartTotal, 0, ',', '.'),
                    'quantity' => $cart[$product->id]['quantity']
                ]);
            }
            
            return redirect()->route('user.cart.index')->with('success', 'Số lượng sản phẩm đã được cập nhật.');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
            ], 404);
        }
        
        return redirect()->route('user.cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear(Request $request)
    {
        session()->forget('cart');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Giỏ hàng đã được xóa.',
                'cart_count' => 0,
                'cart_total' => '0'
            ]);
        }
        
        return redirect()->route('user.cart.index')->with('success', 'Giỏ hàng đã được xóa.');
    }

    public function processPayment(Request $request)
    {
        // Lấy dữ liệu từ form
        $data = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        // Lấy giỏ hàng hiện tại
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Giả lập tạo đơn hàng
        $orderId = rand(1000, 9999); // tạm tạo số ngẫu nhiên

        // Chuẩn bị dữ liệu order để gửi sang trang success
        $orderData = [
            'id' => $orderId,
            'shipping' => $data,
            'items' => $cart,
            'total' => $total,
            'status' => $request->payment_method === 'cod' ? 'Chờ thanh toán (COD)' : 'Chờ thanh toán',
        ];

        // Lưu vào session
        session(['orderData' => $orderData]);

        // Xóa giỏ hàng
        session()->forget('cart');

        // Chuyển sang trang thông báo thành công
        return redirect()->route('user.payment.success', ['orderId' => $orderId]);
    }

    public function success($orderId)
    {
        $order = session('orderData'); // lấy thông tin vừa đặt hàng
        if (!$order) {
            return redirect()->route('user.cart.index')->with('error', 'Không tìm thấy đơn hàng.');
        }
        return view('user.payment.success', compact('orderId', 'order'));
    }
}
