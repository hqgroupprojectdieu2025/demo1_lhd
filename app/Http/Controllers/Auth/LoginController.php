<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); 
    }

    public function login(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        $key = 'login.attempts.' . Str::lower($email);
        $lockoutKey = 'login.lockout.' . Str::lower($email);

        // Kiểm tra xem tài khoản có bị khóa không
        if (cache($lockoutKey)) {
            $remainingTime = cache()->get($lockoutKey . '.expires_at') - now()->timestamp;
            return back()->with('lockout_remaining', $remainingTime)->withInput();
        }

        // Kiểm tra email có tồn tại không
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->incrementLoginAttempts($key);
            return back()->withErrors(['email' => 'Email không tồn tại, vui lòng kiểm tra lại.'])->withInput();
        }

        // Kiểm tra mật khẩu
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            $this->incrementLoginAttempts($key);
            $attempts = RateLimiter::attempts($key);
            
            if ($attempts % 3 == 0) {
                if ($attempts == 3) {
                    $expiresAt = now()->addSeconds(60)->timestamp;
                    cache()->put($lockoutKey, true, now()->addSeconds(60));
                    cache()->put($lockoutKey . '.expires_at', $expiresAt, now()->addSeconds(60));
                    return back()->with('lockout_remaining', 60)->withInput();
                } elseif ($attempts >= 6) {
                    return back()->with('error', 'Bạn đã sai quá nhiều lần. Vui lòng đặt lại mật khẩu.')->withInput();
                }
            }
            
            return back()->withErrors(['password' => 'Mật khẩu không chính xác, vui lòng thử lại.'])->withInput();
        }

        // Kiểm tra loại tài khoản
        if ($user->account_type == 0 && $user->two_fa_enable == 1) { // Admin đã bật 2FA
            // Tạo session cho 2FA
            session([
                '2fa_user_id' => $user->id,
                '2fa_email' => $user->email,
                '2fa_verified' => false
            ]);
            
            // Tạo mã 2FA
            $google2fa = new Google2FA();
            $secret = $user->two_fa_secret ?: $google2fa->generateSecretKey();
            
            // Cập nhật secret nếu chưa có
            if (!$user->two_fa_secret) {
                $user->two_fa_secret = $secret;
                $user->save();
            }
            
            // Tạo mã 2FA tạm thời
            $tempCode = $this->generateTemp2FACode();
            session(['2fa_temp_code' => $tempCode]);
            
            return redirect()->route('2fa.form')->with('success', 'Mã xác thực 2FA đã được gửi qua Google Authenticator.');
        } else { // User hoặc Admin chưa bật 2FA - đăng nhập thông thường
            // Đăng nhập thành công
            RateLimiter::clear($key);
            cache()->forget($lockoutKey);
            cache()->forget($lockoutKey . '.expires_at');
            $request->session()->regenerate();
            
            return redirect()->intended('/users')->with('success', 'Đăng nhập thành công!');
        }
    }

    public function show2FAForm()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login.form');
        }
        
        return view('admin.2fa');
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ], [
            'code.required' => 'Mã 2FA là trường bắt buộc.',
            'code.string' => 'Mã 2FA phải là chuỗi ký tự.',
            'code.size' => 'Mã 2FA phải có 6 ký tự.'
        ]);

        $userId = session('2fa_user_id');
        $tempCode = session('2fa_temp_code');
        $code = $request->input('code');

        if (!$userId) {
            return redirect()->route('login.form')->with('error', 'Phiên đăng nhập đã hết hạn.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Tài khoản không tồn tại.');
        }

        // Kiểm tra mã 2FA
        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($user->two_fa_secret, $code) || $code === $tempCode;

        if (!$isValid) {
            return back()->withErrors(['code' => 'Mã 2FA không hợp lệ, vui lòng thử lại.'])->withInput();
        }

        // Đăng nhập thành công
        Auth::login($user);
        $request->session()->regenerate();
        
        // Xóa session 2FA
        session()->forget(['2fa_user_id', '2fa_email', '2fa_verified', '2fa_temp_code']);
        
        return redirect()->intended('/users')->with('success', 'Đăng nhập thành công!');
    }

    public function resend2FA()
    {
        if (!session('2fa_user_id')) {
            return response()->json(['success' => false, 'message' => 'Phiên đăng nhập đã hết hạn.']);
        }

        // Tạo mã 2FA mới
        $tempCode = $this->generateTemp2FACode();
        session(['2fa_temp_code' => $tempCode]);

        return response()->json([
            'success' => true, 
            'message' => 'Mã 2FA mới đã được gửi qua Google Authenticator.'
        ]);
    }

    private function generateTemp2FACode()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function incrementLoginAttempts($key)
    {
        RateLimiter::hit($key, 3600);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Đăng xuất thành công!');
    }

    public function checkLockoutStatus(Request $request)
    {
        $email = $request->input('email');
        $lockoutKey = 'login.lockout.' . Str::lower($email);
        
        if (cache($lockoutKey)) {
            $expiresAt = cache()->get($lockoutKey . '.expires_at');
            $remainingTime = $expiresAt - now()->timestamp;
            
            if ($remainingTime > 0) {
                return response()->json([
                    'locked' => true,
                    'remaining_time' => $remainingTime
                ]);
            } else {
                // Clear expired lockout
                cache()->forget($lockoutKey);
                cache()->forget($lockoutKey . '.expires_at');
                return response()->json([
                    'locked' => false,
                    'remaining_time' => 0
                ]);
            }
        }
        
        return response()->json([
            'locked' => false,
            'remaining_time' => 0
        ]);
    }
}
