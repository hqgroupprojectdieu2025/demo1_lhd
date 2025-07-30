<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
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
        $data = $request->validated();

        $user = User::create([
            'fullname' => trim($data['fullname']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_token' => Str::random(64),
        ]);


        $url = URL::temporarySignedRoute(
            'verify.email',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => $user->verification_token]
        );

        Mail::to($user->email)->send(new VerifyEmail($user, $url));

        return redirect()->back()->with('success', 'Vui lòng kiểm tra email để kích hoạt tài khoản.');
    }

    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('info', 'Tài khoản đã được xác minh.');
        }

        if ($user->verification_token !== $hash) {
            abort(403, 'Liên kết không hợp lệ');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Tài khoản đã được xác minh, bạn có thể đăng nhập.');
    }
}
