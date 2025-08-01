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
        $data = $request->validate();
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }
}
