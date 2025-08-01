# Hướng dẫn sử dụng chức năng 2FA (Two-Factor Authentication)

## Tổng quan

Chức năng 2FA đã được tích hợp vào hệ thống đăng nhập với Google Authenticator. Chỉ admin (account_type = 0) **đã bật 2FA** (two_fa_enable = 1) mới cần xác thực 2FA khi đăng nhập.

## Luồng hoạt động

### 1. Đăng nhập cho User (account_type = 1)

-   Nhập email và mật khẩu
-   Đăng nhập thành công → Chuyển đến dashboard
-   Không cần xác thực 2FA

### 2. Đăng nhập cho Admin chưa bật 2FA (account_type = 0, two_fa_enable = 0)

-   Nhập email và mật khẩu
-   Đăng nhập thành công → Chuyển đến dashboard
-   Không cần xác thực 2FA

### 3. Đăng nhập cho Admin đã bật 2FA (account_type = 0, two_fa_enable = 1)

-   Nhập email và mật khẩu
-   Nếu đúng → Chuyển đến form xác thực 2FA
-   Nhập mã 6 chữ số từ Google Authenticator
-   Nếu đúng → Đăng nhập thành công → Chuyển đến dashboard

## Cài đặt 2FA

### Bước 1: Truy cập trang cài đặt

1. Đăng nhập với tài khoản admin
2. Vào dashboard
3. Nhấn nút "Cài đặt 2FA"

### Bước 2: Cài đặt Google Authenticator

1. Tải ứng dụng Google Authenticator từ App Store hoặc Google Play
2. Mở ứng dụng và nhấn dấu "+"
3. Chọn "Quét mã QR" hoặc "Nhập khóa thiết lập thủ công"

### Bước 3: Quét mã QR

1. Hướng camera vào mã QR hiển thị trên trang web
2. Hoặc nhập mã bí mật thủ công: `[SECRET_KEY]`
3. Ứng dụng sẽ hiển thị tên trang web và mã 6 chữ số

### Bước 4: Xác minh lần đầu

1. Nhập mã 6 chữ số từ Google Authenticator
2. Nhấn "Bật xác thực 2 bước"
3. Nếu đúng → 2FA được bật thành công

## Đăng nhập với 2FA

### Bước 1: Đăng nhập thông thường

1. Truy cập trang đăng nhập
2. Nhập email và mật khẩu
3. Nhấn "Sign In"

### Bước 2: Xác thực 2FA

1. Hệ thống chuyển đến form xác thực 2FA
2. Mở Google Authenticator trên điện thoại
3. Nhập mã 6 chữ số hiện tại
4. Nhấn "Xác thực"

### Bước 3: Hoàn thành đăng nhập

-   Nếu mã đúng → Đăng nhập thành công → Chuyển đến dashboard
-   Nếu mã sai → Hiển thị thông báo lỗi

## Tính năng bổ sung

### 1. Gửi lại mã 2FA

-   Nếu không nhận được mã, nhấn "Gửi lại mã 2FA"
-   Hệ thống sẽ tạo mã mới
-   Nút sẽ bị disable trong 30 giây

### 2. Tạo lại mã bí mật

-   Trong trang cài đặt 2FA, nhấn "Tạo lại mã bí mật"
-   Hệ thống sẽ tạo secret key mới
-   Cần cấu hình lại Google Authenticator

### 3. Tắt 2FA

-   Đăng nhập với tài khoản admin
-   Vào trang cài đặt 2FA
-   Nhập mã xác thực để tắt 2FA

## Validation và thông báo lỗi

### 1. Form đăng nhập

-   **Email bắt buộc**: "Email là trường bắt buộc"
-   **Định dạng email**: "Email có định dạng hợp lệ là abc@example.com"
-   **Email không tồn tại**: "Email không tồn tại, vui lòng kiểm tra lại"
-   **Mật khẩu bắt buộc**: "Mật khẩu là trường bắt buộc"
-   **Mật khẩu sai**: "Mật khẩu không chính xác, vui lòng thử lại"

### 2. Form xác thực 2FA

-   **Mã 2FA bắt buộc**: "Mã 2FA là trường bắt buộc"
-   **Định dạng mã**: "Mã 2FA phải có 6 ký tự"
-   **Mã 2FA sai**: "Mã 2FA không hợp lệ, vui lòng thử lại"

## API Endpoints

### 1. Đăng nhập

```
POST /login
```

### 2. Form 2FA

```
GET /2fa
```

### 3. Xác thực 2FA

```
POST /2fa/verify
```

### 4. Gửi lại mã 2FA

```
POST /2fa/resend
```

### 5. Cài đặt 2FA

```
GET /2fa/setup
POST /2fa/enable
POST /2fa/disable
GET /2fa/regenerate
```

## Bảo mật

### 1. Session Management

-   Session 2FA được tạo khi admin đăng nhập thành công
-   Session được xóa sau khi xác thực 2FA thành công
-   Session hết hạn sau 30 phút

### 2. Rate Limiting

-   Giới hạn số lần nhập sai mã 2FA
-   Khóa tài khoản sau 3 lần sai liên tiếp

### 3. Secret Key

-   Secret key được tạo tự động cho admin
-   Secret key được mã hóa trong database
-   Có thể tạo lại secret key khi cần

## Testing

### 1. Test đăng nhập admin

```bash
php artisan test:login admin@example.com password123
```

### 2. Test đăng nhập user

```bash
php artisan test:login user@example.com password123
```

### 3. Chạy test tự động

```bash
php artisan test tests/Feature/LoginTest.php
```

## Troubleshooting

### 1. Không nhận được mã 2FA

-   Kiểm tra thời gian server có đồng bộ không
-   Thử gửi lại mã 2FA
-   Kiểm tra cài đặt Google Authenticator

### 2. Mã 2FA không đúng

-   Đảm bảo thời gian trên điện thoại chính xác
-   Kiểm tra secret key có đúng không
-   Thử tạo lại secret key

### 3. Không thể truy cập trang 2FA

-   Đảm bảo đã đăng nhập với tài khoản admin
-   Kiểm tra session có hoạt động không
-   Thử đăng nhập lại

## Lưu ý quan trọng

1. **Backup codes**: Nên lưu backup codes để khôi phục tài khoản
2. **Multiple devices**: Có thể sử dụng 2FA trên nhiều thiết bị
3. **Time sync**: Đảm bảo thời gian trên thiết bị chính xác
4. **Secret key**: Không chia sẻ secret key với người khác
5. **Recovery**: Có kế hoạch khôi phục tài khoản khi mất thiết bị
