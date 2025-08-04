<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\PasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('admin.change-pass');
    }

    public function changePassword(PasswordRequest $request)
    {
        $user = Auth::user();

        // Validate current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log out user from all devices except current
        Auth::logoutOtherDevices($request->new_password);

        // Redirect to login with success message
        Auth::logout();
        return redirect()->route('login.form')->with('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại với mật khẩu mới.');
    }
}
