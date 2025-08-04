<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để xem thông tin cá nhân.');
            }

            return view('admin.profile', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải thông tin người dùng, vui lòng thử lại.');
        }
    }

    public function showEditForm()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để chỉnh sửa thông tin cá nhân.');
            }

            return view('admin.edit-profile', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể tải thông tin người dùng, vui lòng thử lại.');
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để chỉnh sửa thông tin cá nhân.');
            }

            $data = $request->validated();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            // Update user data
            $user->update($data);

            return redirect()->route('profile.show')->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể cập nhật thông tin, vui lòng thử lại.');
        }
    }
} 