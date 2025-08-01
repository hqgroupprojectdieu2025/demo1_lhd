<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFAController extends Controller
{
    public function showSetupForm()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login.form');
        }

        $google2fa = new Google2FA();
        
        // Tạo secret key nếu chưa có
        if (!$user->two_fa_secret) {
            $user->two_fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        // Tạo QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_fa_secret
        );

        // Tạo QR code SVG
        $qrCodeSvg = QrCode::size(200)->generate($qrCodeUrl);

        return view('admin.setup-2fa', compact('user', 'qrCodeUrl', 'qrCodeSvg'));
    }

    public function enable2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ], [
            'code.required' => 'Mã xác thực là trường bắt buộc.',
            'code.string' => 'Mã xác thực phải là chuỗi ký tự.',
            'code.size' => 'Mã xác thực phải có 6 ký tự.'
        ]);

        $user = Auth::user();
        $code = $request->input('code');

        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($user->two_fa_secret, $code);

        if (!$isValid) {
            return back()->withErrors(['code' => 'Mã xác thực không hợp lệ, vui lòng thử lại.'])->withInput();
        }

        // Bật 2FA
        $user->two_fa_enable = true;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Xác thực 2 bước đã được bật thành công!');
    }

    public function disable2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ], [
            'code.required' => 'Mã xác thực là trường bắt buộc.',
            'code.string' => 'Mã xác thực phải là chuỗi ký tự.',
            'code.size' => 'Mã xác thực phải có 6 ký tự.'
        ]);

        $user = Auth::user();
        $code = $request->input('code');

        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($user->two_fa_secret, $code);

        if (!$isValid) {
            return back()->withErrors(['code' => 'Mã xác thực không hợp lệ, vui lòng thử lại.'])->withInput();
        }

        // Tắt 2FA
        $user->two_fa_enable = false;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Xác thực 2 bước đã được tắt thành công!');
    }

    public function regenerateSecret()
    {
        $user = Auth::user();
        
        $google2fa = new Google2FA();
        $user->two_fa_secret = $google2fa->generateSecretKey();
        $user->two_fa_enable = false; // Reset về trạng thái chưa bật
        $user->save();

        return redirect()->route('2fa.setup')->with('success', 'Mã bí mật đã được tạo lại. Vui lòng cấu hình lại Google Authenticator.');
    }
} 