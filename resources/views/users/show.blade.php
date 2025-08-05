@extends('layouts.admin')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container">
    <h3>Thông tin cá nhân</h3>

    @if (!$user)
        <div class="alert alert-danger">
            Không thể tải thông tin người dùng, vui lòng thử lại.
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                         alt="Ảnh đại diện" 
                         class="rounded-circle" 
                         width="120" height="120">
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Họ và tên:</strong> {{ $user->fullname }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                    <li class="list-group-item"><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</li>
                    <li class="list-group-item"><strong>Ngày sinh:</strong> {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : 'Chưa cập nhật' }}</li>
                    <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa cập nhật' }}</li>
                    <li class="list-group-item"><strong>Vai trò:</strong> {{ $user->account_type == 0 ? 'Admin' : 'Khách hàng' }}</li>
                    <li class="list-group-item"><strong>2FA:</strong> {{ $user->two_fa_enable == 1 ? 'Đã bật' : 'Chưa bật' }}</li>
                    <li class="list-group-item"><strong>Trạng thái:</strong> {{ $user->status == 0 ? 'Đang hoạt động' : 'Bị khóa' }}</li>
                    <li class="list-group-item"><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                </ul>

                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
            </div>
        </div>
    @endif
</div>
@endsection
