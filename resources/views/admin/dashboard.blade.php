@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- Dashboard Section -->
            <div id="dashboard-section" class="section-content">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-title">Tổng người dùng</div>
                        <div class="stat-value">{{ \App\Models\User::count() }}</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>+12% tháng này
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-title">Đã xác thực</div>
                        <div class="stat-value">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }}</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>+8% tháng này
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="stat-title">2FA đã bật</div>
                        <div class="stat-value">{{ \App\Models\User::where('two_fa_enable', 1)->count() }}</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up me-1"></i>+15% tháng này
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon info">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="stat-title">Admin</div>
                        <div class="stat-value">{{ \App\Models\User::where('account_type', 0)->count() }}</div>
                        <div class="stat-change">
                            <i class="fas fa-minus me-1"></i>Không đổi
                        </div>
                    </div>
                </div>   
            </div>