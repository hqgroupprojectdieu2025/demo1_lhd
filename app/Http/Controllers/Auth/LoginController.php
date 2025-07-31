<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); 
    }

    public function login(LoginRequest $request)
    {
        $user = $request->only('email', 'password');

        $email = $request ->input('email');
        $key = 'login.attempts.' .Str::lower($email);
        $lockoutKey = 'login.lockout. ' .Str::lower($email);

        if(cache($lockoutKey)){
            return back()->withErrors(['email' => 'Bạn đã đăng nhập sai 3 lần. Vui lòng thử lại sau 60s'])->withInput();
        }

        if(Auth::attempt($user)){
            RateLimiter::clear($key);
            cache()->forget(lockoutKey);
            $request -> session() -> regenerate();
            return redirect()->intended('/');
        }

        RateLimiter::hit($key,3600);
        $attempt = RateLimiter::attempts($key);

        if($attempt % 3 == 0){
            if($attempt == 3){
                cache() ->put($lockoutKey, true, now()->addSeconds(60));
                return back() ->withErrors(['email' =>'Bạn đã sai 3 lần. Vui lòng thử lại sau 60s.'])->withInput();
            } elseif ($attempt >=6){
                return back() -> withErrors(['email'=>'Bạn đã sai quá nhiều lần. Vui lòng đặt lại mật khẩu.']);
            }
        }

        return back() -> withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    public function logout(LoginRequest $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
