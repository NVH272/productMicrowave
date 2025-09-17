<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký người dùng
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user', // Mặc định là user
        ]);

        // Gửi email xác thực
        event(new Registered($user));

        // ❌ KHÔNG login ở đây
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Vui lòng kiểm tra email để xác thực tài khoản trước khi đăng nhập.');
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập người dùng
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Tìm user theo email
        $user = User::where('email', $credentials['email'])->first();

        // Nếu không tìm thấy user hoặc mật khẩu sai
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ])->onlyInput('email');
        }

        // Kiểm tra xác thực email (trừ admin)
        if ($user->role !== 'admin' && !$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn chưa được xác thực. 
                    Vui lòng kiểm tra email: ' . e($user->email),
            ])->onlyInput('email');
        }

        // Nếu mọi thứ ok → login
        Auth::login($user);
        $request->session()->regenerate();

        // Nếu là admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Nếu là user
        return redirect()->intended(route('home'));
    }


    /**
     * Xử lý đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
