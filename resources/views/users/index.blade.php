@extends('layouts.admin')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Danh sách người dùng</h2>
        <a href="{{ route('layouts.admin') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
        </a>
    </div>

    {{-- Tìm kiếm và lọc --}}
    <form method="GET" action="{{ route('users.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc email" value="{{ request('keyword') }}">
        </div>
        <div class="col-md-2">
            <select name="account_type" class="form-select">
                <option value="">Tất cả vai trò</option>
                <option value="0" {{ request('account_type') === '0' ? 'selected' : '' }}>Admin</option>
                <option value="1" {{ request('account_type') === '1' ? 'selected' : '' }}>Khách hàng</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Bị khóa</option>
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
                    <td>{{ $user->account_type == '0' ? 'Admin' : 'Khách hàng' }}</td>
                    <td>
                        <span class="badge bg-{{ $user->status == 0? 'success' : 'secondary' }}">
                            {{ $user->status == '0' ? 'Đang hoạt động' : 'Bị khóa' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">Xem</a>
                        <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-{{ $user->status == 0 ? 'danger' : 'success' }}"
                                    onclick="return confirm('Bạn có chắc chắn muốn {{ $user->status == 0 ? 'khóa' : 'kích hoạt' }} tài khoản {{$user->email}} này không?')">
                                {{ $user->status == 0 ? 'Khóa' : 'Kích hoạt' }}
                            </button>
                        </form>
                        <form action="{{ route('users.UpdateRole', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-{{ $user->status == 0 ? 'danger' : 'success' }}"
                                    onclick="return confirm('Bạn có chắc chắn muốn đổi tài khoản {{$user->email}} này sang {{ $user->account_type == 1 ? 'admin' : 'khách hàng' }} không?')">
                                {{ $user->account_type == 1 ? 'Admin' : 'Khách hàng' }}
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
