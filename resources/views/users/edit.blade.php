@extends('dashboard')

@section('content')
<div class="container">
    <h3>Chỉnh sửa người dùng</h3>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="fullname" class="form-label">Họ tên</label>
            <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" class="form-control" required>
        </div>

        <!-- Không chỉnh sửa email -->

        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select name="role" class="form-select">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Khách hàng</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
