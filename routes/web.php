<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\User\OrderController as OrderController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ (public)
//Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/', [HomeController::class, 'index'])->name('home');


// Public: duyệt sản phẩm & danh mục
Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::resource('categories', CategoryController::class)->only(['index', 'show']);

// ---------------- AUTH ----------------
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ---------------- EMAIL VERIFICATION ----------------

// Trang hiển thị yêu cầu verify
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Link xác thực trong email
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    // check hash hợp lệ
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link xác thực không hợp lệ.');
    }

    // cập nhật email_verified_at nếu chưa có
    if (is_null($user->email_verified_at)) {
        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();
    }

    return redirect()->route('login')->with('success', 'Xác thực email thành công, hãy đăng nhập.');
})->middleware(['signed'])->name('verification.verify');

// Gửi lại email xác thực
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email xác thực đã được gửi lại!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ---------------- USER ROUTES ----------------
// Giỏ hàng - chỉ cần đăng nhập, không cần xác thực email
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Yêu cầu user login + verify email cho các chức năng khác
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {

    // Thanh toán (OrderController xử lý cả COD & MoMo)
    Route::get('/payment', [OrderController::class, 'index'])->name('payment.index');
    Route::post('/payment/process', [OrderController::class, 'processPayment'])->name('payment.process');

    // Trang thông báo thành công (đang được CartController gọi)
    Route::get('/payment/success/{orderId}', [CartController::class, 'success'])->name('payment.success');

    // MoMo
    Route::get('/orders/{order}/pay/momo', [OrderController::class, 'payAgain'])->name('orders.momo.pay');
    Route::post('/payment/momo', [OrderController::class, 'momo_payment'])->name('payment.momo');
    Route::get('/payment/momo/callback', [OrderController::class, 'callback'])->name('payment.momo.callback');
    Route::post('/payment/momo/ipn', [OrderController::class, 'ipn'])->name('payment.momo.ipn');

    // Đơn hàng
    Route::get('/orders', [OrderController::class, 'orderHistory'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Debug MoMo (chỉ môi trường local)
    if (app()->environment('local')) {
        Route::get('/debug/momo', [OrderController::class, 'debugMoMo'])->name('debug.momo');

        Route::get('/test/momo', function () {
            try {
                $config = [
                    'endpoint' => config('momo.endpoints.' . config('momo.environment') . '.create'),
                    'partner_code' => config('momo.partner_code'),
                    'access_key' => config('momo.access_key'),
                    'secret_key' => config('momo.secret_key'),
                    'timeout' => config('momo.timeout', 30),
                ];

                echo "Config: <pre>" . print_r($config, true) . "</pre>";

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Accept' => 'application/json'
                ])
                    ->timeout($config['timeout'])
                    ->withoutVerifying()
                    ->get($config['endpoint']);

                echo "Response Status: " . $response->status() . "<br>";
                echo "Response Body: " . $response->body();
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        })->name('test.momo');
    }
});

// ĐÁNH GIÁ SẢN PHẨM (giữ URL /user/... nhưng đặt name NGẮN không có tiền tố 'user.')
Route::middleware(['auth', 'verified'])->prefix('user')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
        ->name('products.reviews.store');
});

// Wishlist
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Chat (yêu cầu đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/customer', [ChatController::class, 'index'])->name('chat.customer');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

// ---------------- ADMIN ROUTES ----------------
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories/store', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.destroy');

    // Quản lý sản phẩm
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.destroy');

    // Đơn hàng (resource)
    Route::resource('orders', AdminOrderController::class);

    // Cập nhật trạng thái thanh toán
    Route::patch('/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])
        ->name('orders.payment-status.update');

    // Báo cáo
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/charts', [ReportController::class, 'charts'])->name('reports.charts');

    // Người dùng
    Route::resource('users', AdminUserController::class);

    // Quản lý đánh giá
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/reply', [App\Http\Controllers\Admin\ReviewController::class, 'reply'])->name('reviews.reply');
    Route::patch('/reviews/{review}/mark-read', [App\Http\Controllers\Admin\ReviewController::class, 'markAsRead'])->name('reviews.mark-read');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Quản lý chat
    Route::get('/messages', [ChatController::class, 'adminIndex'])->name('messages.index');
    Route::get('/messages/chat/{user}', [ChatController::class, 'adminChat'])->name('messages.chat');
    Route::post('/messages/send', [ChatController::class, 'adminSend'])->name('messages.send');
});
