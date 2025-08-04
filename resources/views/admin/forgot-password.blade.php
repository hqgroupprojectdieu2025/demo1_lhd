@extends('layouts.master')

@section('title', 'Quên mật khẩu')

@section('content')
<!--begin::Content-->
<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
    <div class="d-flex flex-column-fluid flex-center">
        <div class="login-form">
            <!--begin::Form-->
            <form class="form" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="pb-13 pt-lg-0 pt-5">
                    <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Quên mật khẩu?</h3>
                    <p class="text-muted font-weight-bold font-size-h4">Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
                </div>

                <!--begin::Alert Messages-->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <!--end::Alert Messages-->

                <div class="form-group">
                    <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('email') is-invalid @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Nhập email của bạn"
                           required />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                    <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Gửi liên kết đặt lại
                    </button>
                    <a href="{{ route('login.form') }}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay lại đăng nhập
                    </a>
                </div>

                <div class="text-center">
                    <p class="text-muted font-size-sm">
                        Chưa có tài khoản? 
                        <a href="{{ route('register.form') }}" class="text-primary font-weight-bolder">Đăng ký ngay</a>
                    </p>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Content-->
@endsection 