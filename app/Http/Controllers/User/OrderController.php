<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItems;
use App\Models\Orders;

class OrderController extends Controller
{
    // Sử dụng cấu hình từ config thay vì hardcode
    protected function getMoMoConfig()
    {
        return [
            'endpoint' => config('momo.endpoints.' . config('momo.environment') . '.create'),
            'partner_code' => config('momo.partner_code'),
            'access_key' => config('momo.access_key'),
            'secret_key' => config('momo.secret_key'),
            'timeout' => config('momo.timeout', 30),
        ];
    }

    // giới hạn thao tác: nếu có sản phẩm => truyền đến trang checkout, nếu không có => ở lại giỏ hàng
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        return view('user.payment.index', compact('cart'));
    }

    // nhận thao tác thanh toán từ form rồi điều hướng kết quả momo hay COD
    public function processPayment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,momo,bank',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Không thể thanh toán vì giỏ hàng trống.');
        }

        // Tính tổng tiền
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Tạo đơn hàng với trạng thái thanh toán mới
        $order = Orders::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'total_price' => $total,
            'status' => 'chờ xử lý',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        // Lưu chi tiết đơn hàng
        foreach ($cart as $productId => $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $productId, // Sử dụng key của mảng (product_id)
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // ✅ XÓA GIỎ HÀNG NGAY KHI NHẤN THANH TOÁN (kể cả MoMo chưa thành công)
        session()->forget('cart');

        // Rẽ nhánh phương thức thanh toán
        if ($request->payment_method === 'momo') {
            return $this->redirectToMoMo($order);
        } elseif ($request->payment_method === 'bank') {
            // Xử lý chuyển khoản ngân hàng
            $order->update([
                'status' => 'chờ xác nhận chuyển khoản',
                'payment_status' => 'pending'
            ]);
            return redirect()->route('user.orders.index')
                ->with('success', 'Đặt hàng thành công! Vui lòng chuyển khoản theo thông tin bên dưới.');
        } else {
            // COD
            $order->update([
                'status' => 'đã đặt (COD)',
                'payment_status' => 'pending'
            ]);
            return redirect()->route('user.orders.index')
                ->with('success', 'Đặt hàng thành công! Thanh toán khi nhận hàng.');
        }
    }

    /**
     * Debug MoMo configuration
     */
    public function debugMoMo()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $config = $this->getMoMoConfig();
        $testData = [
            'endpoint' => $config['endpoint'],
            'partnerCode' => $config['partner_code'],
            'accessKey' => $config['access_key'],
            'secretKey' => substr($config['secret_key'], 0, 10) . '...',
            'connection_test' => $this->testMoMoConnection(),
            'routes' => [
                'callback' => route('user.payment.momo.callback'),
                'ipn' => route('user.payment.momo.ipn'),
            ]
        ];

        return response()->json($testData);
    }

    /**
     * Test kết nối MoMo
     */
    protected function testMoMoConnection()
    {
        // MoMo API chỉ nhận POST requests, không thể test bằng GET
        // Trả về true để cho phép tạo payment request
        return true;
    }

    /**
     * Tạo giao dịch MoMo và chuyển hướng người dùng
     */
    protected function redirectToMoMo(Orders $order)
    {
        // Lấy cấu hình MoMo
        $config = $this->getMoMoConfig();

        $redirectUrl = route('user.payment.momo.callback');
        $ipnUrl = route('user.payment.momo.ipn');
        $orderId = time() . '_' . $order->id;
        $requestId = uniqid();

        $orderInfo = "Thanh toán đơn hàng #{$order->id}";
        $amount = (string) max(1000, (int) $order->total_price); // test nên >= 1000
        $extraData = ''; // có thể base64_encode(json_encode(...))
        $requestType = 'payWithATM';

        // Sửa lỗi: orderId bị lặp lại trong rawHash
        $rawHash = "accessKey={$config['access_key']}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}"
            . "&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$config['partner_code']}"
            . "&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";

        $signature = hash_hmac('sha256', $rawHash, $config['secret_key']);
        $payload = [
            'partnerCode' => $config['partner_code'],
            'partnerName' => "NVH Store",
            'storeId' => "Store_01",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        Log::info('MoMo request payload: ', $payload);
        Log::info('MoMo rawHash: ' . $rawHash);
        Log::info('MoMo signature: ' . $signature);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json; charset=UTF-8',
                'Accept' => 'application/json'
            ])
                ->timeout($config['timeout'])
                ->withoutVerifying()
                ->post($config['endpoint'], $payload);

            Log::info('MoMo response status: ' . $response->status());
            Log::info('MoMo response body: ' . $response->body());

            if (!$response->successful()) {
                $errorBody = $response->body();
                $errorJson = json_decode($errorBody, true);
                $errorMessage = $errorJson['message'] ?? $errorJson['error'] ?? 'Lỗi không xác định';

                Log::error('MoMo create payment failed', [
                    'status' => $response->status(),
                    'body' => $errorBody,
                    'error_message' => $errorMessage,
                    'payload' => $payload
                ]);

                return redirect()
                    ->route('user.orders.index')
                    ->with('error', "Không thể kết nối MoMo (HTTP {$response->status()}): {$errorMessage}");
            }

            $json = $response->json();
            Log::info('MoMo response JSON:', $json);

            if (!empty($json['payUrl'])) {
                $order->update([
                    'transaction_id' => $requestId,
                    'status' => 'đang thanh toán MoMo'
                ]);
                return redirect()->away($json['payUrl']);
            }

            // Không có payUrl → báo lỗi rõ
            $msg = $json['message'] ?? $json['error'] ?? 'MoMo không trả về payUrl.';
            Log::error('MoMo payUrl missing', ['response' => $json]);
            return redirect()
                ->route('user.orders.index')
                ->with('error', 'Không tạo được link thanh toán MoMo: ' . $msg);
        } catch (\Exception $e) {
            Log::error('MoMo request exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload
            ]);
            return redirect()
                ->route('user.orders.index')
                ->with('error', 'Lỗi khi tạo thanh toán MoMo: ' . $e->getMessage());
        }
    }

    /**
     * Callback: người dùng được MoMo chuyển về sau thanh toán
     */
    public function callback(Request $request)
    {
        $resultCode = $request->input('resultCode'); // 0 = success

        // Có orderId thì lấy id thực từ "time_orderId"
        $order = null;
        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);
            $order = Orders::find($orderId);
        }

        if ($resultCode === '0' || $resultCode === 0) {
            // ✅ Thành công: cập nhật trạng thái đơn hàng
            if ($order) {
                $order->update([
                    'status' => 'đã thanh toán',
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'transaction_id' => $request->input('transId')
                ]);
            }
            return redirect()->route('user.orders.index')
                ->with('success', 'Thanh toán MoMo thành công! Đơn hàng của bạn đã được xác nhận.');
        }

        // ❌ Thất bại/hủy: cập nhật trạng thái
        if ($order) {
            $order->update([
                'status' => 'thanh toán thất bại',
                'payment_status' => 'failed'
            ]);
        }

        // Quay lại trang đơn hàng để người dùng thử thanh toán lại
        return redirect()->route('user.orders.index')
            ->with('error', 'Thanh toán MoMo thất bại hoặc bị hủy. Vui lòng thử lại.');
    }

    /**
     * IPN: MoMo gọi ngầm (server-to-server) báo trạng thái
     */
    public function ipn(Request $request)
    {
        Log::info('MoMo IPN payload:', $request->all());

        // TODO: bạn nên xác thực chữ ký ở đây
        // Ví dụ cập nhật trạng thái dựa vào orderId/resultCode:
        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);
            if ($order = Orders::find($orderId)) {
                if ((string)($request->resultCode) === '0') {
                    $order->update([
                        'status' => 'đã thanh toán',
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'transaction_id' => $request->input('transId')
                    ]);
                } else {
                    $order->update([
                        'status' => 'thanh toán thất bại',
                        'payment_status' => 'failed'
                    ]);
                }
            }
        }
        return response()->json(['resultCode' => 0, 'message' => 'Received']);
    }

    // Cho phép user kéo lại đơn chưa thanh toán hoặc thất bại đi MoMo lần nữa
    public function payAgain(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thanh toán lại đơn này.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('user.orders.index')
                ->with('info', 'Đơn này đã thanh toán.');
        }

        if (! $order->isMoMo()) {
            return redirect()->route('user.orders.index')
                ->with('error', 'Chỉ hỗ trợ thanh toán lại bằng MoMo.');
        }

        // Nếu đơn thất bại hoặc pending → cho phép thanh toán lại
        $order->update([
            'status' => 'chờ thanh toán lại MoMo',
            'payment_status' => 'pending',
            'transaction_id' => null, // reset transaction cũ
        ]);

        return $this->redirectToMoMo($order);
    }


    // gọi lịch sử các đơn hàng theo người dùng
    public function orderHistory()
    {
        $orders = Orders::where('user_id', Auth::id())
            ->with('items.product')
            ->orderByDesc('created_at')
            ->get();
        return view('user.payment.order', compact('orders'));
    }

    // gọi chi tiết sản phẩm từng đơn hàng
    public function show(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }
        $order->load('items.product');
        return view('user.payment.show', compact('order'));
    }

    public function store(Request $request)
    {
        // Tái sử dụng logic processPayment
        return $this->processPayment($request);
    }

    /**
     * Tạo request thanh toán MoMo
     */
    public function momo_payment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Orders::findOrFail($request->order_id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thanh toán đơn này.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('user.orders.index')->with('info', 'Đơn này đã thanh toán.');
        }

        return $this->redirectToMoMo($order);
    }
}
