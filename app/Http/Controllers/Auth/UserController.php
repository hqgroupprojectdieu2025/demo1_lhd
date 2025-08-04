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

        // Tìm kiếm theo tên hoặc email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Lọc theo vai trò
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Lọc theo trạng thái (active = 0, inactive = 1)
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status === 'active' ? 0 : 1;
            $query->where('status', $status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Giữ nguyên các tham số lọc khi phân trang
        $users->appends($request->all());

        return view('users.index', [
            'users' => $users,
            'search' => $request->search,
            'role' => $request->role,
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
        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            'status' => $request->status
        ]);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    // Mở/Khoá tài khoản
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái tài khoản thành công!');
    }

    // Xem chi tiết người dùng
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
}
