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
                        <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Sign Up</h3>
                        <p class="text-muted font-weight-bold font-size-h4">Enter your details to create your account</p>
                    </div>
                    <!--end::Title-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="text" placeholder="Fullname" name="fullname" autocomplete="off" />
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Password" name="password" autocomplete="off" />
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Confirm password" name="password_confirmation" autocomplete="off" />
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group">
                        <label class="checkbox mb-0">
                        <input type="checkbox" name="agree" />I Agree the
                        <a href="#">terms and conditions</a>.
                        <span></span></label>
                    </div>
                    <!--end::Form group-->
                    <!--begin::Form group-->
                    <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                        <button type="submit" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Submit</button>
                        <button type="submit" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Cancel</button>
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
            <a href="#" class="text-primary font-weight-bolder font-size-h5">Terms</a>
            <a href="#" class="text-primary ml-10 font-weight-bolder font-size-h5">Plans</a>
            <a href="#" class="text-primary ml-10 font-weight-bolder font-size-h5">Contact Us</a>
        </div>
        <!--end::Content footer-->
    </div>
    <!--end::Content-->
@endsection
