<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    // Danh sách người dùng với phân trang, tìm kiếm, lọc
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $search = $request->input('keyword');
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }

        // Lọc theo account_type
        if ($request->filled('account_type') && in_array($request->account_type, ['0', '1'])) {
            $query->where('account_type', (int) $request->account_type);
        }

        // Lọc theo trạng thái
        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('status', (int) $request->status);
        }

        $users = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('users.index', [
            'users' => $users,
            'keyword' => $request->keyword,
            'account_type' => $request->account_type,
            'status' => $request->status
        ]);
    }


    // Trang chỉnh sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = [
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'address' => $request->address,
            'account_type' => $request->account_type,
        ];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            if (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png']) || $file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withErrors(['avatar' => 'Chỉ cho phép ảnh JPG, PNG nhỏ hơn 2MB'])->withInput();
            }

            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $file->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
    }
    // Mở/Khoá tài khoản
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 0? 1 : 0;
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái tài khoản thành công!');
    }

    //Cập nhật quyền
    public function UpdateRole($id)
    {
        $user = User::findOrFail($id);
        $user->account_type = $user->account_type == 0? 1 : 0;
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật quyền tài khoản thành công!');
    }

    // Xem chi tiết người dùng
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
}
