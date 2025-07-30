<!-- resources/views/auth/forgot.blade.php -->
@extends('admin.layout')

@section('title', 'Quên mật khẩu')

@section('content')
    <h2>Khôi phục mật khẩu</h2>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Gửi liên kết khôi phục</button>
    </form>
    <a href="{{ route('login') }}">Quay lại đăng nhập</a>
@endsection
