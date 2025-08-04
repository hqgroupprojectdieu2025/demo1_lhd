<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Admin')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            margin: 0;
        }

        .top-bar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
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
            background: linear-gradient(135deg, #3699FF, #E1F0FF);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .btn-dashboard {
            font-weight: 600;
            color: #3699FF;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-dashboard:hover {
            text-decoration: underline;
        }
    </style>

    @stack('head')
</head>
<body>

    <!-- Header -->
    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>

        <div class="user-menu">
            <div class="user-avatar">
                {{ substr(Auth::user()->fullname, 0, 1) }}
            </div>
            <div>
                <div class="fw-bold">{{ Auth::user()->fullname }}</div>
                <small class="text-muted">{{ Auth::user()->email }}</small>
            </div>
        </div>
    </div>

    <!-- Nội dung của từng trang con -->
    <div class="container mt-4">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
