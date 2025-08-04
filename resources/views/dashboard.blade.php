<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #3699FF;
            --secondary-color: #E1F0FF;
            --success-color: #1BC5BD;
            --warning-color: #FFA800;
            --danger-color: #F64E60;
            --dark-color: #181C32;
            --light-color: #F3F6F9;
            --border-color: #E4E6EF;
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #374151;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .sidebar-header h3 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-header p {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
            color: white;
        }
        
        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left-color: white;
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .top-bar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .content-area {
            padding: 2rem;
        }
        
        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .card-header h4 {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }
        
        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .stat-icon.success {
            background: linear-gradient(135deg, var(--success-color), #34d399);
        }
        
        .stat-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #fbbf24);
        }
        
        .stat-icon.info {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
        }
        
        .stat-title {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-change {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: var(--success-color);
        }
        
        .stat-change.negative {
            color: var(--danger-color);
        }
        
        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 1rem;
        }
        
        /* Tables */
        .table {
            margin: 0;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: #f8fafc;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #5BB3FF);
            color: white;
            box-shadow: 0 4px 15px rgba(54, 153, 255, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(54, 153, 255, 0.4);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #FFB84D);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 168, 0, 0.3);
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 168, 0, 0.4);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #FF6B7A);
            color: white;
            box-shadow: 0 4px 15px rgba(246, 78, 96, 0.3);
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(246, 78, 96, 0.4);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, var(--success-color), #4DD4C8);
            color: white;
            box-shadow: 0 4px 15px rgba(27, 197, 189, 0.3);
        }
        
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(27, 197, 189, 0.4);
            color: white;
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--primary-color), #5BB3FF);
            color: white;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
        }
        
        .badge-success {
            background: linear-gradient(135deg, var(--success-color), #4DD4C8);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color), #FFB84D);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-area {
                padding: 1rem;
            }
        }
        
        /* Toggle Button */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="#dashboard" class="menu-item active" data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="#users" class="menu-item" data-section="users">
                <i class="fas fa-users"></i>
                Quản lý tài khoản
            </a>
            <a href="#security" class="menu-item" data-section="security">
                <i class="fas fa-shield-alt"></i>
                Bảo mật
            </a>
            <a href="#profile" class="menu-item" data-section="profile">
                <i class="fas fa-user"></i>
                Thông tin cá nhân
            </a>
            <a href="#settings" class="menu-item" data-section="settings">
                <i class="fas fa-cog"></i>
                Cài đặt
            </a>
            <a href="#reports" class="menu-item" data-section="reports">
                <i class="fas fa-chart-bar"></i>
                Báo cáo
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title mb-0">Dashboard</h1>
            </div>
            
            <div class="user-menu">
                <div class="user-avatar">
                    {{ substr(Auth::user()->fullname, 0, 1) }}
                </div>
                <div>
                    <div class="fw-bold">{{ Auth::user()->fullname }}</div>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link text-dark" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-edit me-2"></i>Sửa thông tin</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('password.change.form') }}"><i class="fas fa-key me-2"></i>Đổi mật khẩu</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
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
            
            <!-- Users Section -->
            <div id="users-section" class="section-content">
                <div class="dashboard-card card shadow-sm rounded">
                    <div class="card-body text-center py-5">
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-users me-2"></i>Quản lý tài khoản người dùng
                        </a>
                    </div>
                </div>
            </div>


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
            
            <!-- Settings Section -->
            <div id="settings-section" class="section-content" style="display: none;">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4><i class="fas fa-cog me-2"></i>Cài đặt hệ thống</h4>
                    </div>
                    <div class="card-body">
                        <p>Chức năng cài đặt hệ thống sẽ được phát triển trong tương lai.</p>
                    </div>
                </div>
            </div>
            
            <!-- Profile Section -->
            <div id="profile-section" class="section-content" style="display: none;">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4><i class="fas fa-user me-2"></i>Thông tin cá nhân</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                         alt="Avatar" 
                                         class="rounded-circle mb-3" 
                                         style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #E4E6EF;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mb-3" 
                                         style="width: 120px; height: 120px; border: 4px solid #E4E6EF;">
                                        <span class="text-white font-size-h2 font-weight-bold">{{ substr(Auth::user()->fullname, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h5 class="font-weight-bolder">{{ Auth::user()->fullname }}</h5>
                                <span class="badge badge-primary">
                                    @if(Auth::user()->account_type == 0)
                                        <i class="fas fa-crown mr-1"></i>Admin
                                    @else
                                        <i class="fas fa-user mr-1"></i>User
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold text-muted">Email:</label>
                                        <p class="font-weight-bolder text-dark">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold text-muted">Số điện thoại:</label>
                                        <p class="font-weight-bolder text-dark">{{ Auth::user()->phone ?: 'Chưa cập nhật' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold text-muted">Ngày sinh:</label>
                                        <p class="font-weight-bolder text-dark">{{ Auth::user()->dob ? Auth::user()->dob->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold text-muted">Ngày tham gia:</label>
                                        <p class="font-weight-bolder text-dark">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('profile.show') }}" class="btn btn-primary mr-3">
                                        <i class="fas fa-eye mr-2"></i>Xem chi tiết
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                                        <i class="fas fa-edit mr-2"></i>Sửa thông tin
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reports Section -->
            <div id="reports-section" class="section-content" style="display: none;">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar me-2"></i>Báo cáo thống kê</h4>
                    </div>
                    <div class="card-body">
                        <p>Chức năng báo cáo thống kê sẽ được phát triển trong tương lai.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Menu Navigation
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all menu items
                document.querySelectorAll('.menu-item').forEach(menuItem => {
                    menuItem.classList.remove('active');
                });
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Hide all sections
                document.querySelectorAll('.section-content').forEach(section => {
                    section.style.display = 'none';
                });
                
                // Show selected section
                const sectionId = this.getAttribute('data-section') + '-section';
                document.getElementById(sectionId).style.display = 'block';
                
                // Update page title
                document.querySelector('.page-title').textContent = this.textContent.trim();
            });
        });
        
        // Charts
        // Login Chart
        const loginCtx = document.getElementById('loginChart').getContext('2d');
        new Chart(loginCtx, {
            type: 'line',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                datasets: [{
                    label: 'Đăng nhập thành công',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: '#3699FF',
                    backgroundColor: 'rgba(54, 153, 255, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Đăng nhập thất bại',
                    data: [28, 48, 40, 19, 86, 27, 90],
                    borderColor: '#F64E60',
                    backgroundColor: 'rgba(246, 78, 96, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // User Distribution Chart
        const userCtx = document.getElementById('userChart').getContext('2d');
        new Chart(userCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'User', 'Chưa xác thực'],
                datasets: [{
                    data: [
                        {{ \App\Models\User::where('account_type', 0)->count() }},
                        {{ \App\Models\User::where('account_type', 1)->count() }},
                        {{ \App\Models\User::whereNull('email_verified_at')->count() }}
                    ],
                    backgroundColor: [
                        '#3699FF',
                        '#1BC5BD',
                        '#FFA800'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html> 