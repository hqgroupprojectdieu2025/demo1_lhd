@extends('layouts.master')

@section('title', 'Đổi mật khẩu')

@section('content')
<!--begin::Content-->
<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
    <div class="d-flex flex-column-fluid flex-center">
        <div class="login-form">
            <!--begin::Form-->
            <form class="form" novalidate="novalidate" method="POST" action="{{ route('password.change') }}">
                @csrf
                <div class="pb-13 pt-lg-0 pt-5">
                    <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Đổi mật khẩu</h3>
                    <p class="text-muted font-weight-bold font-size-h4">Nhập thông tin để thay đổi mật khẩu</p>
                </div>

                <!--begin::Alert Messages-->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <!--end::Alert Messages-->

                <div class="form-group">
                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('current_password') is-invalid @enderror" 
                           type="password" 
                           placeholder="Mật khẩu hiện tại" 
                           name="current_password" 
                           required />
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('new_password') is-invalid @enderror" 
                           type="password" 
                           placeholder="Mật khẩu mới" 
                           name="new_password" 
                           required />
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('new_password_confirmation') is-invalid @enderror" 
                           type="password" 
                           placeholder="Xác nhận mật khẩu mới" 
                           name="new_password_confirmation" 
                           required />
                    @error('new_password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                    <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Xác nhận thay đổi</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Hủy</a>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Content-->
@endsection
