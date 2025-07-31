@extends('layouts.layout')

@section('title', 'Đăng ký')

@section('content')
<!--begin::Content-->
    <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
        <!--begin::Content body-->
        <div class="d-flex flex-column-fluid flex-center">
            <!--begin::Signup-->
            <div class="login-form">
                <!--begin::Form-->
                <form class="form" novalidate="novalidate" id="kt_login_signup_form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <!--begin::Title-->
                    <div class="pb-13 pt-lg-0 pt-5">
                        <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Đăng ký</h3>
                        <p class="text-muted font-weight-bold font-size-h4">Nhập thông tin để tạo tài khoản</p>
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
                        
                        @if(session('user_id') && session('email') && session('needs_verification'))
                            <div class="alert alert-info" role="alert">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <strong>Email đã được gửi đến: {{ session('email') }}</strong><br>
                                        <small>Vui lòng kiểm tra hộp thư và spam folder</small>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> 
                                                Giới hạn: 10 lần gửi email/ngày
                                            </small>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div id="countdown-container" class="text-center">
                                            <div id="countdown" class="font-weight-bold text-primary">60</div>
                                            <small class="text-muted">giây</small>
                                        </div>
                                        <button id="resend-btn" class="btn btn-sm btn-outline-primary mt-2" style="display: none;" 
                                                data-user-id="{{ session('user_id') }}">
                                            Gửi lại email
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <!--end::Alert Messages-->

                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('fullname') is-invalid @enderror" 
                               type="text" 
                               placeholder="Họ và tên" 
                               name="fullname" 
                               value="{{ old('fullname') }}"
                               autocomplete="off" />
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('email') is-invalid @enderror" 
                               type="email" 
                               placeholder="Email" 
                               name="email" 
                               value="{{ old('email') }}"
                               autocomplete="off" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('password') is-invalid @enderror" 
                               type="password" 
                               placeholder="Mật khẩu" 
                               name="password" 
                               autocomplete="off" />
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @error('password_confirmation') is-invalid @enderror" 
                               type="password" 
                               placeholder="Xác nhận mật khẩu" 
                               name="password_confirmation" 
                               autocomplete="off" />
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group">
                        <label class="checkbox mb-0 @error('agree') is-invalid @enderror">
                        <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }} />
                        Tôi đồng ý với
                        <a href="#">điều khoản và điều kiện</a>.
                        <span></span></label>
                        @error('agree')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                        <button type="submit" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Đăng ký</button>
                        <a href="{{ route('login.form') }}" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Hủy</a>
                    </div>
                    <!--end::Form group-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Signup-->      
        </div>
        <!--end::Content body-->
        <!--begin::Content footer-->
        <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
            <a href="#" class="text-primary font-weight-bolder font-size-h5">Điều khoản</a>
            <a href="#" class="text-primary ml-10 font-weight-bolder font-size-h5">Gói dịch vụ</a>
            <a href="#" class="text-primary ml-10 font-weight-bolder font-size-h5">Liên hệ</a>
        </div>
        <!--end::Content footer-->
    </div>
    <!--end::Content-->

    @if(session('user_id') && session('email') && session('needs_verification'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 60;
            const countdownElement = document.getElementById('countdown');
            const resendBtn = document.getElementById('resend-btn');
            const countdownContainer = document.getElementById('countdown-container');
            const alertContainer = document.querySelector('.alert-info');
            const userId = '{{ session("user_id") }}';
            
            // Kiểm tra trạng thái xác thực định kỳ
            const checkVerificationInterval = setInterval(function() {
                fetch(`/check-verification-status/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.verified) {
                            clearInterval(checkVerificationInterval);
                            
                            if (alertContainer) {
                                alertContainer.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <strong class="text-success">
                                                <i class="fas fa-check-circle"></i> 
                                                Tài khoản đã được xác thực thành công!
                                            </strong><br>
                                            <small class="text-muted">Bạn có thể đăng nhập ngay bây giờ</small>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('login.form') }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                                            </a>
                                        </div>
                                    </div>
                                `;
                                alertContainer.className = 'alert alert-success';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi kiểm tra trạng thái xác thực:', error);
                    });
            }, 5000); // Kiểm tra mỗi 5 giây
            
            // Bắt đầu đếm ngược
            const timer = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    countdownContainer.style.display = 'none';
                    resendBtn.style.display = 'block';
                }
            }, 1000);
            
            // Xử lý nút gửi lại email
            resendBtn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const button = this;
                
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang gửi...';
                
                fetch('{{ route("resend.verification.email") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        
                        countdown = 60;
                        countdownElement.textContent = countdown;
                        countdownContainer.style.display = 'block';
                        resendBtn.style.display = 'none';
                        
                        const newTimer = setInterval(function() {
                            countdown--;
                            countdownElement.textContent = countdown;
                            
                            if (countdown <= 0) {
                                clearInterval(newTimer);
                                countdownContainer.style.display = 'none';
                                resendBtn.style.display = 'block';
                            }
                        }, 1000);
                        
                    } else {
                        showAlert('error', data.message);
                        
                        // Xử lý giới hạn hàng ngày
                        if (data.daily_limit_reached) {
                            resendBtn.style.display = 'none';
                            countdownContainer.style.display = 'block';
                            
                            if (data.remaining_time) {
                                countdown = data.remaining_time;
                                countdownElement.textContent = formatTime(countdown);
                                
                                const dailyLimitTimer = setInterval(function() {
                                    countdown--;
                                    countdownElement.textContent = formatTime(countdown);
                                    
                                    if (countdown <= 0) {
                                        clearInterval(dailyLimitTimer);
                                        location.reload();
                                    }
                                }, 1000);
                            }
                        } else if (data.remaining_time) {
                            // Nếu còn thời gian chờ, hiển thị countdown
                            countdown = data.remaining_time;
                            countdownElement.textContent = countdown;
                            countdownContainer.style.display = 'block';
                            resendBtn.style.display = 'none';
                            
                            const waitTimer = setInterval(function() {
                                countdown--;
                                countdownElement.textContent = countdown;
                                
                                if (countdown <= 0) {
                                    clearInterval(waitTimer);
                                    countdownContainer.style.display = 'none';
                                    resendBtn.style.display = 'block';
                                }
                            }, 1000);
                        }
                    }
                })
                .catch(error => {
                    showAlert('error', 'Có lỗi xảy ra khi gửi lại email. Vui lòng thử lại.');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = 'Gửi lại email';
                });
            });
            
            // Hàm hiển thị alert
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                const form = document.getElementById('kt_login_signup_form');
                form.insertAdjacentHTML('beforebegin', alertHtml);
                
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        if (alert.classList.contains(alertClass)) {
                            alert.remove();
                        }
                    });
                }, 5000);
            }

            // Hàm định dạng thời gian (giây thành phút:giây)
            function formatTime(seconds) {
                const hours = Math.floor(seconds / 3600);
                const minutes = Math.floor((seconds % 3600) / 60);
                const remainingSeconds = seconds % 60;
                
                if (hours > 0) {
                    return `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
                } else {
                    return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
                }
            }
        });
    </script>
    @endif
@endsection
