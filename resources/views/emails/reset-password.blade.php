<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #3699FF, #5BB3FF);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #3699FF, #5BB3FF);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Đặt lại mật khẩu</h1>
        <p>Xin chào {{ $user->fullname }}!</p>
    </div>
    
    <div class="content">
        <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
        
        <p>Vui lòng nhấp vào nút bên dưới để đặt lại mật khẩu:</p>
        
        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="btn">Đặt lại mật khẩu</a>
        </div>
        
        <div class="warning">
            <strong>Lưu ý:</strong>
            <ul>
                <li>Liên kết này chỉ có hiệu lực trong 10 phút</li>
                <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                <li>Để bảo mật, vui lòng không chia sẻ liên kết này với người khác</li>
            </ul>
        </div>
        
        <p>Nếu nút không hoạt động, bạn có thể copy và paste link sau vào trình duyệt:</p>
        <p style="word-break: break-all; background: #e9ecef; padding: 10px; border-radius: 5px; font-size: 12px;">
            {{ $resetUrl }}
        </p>
    </div>
    
    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tất cả quyền được bảo lưu.</p>
    </div>
</body>
</html> 