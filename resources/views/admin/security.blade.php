@extends('layouts.kp')

@section('title', 'Bảo mật')

@section('content')
<!-- Security Section -->
<div id="security-section" class="section-content" style="display: none;">
    <div class="dashboard-card">
        <div class="card-header">
            <h4><i class="fas fa-shield-alt me-2"></i>Bảo mật hệ thống</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>2FA Settings</h5>
                    <p>Quản lý xác thực 2 yếu tố cho tài khoản admin.</p>
                    @if(Auth::user()->account_type == 0 && Auth::user()->two_fa_enable == 0)
                        <a href="{{ route('2fa.setup') }}" class="btn btn-info">
                            <i class="fas fa-shield-alt"></i>
                            Cài đặt 2FA
                        </a>
                    @else
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Đã bật 2FA
                        </span>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5>Đổi mật khẩu</h5>
                    <p>Thay đổi mật khẩu để tăng cường bảo mật tài khoản.</p>
                    <a href="{{ route('password.change.form') }}" class="btn btn-warning">
                        <i class="fas fa-key"></i>
                        Đổi mật khẩu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>