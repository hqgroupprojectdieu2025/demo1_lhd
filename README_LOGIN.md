# Hướng dẫn sử dụng chức năng Đăng nhập

## Tổng quan

Chức năng đăng nhập đã được hoàn thiện với các tính năng bảo mật và trải nghiệm người dùng tốt.

## Các tính năng chính

### 1. Validation và thông báo lỗi

-   **Email bắt buộc**: Hiển thị "Email là trường bắt buộc"
-   **Định dạng email**: Hiển thị "Email có định dạng hợp lệ là abc@example.com"
-   **Mật khẩu bắt buộc**: Hiển thị "Mật khẩu là trường bắt buộc"
-   **Email không tồn tại**: Hiển thị "Email không tồn tại, vui lòng kiểm tra lại"
-   **Mật khẩu sai**: Hiển thị "Mật khẩu không chính xác, vui lòng thử lại"

### 2. Bảo mật đăng nhập

-   **Rate limiting**: Giới hạn số lần đăng nhập sai
-   **Khóa tài khoản**: Sau 3 lần sai liên tiếp, khóa 60 giây với đếm ngược thời gian thực
-   **Yêu cầu đặt lại mật khẩu**: Sau 6 lần sai, yêu cầu đặt lại mật khẩu
-   **Đếm ngược thời gian thực**: Hiển thị thời gian còn lại khi tài khoản bị khóa
-   **Tự động mở khóa**: Form tự động được mở khóa khi hết thời gian

### 3. Trải nghiệm người dùng

-   **Thông báo thành công**: "Đăng nhập thành công!"
-   **Chuyển hướng**: Sau đăng nhập thành công, chuyển đến dashboard
-   **Hiển thị thông tin user**: Dashboard hiển thị thông tin tài khoản

## Cách sử dụng

### 1. Truy cập trang đăng nhập

```
GET /login
```

### 2. Đăng nhập

```
POST /login
```

**Parameters:**

-   `email`: Email người dùng
-   `password`: Mật khẩu

### 3. Đăng xuất

```
POST /logout
```

### 4. Truy cập dashboard (cần đăng nhập)

```
GET /dashboard
```

## Testing

### Test đăng nhập qua command line

```bash
php artisan test:login {email} {password}
```

### Test đăng ký qua command line

```bash
php artisan test:registration {email}
```

## Cấu trúc file

### Controllers

-   `app/Http/Controllers/Auth/LoginController.php`: Xử lý logic đăng nhập
-   `app/Http/Controllers/Auth/SignupController.php`: Xử lý logic đăng ký

### Requests

-   `app/Http/Requests/LoginRequest.php`: Validation cho form đăng nhập
-   `app/Http/Requests/SignupRequest.php`: Validation cho form đăng ký

### Views

-   `resources/views/admin/login.blade.php`: Giao diện đăng nhập
-   `resources/views/admin/register.blade.php`: Giao diện đăng ký
-   `resources/views/welcome.blade.php`: Dashboard

### Routes

-   `routes/web.php`: Định nghĩa các route

## Bảo mật

### Rate Limiting

-   Sử dụng Laravel RateLimiter để giới hạn số lần đăng nhập sai
-   Cache được sử dụng để lưu trữ thông tin khóa tài khoản

### Session Security

-   Session được regenerate sau khi đăng nhập thành công
-   CSRF protection được bật cho tất cả form

### Password Security

-   Mật khẩu được hash bằng bcrypt
-   Validation chặt chẽ cho định dạng email và mật khẩu

## Lưu ý quan trọng

1. **Email verification**: Tài khoản cần được xác thực email trước khi có thể đăng nhập
2. **Rate limiting**: Hệ thống sẽ khóa tài khoản sau 3 lần đăng nhập sai
3. **Session management**: Session được quản lý an toàn với regenerate và invalidate
4. **Error handling**: Tất cả lỗi được xử lý và hiển thị thông báo phù hợp

## Troubleshooting

### Lỗi thường gặp

1. **"Email không tồn tại"**

    - Kiểm tra email đã được đăng ký chưa
    - Đảm bảo đã xác thực email

2. **"Mật khẩu không chính xác"**

    - Kiểm tra lại mật khẩu
    - Đảm bảo không có khoảng trắng thừa

3. **"Bạn đã sai 3 lần"**

    - Đợi 60 giây để tài khoản được mở khóa
    - Xem đếm ngược thời gian thực trên giao diện
    - Form sẽ tự động được mở khóa khi hết thời gian
    - Hoặc đặt lại mật khẩu nếu sai quá nhiều lần

4. **Không thể truy cập dashboard**
    - Đảm bảo đã đăng nhập thành công
    - Kiểm tra session có hoạt động không
