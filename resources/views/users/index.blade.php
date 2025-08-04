@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách người dùng</h2>

    {{-- Tìm kiếm và lọc --}}
    <form method="GET" action="{{ route('users.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc email" value="{{ request('keyword') }}">
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <option value="">Tất cả vai trò</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Khách hàng</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Bị khóa</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
        </div>
    </form>

    {{-- Danh sách người dùng --}}
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role === 'admin' ? 'Admin' : 'Khách hàng' }}</td>
                    <td>
                        <span class="badge bg-{{ $user->status === '0' ? 'success' : 'secondary' }}">
                            {{ $user->status === 'active' ? 'Đang hoạt động' : 'Bị khóa' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">Xem</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-{{ $user->status === 'active' ? 'danger' : 'success' }}"
                                    onclick="return confirm('Bạn có chắc chắn muốn {{ $user->status === 'active' ? 'khóa' : 'mở' }} tài khoản này không?')">
                                {{ $user->status === 'active' ? 'Khóa' : 'Mở' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Không có người dùng nào.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
