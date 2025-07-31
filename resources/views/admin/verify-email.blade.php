<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận tài khoản</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Xác nhận tài khoản</h2>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $user->fullname }}</strong>,</p>
            
            <p>Cảm ơn bạn đã đăng ký tài khoản. Để hoàn tất quá trình đăng ký, vui lòng nhấn vào nút bên dưới để xác nhận tài khoản:</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="button">Xác nhận tài khoản</a>
            </p>
            
            <p>Hoặc bạn có thể copy và paste link sau vào trình duyệt:</p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                {{ $url }}
            </p>
            
            <p><strong>Lưu ý:</strong> Liên kết này có hiệu lực trong 60 phút. Sau thời gian này, bạn sẽ cần đăng ký lại.</p>
            
            <p>Nếu bạn không thực hiện đăng ký tài khoản này, vui lòng bỏ qua email này.</p>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} - Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
