@extends('layouts.master')

@section('title', 'Xác thực 2FA')

@section('content')
<!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::2FA Form-->
                <div class="login-form">
                    <!--begin::Form-->
                    <form class="form" id="kt_2fa_form" method="POST" action="{{ route('2fa.verify') }}">
                        @csrf
                        <!--begin::Title-->
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Xác thực 2FA</h3>
                            <span class="text-muted font-weight-bold font-size-h4">Vui lòng nhập mã xác thực từ Google Authenticator</span>
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

                        <!--begin::Form group - 2FA Code-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Mã xác thực 2FA</label>
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('code') is-invalid @enderror" 
                                type="text" name="code" value="{{ old('code') }}" autocomplete="off" 
                                placeholder="Nhập mã 6 chữ số" maxlength="6" />

                            @error('code')
                                <div class="text-danger mt-2 font-size-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Form group-->

                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">
                                Xác thực
                            </button>

                            <button type="button" class="btn btn-light-primary font-weight-bolder px-8 py-4 my-3 font-size-lg" id="resend-2fa">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Gửi lại mã 2FA
                            </button>
                        </div>
                        <!--end::Action-->

                        <!--begin::Help Text-->
                        <div class="text-center mt-4">
                            <p class="text-muted font-size-sm">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mã xác thực sẽ thay đổi mỗi 30 giây trong ứng dụng Google Authenticator
                            </p>
                        </div>
                        <!--end::Help Text-->
                    </form>

                </div>
                <!--end::2FA Form-->
            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
                <a href="{{ route('login.form') }}" class="text-primary font-weight-bolder font-size-h5">Quay lại đăng nhập</a>
            </div>
            <!--end::Content footer-->
        </div>
        <!--end::Content-->

        <!--begin::2FA Script-->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const codeInput = document.querySelector('input[name="code"]');
                const resendButton = document.getElementById('resend-2fa');
                let resendTimeout;

                // Auto-focus on code input
                codeInput.focus();

                // Auto-submit when 6 digits are entered
                codeInput.addEventListener('input', function() {
                    if (this.value.length === 6) {
                        document.getElementById('kt_2fa_form').submit();
                    }
                });

                // Resend 2FA code
                resendButton.addEventListener('click', function() {
                    if (resendTimeout) return; // Prevent multiple clicks

                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';

                    fetch('{{ route("2fa.resend") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show';
                            alertDiv.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>${data.message}</span>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            `;
                            
                            const form = document.getElementById('kt_2fa_form');
                            form.parentNode.insertBefore(alertDiv, form);
                            
                            // Auto-hide after 5 seconds
                            setTimeout(() => {
                                alertDiv.remove();
                            }, 5000);
                        } else {
                            // Show error message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                            alertDiv.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>${data.message}</span>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            `;
                            
                            const form = document.getElementById('kt_2fa_form');
                            form.parentNode.insertBefore(alertDiv, form);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Show error message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>Có lỗi xảy ra khi gửi lại mã 2FA. Vui lòng thử lại.</span>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        `;
                        
                        const form = document.getElementById('kt_2fa_form');
                        form.parentNode.insertBefore(alertDiv, form);
                    })
                    .finally(() => {
                        // Re-enable button after 30 seconds
                        resendTimeout = setTimeout(() => {
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Gửi lại mã 2FA';
                        }, 30000);
                    });
                });
            });
        </script>
        <!--end::2FA Script-->
@endsection 