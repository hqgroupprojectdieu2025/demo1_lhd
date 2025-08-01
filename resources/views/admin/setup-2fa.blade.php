@extends('layouts.master')

@section('title', 'Cài đặt 2FA')

@section('content')
<!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::2FA Setup Form-->
                <div class="login-form">
                    <!--begin::Title-->
                    <div class="pb-13 pt-lg-0 pt-5">
                        <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Cài đặt xác thực 2 bước</h3>
                        <span class="text-muted font-weight-bold font-size-h4">Bảo mật tài khoản của bạn với Google Authenticator</span>
                    </div>
                    <!--end::Title-->

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

                    <!--begin::Setup Instructions-->
                    <div class="card card-custom mb-5">
                        <div class="card-header">
                            <h5 class="card-title">Hướng dẫn cài đặt</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Bước 1: Cài đặt Google Authenticator</h6>
                                    <ul class="text-muted">
                                        <li>Tải ứng dụng Google Authenticator từ App Store hoặc Google Play</li>
                                        <li>Mở ứng dụng và nhấn dấu "+" để thêm tài khoản</li>
                                    </ul>
                                    
                                    <h6>Bước 2: Quét mã QR</h6>
                                    <ul class="text-muted">
                                        <li>Chọn "Quét mã QR"</li>
                                        <li>Hướng camera vào mã QR bên phải</li>
                                        <li>Hoặc nhập mã bí mật thủ công</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="qr-code-container">
                                        {!! $qrCodeSvg !!}
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Mã bí mật: <code>{{ $user->two_fa_secret }}</code></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Setup Instructions-->

                    <!--begin::Verification Form-->
                    <form class="form" method="POST" action="{{ route('2fa.enable') }}">
                        @csrf
                        <!--begin::Form group - Verification Code-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Mã xác thực</label>
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('code') is-invalid @enderror" 
                                type="text" name="code" value="{{ old('code') }}" autocomplete="off" 
                                placeholder="Nhập mã 6 chữ số từ Google Authenticator" maxlength="6" />

                            @error('code')
                                <div class="text-danger mt-2 font-size-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Form group-->

                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">
                                Bật xác thực 2 bước
                            </button>

                            <a href="{{ route('2fa.regenerate') }}" class="btn btn-light-primary font-weight-bolder px-8 py-4 my-3 font-size-lg" 
                               onclick="return confirm('Bạn có chắc muốn tạo lại mã bí mật?')">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Tạo lại mã bí mật
                            </a>
                        </div>
                        <!--end::Action-->
                    </form>
                    <!--end::Verification Form-->

                    <!--begin::Help Text-->
                    <div class="text-center mt-4">
                        <p class="text-muted font-size-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Sau khi bật 2FA, bạn sẽ cần nhập mã xác thực mỗi khi đăng nhập
                        </p>
                    </div>
                    <!--end::Help Text-->
                </div>
                <!--end::2FA Setup Form-->
            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
                <a href="{{ route('dashboard') }}" class="text-primary font-weight-bolder font-size-h5">Quay lại Dashboard</a>
            </div>
            <!--end::Content footer-->
        </div>
        <!--end::Content-->

        <!--begin::2FA Setup Script-->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const codeInput = document.querySelector('input[name="code"]');
                
                // Auto-focus on code input
                codeInput.focus();

                // Auto-submit when 6 digits are entered
                codeInput.addEventListener('input', function() {
                    if (this.value.length === 6) {
                        this.form.submit();
                    }
                });
            });
        </script>
        <!--end::2FA Setup Script-->
@endsection 