@extends('dashboard')

@section('content')
<div class="container">
    <h3>Chi tiết người dùng</h3>

    <ul class="list-group">
        <li class="list-group-item"><strong>Họ tên:</strong> {{ $user->fullname }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
        <li class="list-group-item"><strong>Vai trò:</strong> {{ $user->role == 'admin' ? 'Admin' : 'Khách hàng' }}</li>
        <li class="list-group-item"><strong>Trạng thái:</strong> {{ $user->status ? 'Đang hoạt động' : 'Bị khóa' }}</li>
        <li class="list-group-item"><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <a href="{{ route('users.index') }}" class="btn btn-primary mt-3">Quay lại danh sách</a>
</div>
@endsection
