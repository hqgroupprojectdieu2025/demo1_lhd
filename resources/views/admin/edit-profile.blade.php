@extends('layouts.master')

@section('title', 'Sửa thông tin cá nhân')

@section('content')
<!--begin::Content-->
<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
    <div class="d-flex flex-column-fluid flex-center">
        <div class="login-form">
            <!--begin::Title-->
            <div class="pb-13 pt-lg-0 pt-5">
                <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Sửa thông tin cá nhân</h3>
                <p class="text-muted font-weight-bold font-size-h4">Cập nhật thông tin tài khoản của bạn</p>
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

            <!--begin::Form-->
            <form class="form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!--begin::Avatar Section-->
                <div class="card card-custom mb-8">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-camera text-primary mr-2"></i>
                            Ảnh đại diện
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" 
                                             alt="Avatar" 
                                             class="rounded-circle mb-3" 
                                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #E4E6EF;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mb-3" 
                                             style="width: 150px; height: 150px; border: 4px solid #E4E6EF;">
                                            <span class="text-white font-size-h1 font-weight-bold">{{ substr($user->fullname, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="file" 
                                           name="avatar" 
                                           class="form-control @error('avatar') is-invalid @enderror"
                                           accept="image/*">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Chọn ảnh mới (JPG, PNG, GIF - tối đa 2MB)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Avatar Section-->

                <!--begin::Personal Info Section-->
                <div class="card card-custom mb-8">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user text-primary mr-2"></i>
                            Thông tin cá nhân
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Họ và tên *</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('fullname') is-invalid @enderror" 
                                           type="text" 
                                           name="fullname" 
                                           value="{{ old('fullname', $user->fullname) }}" 
                                           placeholder="Nhập họ và tên"
                                           required />
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                                           type="email" 
                                           value="{{ $user->email }}" 
                                           disabled />
                                    <small class="form-text text-muted">
                                        Email không thể thay đổi vì là định danh chính
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Số điện thoại</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('phone') is-invalid @enderror" 
                                           type="text" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}" 
                                           placeholder="Nhập số điện thoại" />
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Ngày sinh</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('dob') is-invalid @enderror" 
                                           type="date" 
                                           name="dob" 
                                           value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}" />
                                    @error('dob')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Địa chỉ</label>
                            <textarea class="form-control form-control-solid h-auto py-7 px-6 rounded-lg @error('address') is-invalid @enderror" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Nhập địa chỉ">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!--end::Personal Info Section-->

                <!--begin::Action Buttons-->
                <div class="d-flex flex-wrap justify-content-center">
                    <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">
                        <i class="fas fa-save mr-2"></i>
                        Lưu thay đổi
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay lại
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary font-weight-bolder font-size-h6 px-8 py-4 my-3">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </div>
                <!--end::Action Buttons-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Content-->
@endsection 