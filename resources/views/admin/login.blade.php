@extends('layouts.master')

@section('title', 'Đăng nhập')

@section('content')
<!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::Signin-->
                <div class="login-form">
                    <!--begin::Form-->
                    <form class="form"  id="kt_login_form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <!--begin::Title-->
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Chào mừng bạn!</h3>
                            <span class="text-muted font-weight-bold font-size-h4">Bạn chưa có tài khoản?
                            <a href="{{ route('register.form') }}" class="text-primary font-weight-bolder">Đăng ký</a></span>
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

                        <!--begin::Lockout Countdown-->
                        @if(session('lockout_remaining'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert" id="lockout-alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Bạn đã sai 3 lần. Vui lòng thử lại sau <span id="countdown">{{ session('lockout_remaining') }}</span> giây</span>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <!--end::Lockout Countdown-->
                        <!--end::Alert Messages-->

                        <!--begin::Form group - Email-->
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('email') is-invalid @enderror" 
                                type="text" name="email" value="{{ old('email') }}" autocomplete="off" 
                                @if(session('lockout_remaining')) disabled @endif />

                            @error('email')
                                <div class="text-danger mt-2 font-size-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Form group-->

                        <!--begin::Form group - Password-->
                        <div class="form-group">
                            <div class="d-flex justify-content-between mt-n5">
                                <label class="font-size-h6 font-weight-bolder text-dark pt-5">Mật khẩu</label>
                                <a href="{{ route('password.request') }}" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Quên mật khẩu?</a>
                            </div>
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('password') is-invalid @enderror" 
                                type="password" name="password" autocomplete="off" 
                                @if(session('lockout_remaining')) disabled @endif />

                            @error('password')
                                <div class="text-danger mt-2 font-size-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <!--end::Form group-->

                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3" 
                                @if(session('lockout_remaining')) disabled @endif>
                                @if(session('lockout_remaining'))
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Đang khóa...
                                @else
                                    Sign In
                                @endif
                            </button>

                            <button type="button" class="btn btn-light-primary font-weight-bolder px-8 py-4 my-3 font-size-lg" 
                                @if(session('lockout_remaining')) disabled @endif>
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M19.9895 10.1871C19.9895 9.36767 19.9214 8.76973 19.7742 8.14966H10.1992V11.848H15.8195C15.7062 12.7671 15.0943 14.1512 13.7346 15.0813L13.7155 15.2051L16.7429 17.4969L16.9527 17.5174C18.879 15.7789 19.9895 13.221 19.9895 10.1871Z" fill="#4285F4" />
                                        <path d="M10.1993 19.9313C12.9527 19.9313 15.2643 19.0454 16.9527 17.5174L13.7346 15.0813C12.8734 15.6682 11.7176 16.0779 10.1993 16.0779C7.50243 16.0779 5.21352 14.3395 4.39759 11.9366L4.27799 11.9466L1.13003 14.3273L1.08887 14.4391C2.76588 17.6945 6.21061 19.9313 10.1993 19.9313Z" fill="#34A853" />
                                        <path d="M4.39748 11.9366C4.18219 11.3166 4.05759 10.6521 4.05759 9.96565C4.05759 9.27909 4.18219 8.61473 4.38615 7.99466L4.38045 7.8626L1.19304 5.44366L1.08875 5.49214C0.397576 6.84305 0.000976562 8.36008 0.000976562 9.96565C0.000976562 11.5712 0.397576 13.0882 1.08875 14.4391L4.39748 11.9366Z" fill="#FBBC05" />
                                        <path d="M10.1993 3.85336C12.1142 3.85336 13.406 4.66168 14.1425 5.33717L17.0207 2.59107C15.253 0.985496 12.9527 0 10.1993 0C6.2106 0 2.76588 2.23672 1.08887 5.49214L4.38626 7.99466C5.21352 5.59183 7.50242 3.85336 10.1993 3.85336Z" fill="#EB4335" />
                                    </svg>
                                </span>
                                Sign in with Google
                            </button>
                        </div>
                        <!--end::Action-->
                    </form>

                </div>
                <!--end::Signin-->
            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
                <a href="#" class="text-primary font-weight-bolder font-size-h5">Terms</a>
                <a href="#" class="text-primary ml-10 font-weight-bolder font-size-h5">Plans</a>
                <a href="#" class="text-primary ml-10 font-weight-bolder font-size-h5">Contact Us</a>
            </div>
            <!--end::Content footer-->
        </div>
        <!--end::Content-->

        <!--begin::Countdown Script-->
        @if(session('lockout_remaining'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let countdown = {{ session('lockout_remaining') }};
                const countdownElement = document.getElementById('countdown');
                const form = document.getElementById('kt_login_form');
                const inputs = form.querySelectorAll('input');
                const buttons = form.querySelectorAll('button');
                const alertElement = document.getElementById('lockout-alert');

                function updateCountdown() {
                    if (countdown > 0) {
                        countdownElement.textContent = countdown;
                        countdown--;
                        setTimeout(updateCountdown, 1000);
                    } else {
                        // Enable form when countdown reaches 0
                        inputs.forEach(input => input.disabled = false);
                        buttons.forEach(button => button.disabled = false);
                        
                        // Update button text
                        const submitButton = form.querySelector('button[type="submit"]');
                        submitButton.innerHTML = 'Sign In';
                        
                        // Remove alert
                        if (alertElement) {
                            alertElement.remove();
                        }
                        
                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success alert-dismissible fade show';
                        successAlert.innerHTML = `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Tài khoản đã được mở khóa. Bạn có thể đăng nhập ngay bây giờ.</span>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        `;
                        form.parentNode.insertBefore(successAlert, form);
                        
                        // Auto-hide success message after 5 seconds
                        setTimeout(() => {
                            successAlert.remove();
                        }, 5000);
                    }
                }

                updateCountdown();
            });
        </script>
        @endif

        <!--begin::Lockout Check Script-->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const emailInput = document.querySelector('input[name="email"]');
                const passwordInput = document.querySelector('input[name="password"]');
                const submitButton = document.querySelector('button[type="submit"]');
                const googleButton = document.querySelector('button[type="button"]');
                let lockoutCheckTimeout;

                function checkLockoutStatus(email) {
                    if (!email) return;

                    fetch('{{ route("check.lockout.status") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.locked && data.remaining_time > 0) {
                            // Show lockout alert
                            showLockoutAlert(data.remaining_time);
                            disableForm();
                            startCountdown(data.remaining_time);
                        } else {
                            // Enable form if not locked
                            enableForm();
                            hideLockoutAlert();
                        }
                    })
                    .catch(error => {
                        console.error('Error checking lockout status:', error);
                    });
                }

                function showLockoutAlert(remainingTime) {
                    // Remove existing alert
                    hideLockoutAlert();
                    
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-warning alert-dismissible fade show';
                    alertDiv.id = 'lockout-alert';
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Bạn đã sai 3 lần. Vui lòng thử lại sau <span id="countdown">${remainingTime}</span> giây</span>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    
                    const form = document.getElementById('kt_login_form');
                    form.parentNode.insertBefore(alertDiv, form);
                }

                function hideLockoutAlert() {
                    const existingAlert = document.getElementById('lockout-alert');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                }

                function disableForm() {
                    emailInput.disabled = true;
                    passwordInput.disabled = true;
                    submitButton.disabled = true;
                    googleButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang khóa...';
                }

                function enableForm() {
                    emailInput.disabled = false;
                    passwordInput.disabled = false;
                    submitButton.disabled = false;
                    googleButton.disabled = false;
                    submitButton.innerHTML = 'Sign In';
                }

                function startCountdown(remainingTime) {
                    let countdown = remainingTime;
                    const countdownElement = document.getElementById('countdown');
                    
                    function updateCountdown() {
                        if (countdown > 0) {
                            countdownElement.textContent = countdown;
                            countdown--;
                            setTimeout(updateCountdown, 1000);
                        } else {
                            enableForm();
                            hideLockoutAlert();
                            
                            // Show success message
                            const successAlert = document.createElement('div');
                            successAlert.className = 'alert alert-success alert-dismissible fade show';
                            successAlert.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>Tài khoản đã được mở khóa. Bạn có thể đăng nhập ngay bây giờ.</span>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            `;
                            
                            const form = document.getElementById('kt_login_form');
                            form.parentNode.insertBefore(successAlert, form);
                            
                            // Auto-hide success message after 5 seconds
                            setTimeout(() => {
                                successAlert.remove();
                            }, 5000);
                        }
                    }
                    
                    updateCountdown();
                }

                // Check lockout status when email is entered
                emailInput.addEventListener('input', function() {
                    clearTimeout(lockoutCheckTimeout);
                    lockoutCheckTimeout = setTimeout(() => {
                        checkLockoutStatus(this.value);
                    }, 1000); // Delay 1 second after user stops typing
                });

                // Check lockout status on page load if email is pre-filled
                if (emailInput.value) {
                    checkLockoutStatus(emailInput.value);
                }
            });
        </script>
        <!--end::Lockout Check Script-->
        <!--end::Countdown Script-->
@endsection
