@extends('layouts.master')

@section('title', 'Dashboard - Welcome')

@section('content')
<!--begin::Login-->
<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
    
    <!--begin::Content-->
    <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
        <!--begin::Content body-->
        <div class="d-flex flex-column-fluid flex-center">
            <!--begin::Welcome Form-->
            <div class="login-form">
                <!--begin::Title-->
                <div class="pb-13 pt-lg-0 pt-5">
                    <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Dashboard</h3>
                    <span class="text-muted font-weight-bold font-size-h4">Chào mừng bạn đến với hệ thống quản lý</span>
                </div>
                <!--end::Title-->

                @auth
                    <!--begin::Welcome Message-->
                    <div class="text-center mb-10">
                        <h2 class="font-weight-bolder text-dark font-size-h2-lg">Xin chào, {{ Auth::user()->fullname }}! 👋</h2>
                        <p class="text-muted font-size-h6">Đây là trang quản lý của bạn</p>
                    </div>
                    <!--end::Welcome Message-->
                    @if(session('email_sent'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Email xác thực đã được gửi!</strong> Vui lòng kiểm tra hộp thư đến và thư rác.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <!--begin::Stats Grid-->
                    <div class="row mb-8">
                        <div class="col-md-6 mb-4">
                            <div class="card card-custom card-stretch">
                                <div class="card-body p-6">
                                    <div class="d-flex align-items-center">
                                        
                                        <div class="symbol symbol-50 symbol-light-primary mr-6">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="fas fa-users text-primary font-size-h4"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-muted font-weight-bold font-size-sm">Loại tài khoản</span>
                                            <span class="font-weight-bolder text-dark font-size-h5">
                                                @if(Auth::user()->account_type == 0)
                                                    <span class="label label-lg label-primary label-inline">Admin</span>
                                                @else
                                                    <span class="label label-lg label-secondary label-inline">User</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card card-custom card-stretch">
                                <div class="card-body p-6">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50 symbol-light-success mr-6">
                                            <span class="symbol-label bg-light-success">
                                                <i class="fas fa-shield-alt text-success font-size-h4"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-muted font-weight-bold font-size-sm">Trạng thái 2FA</span>
                                            <span class="font-weight-bolder text-dark font-size-h5">
                                                @if(Auth::user()->two_fa_enable)
                                                    <span class="label label-lg label-success label-inline">Đã bật</span>
                                                @else
                                                    <span class="label label-lg label-warning label-inline">Chưa bật</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Stats Grid-->
                    
                    <!--begin::User Info Card-->
                    <div class="card card-custom mb-8">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                Thông tin chi tiết
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between py-3 border-bottom">
                                        <span class="text-muted font-weight-bold">Tên đầy đủ:</span>
                                        <span class="font-weight-bolder text-dark">{{ Auth::user()->fullname }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-3 border-bottom">
                                        <span class="text-muted font-weight-bold">Email:</span>
                                        <span class="font-weight-bolder text-dark">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between py-3 border-bottom">
                                        <span class="text-muted font-weight-bold">Trạng thái email:</span>
                                        <span class="font-weight-bolder">
                                            @if(Auth::user()->email_verified_at)
                                                <span class="label label-lg label-success label-inline">Đã xác thực</span>
                                            <!-- @else
                                                <div class="d-flex align-items-center">
                                                    <div class="text-center">
                                                        {{-- Đếm ngược nếu đã gửi email --}}
                                                        @if(session('email_sent'))
                                                            <div id="countdown" class="font-weight-bold text-primary" style="font-size: 1rem;">60</div>
                                                            <small class="text-muted d-block">giây</small>
                                                            <form method="POST" action="{{ route('resend.verification.email') }}" id="resend-form">
                                                                @csrf
                                                                <button type="submit" id="resend-btn" class="btn btn-sm btn-outline-primary mt-2" style="display: none;">
                                                                    Gửi lại
                                                                </button>
                                                            </form>
                                                        @else
                                                            {{-- Nút xác thực ban đầu --}}
                                                            <form method="POST" action="{{ route('resend.verification.email') }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning mt-2">
                                                                    Xác thực?
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif -->
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between py-3">
                                        <span class="text-muted font-weight-bold">Ngày tham gia:</span>
                                        <span class="font-weight-bolder text-dark">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::User Info Card-->
                    
                    <!--begin::Action Buttons-->
                    <div class="d-flex flex-wrap justify-content-center">
                        <form action="{{ route('logout') }}" method="POST" class="mr-3 mb-3">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg font-weight-bolder px-8 py-4">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Đăng xuất
                            </button>
                        </form>
                        
                        @if(Auth::user()->account_type == 0)
                            <a href="{{ route('2fa.setup') }}" class="btn btn-info btn-lg font-weight-bolder px-8 py-4 mb-3">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Cài đặt 2FA
                            </a>
                        @endif
                    </div>
                    <!--end::Action Buttons-->
                @else
                    <!--begin::Welcome Message for Guests-->
                    <div class="text-center mb-10">
                        <h2 class="font-weight-bolder text-dark font-size-h2-lg">Chào mừng bạn! 👋</h2>
                        <p class="text-muted font-size-h6">Vui lòng đăng nhập để truy cập hệ thống</p>
                    </div>
                    <!--end::Welcome Message for Guests-->
                    
                    <!--begin::Action Buttons for Guests-->
                    <div class="d-flex flex-wrap justify-content-center">
                        <a href="{{ route('login.form') }}" class="btn btn-primary btn-lg font-weight-bolder px-8 py-4 mr-3 mb-3">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Đăng nhập
                        </a>
                        <a href="{{ route('register.form') }}" class="btn btn-outline-primary btn-lg font-weight-bolder px-8 py-4 mb-3">
                            <i class="fas fa-user-plus mr-2"></i>
                            Đăng ký
                        </a>
                    </div>
                    <!--end::Action Buttons for Guests-->
                @endauth
            </div>
            <!--end::Welcome Form-->
        </div>
        <!--end::Content body-->
        <!--begin::Content footer-->
        <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
            <a href="#" class="text-primary font-weight-bolder font-size-h5">Quay lại trang chủ</a>
        </div>
        <!--end::Content footer-->
    </div>
    <!--end::Content-->
</div>
<!--end::Login-->

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let countdown = 60;
        let countdownEl = document.getElementById('countdown');
        let resendBtn = document.getElementById('resend-btn');

        if (countdownEl) {
            let interval = setInterval(() => {
                countdown--;
                countdownEl.innerText = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);
                    if (resendBtn) {
                        resendBtn.style.display = 'inline-block';
                        countdownEl.parentElement.style.display = 'none';
                    }
                }
            }, 1000);
        }
    });
</script>
@endpush

@if(session('email_sent'))
    <script>
        let timeLeft = 60;
        const countdownEl = document.getElementById('countdown');
        const resendBtn = document.getElementById('resend-btn');

        const timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownEl.style.display = 'none';
                resendBtn.style.display = 'inline-block';
            }
        }, 1000);
    </script>
@endif


@endsection
