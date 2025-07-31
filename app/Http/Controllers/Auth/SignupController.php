<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Models\EmailSendLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;

class SignupController extends Controller
{
    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function register(SignupRequest $request)
    {
        try {
            $data = $request->validated();

            \Log::info('Bắt đầu đăng ký user với email: ' . $data['email']);

            $user = User::create([
                'fullname' => trim($data['fullname']),
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'verification_token' => Str::random(64),
                'status' => false, // Tài khoản chưa được kích hoạt
            ]);

            \Log::info('User đã được tạo với ID: ' . $user->id);

            $url = URL::temporarySignedRoute(
                'verify.email',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => $user->verification_token]
            );

            \Log::info('URL xác thực đã được tạo: ' . $url);

            try {
                Mail::to($user->email)->send(new VerifyEmail($user, $url));
                \Log::info('Email đã được gửi thành công đến: ' . $user->email);
                
                // Lưu log gửi email
                EmailSendLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'type' => 'verification',
                    'verification_token' => $user->verification_token,
                    'sent_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                // Lưu thời gian gửi email
                session(['email_sent_at_' . $user->id => now()->timestamp]);
                
            } catch (\Exception $mailException) {
                \Log::error('Lỗi gửi email: ' . $mailException->getMessage());
            }

            return redirect()->route('register.form')
                ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.')
                ->with('user_id', $user->id)
                ->with('email', $user->email)
                ->with('needs_verification', true);
                
        } catch (\Exception $e) {
            \Log::error('Lỗi đăng ký: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.');
        }
    }

    public function resendVerificationEmail(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            
            if (!EmailSendLog::canSendEmail($user->email, 'verification', 10)) {
                $remainingTime = $this->getTimeUntilNextDay();
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đạt giới hạn gửi email trong ngày (10 lần). Vui lòng thử lại vào ngày mai.',
                    'daily_limit_reached' => true,
                    'remaining_time' => $remainingTime
                ]);
            }
            
            $lastSentAt = session('email_sent_at_' . $user->id);
            $currentTime = now()->timestamp;
            
            if ($lastSentAt && ($currentTime - $lastSentAt) < 60) {
                $remainingTime = 60 - ($currentTime - $lastSentAt);
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đợi ' . $remainingTime . ' giây nữa để gửi lại email.',
                    'remaining_time' => $remainingTime
                ]);
            }

            $user->verification_token = Str::random(64);
            $user->save();

            $url = URL::temporarySignedRoute(
                'verify.email',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => $user->verification_token]
            );

            Mail::to($user->email)->send(new VerifyEmail($user, $url));
            
            // Lưu log gửi email
            EmailSendLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'type' => 'verification',
                'verification_token' => $user->verification_token,
                'sent_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Cập nhật thời gian gửi email
            session(['email_sent_at_' . $user->id => $currentTime]);

            $remainingSends = EmailSendLog::getRemainingSends($user->email, 'verification', 10);

            return response()->json([
                'success' => true,
                'message' => 'Email xác nhận đã được gửi lại thành công! (Còn ' . $remainingSends . ' lần gửi trong ngày)'
            ]);

        } catch (\Exception $e) {
            \Log::error('Lỗi gửi lại email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi lại email. Vui lòng thử lại.'
            ]);
        }
    }


    private function getTimeUntilNextDay()
    {
        $now = now();
        $tomorrow = $now->addDay()->startOfDay();
        return $tomorrow->diffInSeconds($now);
    }

    public function checkVerificationStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'verified' => !is_null($user->email_verified_at),
                'message' => $user->email_verified_at ? 'Tài khoản đã được xác thực' : 'Tài khoản chưa được xác thực'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tài khoản'
            ]);
        }
    }

    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            return redirect()->route('register.form')->with('info', 'Tài khoản đã được xác minh.');
        }

        if ($user->verification_token !== $hash) {
            abort(403, 'Liên kết không hợp lệ');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect()->route('register.form')
            ->with('success', 'Tài khoản đã được xác minh thành công! Bạn có thể đăng nhập ngay bây giờ.')
            ->with('user_id', $user->id)
            ->with('email', $user->email)
            ->with('needs_verification', false);
    }
}
