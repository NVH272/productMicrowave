<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // ✅ thêm dòng này
use App\Models\Orders;
use App\Models\OrderItems;

class OrderController extends Controller
{
    public function index()
    {
        // Lấy tất cả đơn hàng để hiển thị cho admin
        $orders = Orders::with('items.product')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        // Hiển thị form tạo đơn hàng mới (nếu cần)
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        // Xử lý tạo đơn hàng mới (nếu cần)
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $order = Orders::create($validated);
        
        return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công!');
    }

    public function show($id)
    {
        // Hiển thị chi tiết đơn hàng
        $order = Orders::with('items.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        // Hiển thị form chỉnh sửa đơn hàng
        $order = Orders::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Orders::findOrFail($id);

        // Nếu là AJAX request chỉ cập nhật shipping_status
        if ($request->ajax() || $request->has('shipping_status')) {
            $request->validate([
                'shipping_status' => 'required|in:pending,packaged,shipping,đã giao,cancelled'
            ]);

            $oldShippingStatus = $order->shipping_status;
            $order->update([
                'shipping_status' => $request->shipping_status
            ]);

            // Logic COD: Nếu đơn hàng COD và trạng thái giao hàng chuyển thành 'đã giao' thì tự động cập nhật payment_status thành 'paid'
            if ($order->payment_method === 'cod' && 
                $oldShippingStatus !== 'đã giao' && 
                $request->shipping_status === 'đã giao' && 
                $order->payment_status === 'pending') {
                
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật trạng thái giao hàng thành công!',
                    'shipping_status' => $order->shipping_status,
                    'payment_status' => $order->payment_status
                ]);
            }

            return redirect()->route('admin.orders.index')->with('success', 'Cập nhật trạng thái giao hàng thành công!');
        }

        // Cập nhật đầy đủ thông tin đơn hàng
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'shipping_status' => 'nullable|in:pending,packaged,shipping,đã giao,cancelled',
            'note' => 'nullable|string|max:500'
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,cancelled'
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'paid_at' => $request->payment_status === 'paid' ? now() : null
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thanh toán thành công!',
                'payment_status' => $order->payment_status
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }

    public function destroy($id)
    {
        // Xóa đơn hàng
        $order = Orders::findOrFail($id);
        
        // Xóa các order items trước
        OrderItems::where('order_id', $id)->delete();
        
        // Xóa đơn hàng
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
    }
}
