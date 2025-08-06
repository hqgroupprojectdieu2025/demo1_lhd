<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Mail\ResetPassword;
use App\Events\UserRegistered;


class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('admin.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        
        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Generate reset token
        $token = Str::random(64);
        $user->password_reset_token = $token;
        $user->password_reset_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Generate reset URL
        $resetUrl = URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(10),
            ['token' => $token, 'email' => $email]
        );

        // Send email
        try {
            // Mail::to($user->email)->send(new ResetPassword($user, $resetUrl));
            event(new UserRegistered($user, $resetUrl));
            
            return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư và thư rác.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }
    }

    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;
        
        // Verify token
        $user = User::where('email', $email)
                    ->where('password_reset_token', $token)
                    ->where('password_reset_expires_at', '>', Carbon::now())
                    ->first();
        
        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Liên kết không còn hiệu lực hoặc đã hết hạn. Vui lòng yêu cầu lại.');
        }

        return view('admin.reset-password', compact('token', 'email'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $token = $request->token;
        $email = $request->email;
        
        // Verify token again
        $user = User::where('email', $email)
                    ->where('password_reset_token', $token)
                    ->where('password_reset_expires_at', '>', Carbon::now())
                    ->first();
        
        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Liên kết không còn hiệu lực hoặc đã hết hạn. Vui lòng yêu cầu lại.');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return redirect()->route('login.form')->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập với mật khẩu mới.');
    }
} 