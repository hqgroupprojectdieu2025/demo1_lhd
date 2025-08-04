@extends('layouts.master')

@section('title', 'Thông tin cá nhân')

@section('content')
<!--begin::Content-->
<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
    <div class="d-flex flex-column-fluid flex-center">
        <div class="login-form">
            <!--begin::Title-->
            <div class="pb-13 pt-lg-0 pt-5">
                <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Thông tin cá nhân</h3>
                <p class="text-muted font-weight-bold font-size-h4">Xem và quản lý thông tin tài khoản của bạn</p>
            </div>
            <!--end::Title-->

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

            <!--begin::Profile Card-->
            <div class="card card-custom mb-8">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle text-primary mr-2"></i>
                        Thông tin cơ bản
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Avatar Section -->
                        <div class="col-md-4 text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" 
                                         alt="Avatar" 
                                         class="rounded-circle" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #E4E6EF;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px; border: 4px solid #E4E6EF;">
                                        <span class="text-white font-size-h1 font-weight-bold">{{ substr($user->fullname, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-primary">
                                    @if($user->account_type == 0)
                                        <i class="fas fa-crown mr-1"></i>Admin
                                    @else
                                        <i class="fas fa-user mr-1"></i>User
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- User Info Section -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-muted">Họ và tên:</label>
                                    <p class="font-weight-bolder text-dark">{{ $user->fullname }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-muted">Email:</label>
                                    <p class="font-weight-bolder text-dark">{{ $user->email }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-muted">Số điện thoại:</label>
                                    <p class="font-weight-bolder text-dark">{{ $user->phone ?: 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-muted">Ngày sinh:</label>
                                    <p class="font-weight-bolder text-dark">{{ $user->dob ? $user->dob->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold text-muted">Địa chỉ:</label>
                                    <p class="font-weight-bolder text-dark">{{ $user->address ?: 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Profile Card-->

            <!--begin::Account Status Card-->
            <div class="card card-custom mb-8">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt text-primary mr-2"></i>
                        Trạng thái tài khoản
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Trạng thái email:</label>
                            <div class="mt-2">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i>Đã xác thực
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Chưa xác thực
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Xác thực 2FA:</label>
                            <div class="mt-2">
                                @if($user->two_fa_enable)
                                    <span class="badge badge-success">
                                        <i class="fas fa-shield-alt mr-1"></i>Đã bật
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-shield-alt mr-1"></i>Chưa bật
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Ngày tham gia:</label>
                            <p class="font-weight-bolder text-dark">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-muted">Cập nhật lần cuối:</label>
                            <p class="font-weight-bolder text-dark">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Account Status Card-->

            <!--begin::Action Buttons-->
            <div class="d-flex flex-wrap justify-content-center">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">
                    <i class="fas fa-edit mr-2"></i>
                    Sửa thông tin
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại Dashboard
                </a>
                <a href="{{ route('password.change.form') }}" class="btn btn-warning font-weight-bolder font-size-h6 px-8 py-4 my-3">
                    <i class="fas fa-key mr-2"></i>
                    Đổi mật khẩu
                </a>
            </div>
            <!--end::Action Buttons-->
        </div>
    </div>
</div>
<!--end::Content-->
@endsection 