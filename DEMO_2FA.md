# Demo chức năng 2FA

## Tổng quan

Chức năng 2FA hoạt động theo logic sau:

-   **User (account_type = 1)**: Không cần 2FA
-   **Admin chưa bật 2FA (account_type = 0, two_fa_enable = 0)**: Không cần 2FA
-   **Admin đã bật 2FA (account_type = 0, two_fa_enable = 1)**: Cần xác thực 2FA

## Test Cases

### 1. Test User Account

```bash
# Tạo user account
php artisan test:registration user@example.com

# Test đăng nhập user
php artisan test:2fa user@example.com Test123!@#
```

**Kết quả mong đợi:**

-   Account Type: User (1)
-   2FA Enabled: No
-   Login flow: Email/Password → Dashboard (không cần 2FA)

### 2. Test Admin Account chưa bật 2FA

```bash
# Tạo admin account
php artisan test:registration admin@example.com

# Cập nhật account_type = 0 trong database
# two_fa_enable = 0 (mặc định)

# Test đăng nhập admin chưa bật 2FA
php artisan test:2fa admin@example.com Test123!@#
```

**Kết quả mong đợi:**

-   Account Type: Admin (0)
-   2FA Enabled: No
-   Login flow: Email/Password → Dashboard (không cần 2FA)

### 3. Test Admin Account đã bật 2FA

```bash
# Đăng nhập admin và bật 2FA
# 1. Truy cập /login
# 2. Đăng nhập với admin@example.com
# 3. Vào dashboard
# 4. Nhấn "Cài đặt 2FA"
# 5. Quét QR code với Google Authenticator
# 6. Nhập mã 6 chữ số để bật 2FA

# Test đăng nhập admin đã bật 2FA
php artisan test:2fa admin@example.com Test123!@#
```

**Kết quả mong đợi:**

-   Account Type: Admin (0)
-   2FA Enabled: Yes
-   Login flow: Email/Password → 2FA Form → Dashboard

## Luồng đăng nhập chi tiết

### Case 1: User Account

```
1. Truy cập /login
2. Nhập email: user@example.com
3. Nhập password: Test123!@#
4. Nhấn "Sign In"
5. → Chuyển đến /dashboard (thành công)
```

### Case 2: Admin chưa bật 2FA

```
1. Truy cập /login
2. Nhập email: admin@example.com
3. Nhập password: Test123!@#
4. Nhấn "Sign In"
5. → Chuyển đến /dashboard (thành công)
```

### Case 3: Admin đã bật 2FA

```
1. Truy cập /login
2. Nhập email: admin@example.com
3. Nhập password: Test123!@#
4. Nhấn "Sign In"
5. → Chuyển đến /2fa (form xác thực 2FA)
6. Mở Google Authenticator
7. Nhập mã 6 chữ số hiện tại
8. Nhấn "Xác thực"
9. → Chuyển đến /dashboard (thành công)
```

## Cài đặt 2FA cho Admin

### Bước 1: Đăng nhập Admin

```bash
# Truy cập /login
# Đăng nhập với admin@example.com
```

### Bước 2: Vào trang cài đặt 2FA

```bash
# Trong dashboard, nhấn "Cài đặt 2FA"
# Hoặc truy cập trực tiếp: /2fa/setup
```

### Bước 3: Cài đặt Google Authenticator

1. Tải Google Authenticator từ App Store/Google Play
2. Mở app và nhấn "+"
3. Chọn "Quét mã QR"
4. Hướng camera vào QR code trên trang web
5. Hoặc nhập mã bí mật thủ công

### Bước 4: Xác minh lần đầu

1. Nhập mã 6 chữ số từ Google Authenticator
2. Nhấn "Bật xác thực 2 bước"
3. Nếu đúng → 2FA được bật thành công

## Test Commands

### Test đăng nhập thông thường

```bash
php artisan test:login user@example.com Test123!@#
```

### Test đăng nhập với 2FA

```bash
php artisan test:login admin@example.com Test123!@#
```

### Test thông tin 2FA

```bash
php artisan test:2fa admin@example.com Test123!@#
```

## Database Schema

```sql
-- Bảng users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    status BOOLEAN DEFAULT 1,
    account_type TINYINT DEFAULT 1, -- 0: Admin, 1: User
    two_fa_secret VARCHAR(255) NULL,
    two_fa_enable TINYINT DEFAULT 0, -- 0: Disabled, 1: Enabled
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Validation Rules

### Login Form

-   Email: required, email format
-   Password: required

### 2FA Form

-   Code: required, string, size:6

### Error Messages

-   Email không tồn tại: "Email không tồn tại, vui lòng kiểm tra lại"
-   Mật khẩu sai: "Mật khẩu không chính xác, vui lòng thử lại"
-   Mã 2FA sai: "Mã 2FA không hợp lệ, vui lòng thử lại"

## Security Features

1. **Rate Limiting**: Khóa tài khoản sau 3 lần sai liên tiếp
2. **Session Management**: Session 2FA có thời hạn
3. **Secret Key**: Tự động tạo và mã hóa
4. **QR Code**: Tự động tạo QR code cho Google Authenticator
5. **Backup Codes**: Có thể tạo lại secret key

## Troubleshooting

### Không thể đăng nhập với 2FA

1. Kiểm tra thời gian trên điện thoại có chính xác không
2. Đảm bảo đã quét đúng QR code
3. Thử gửi lại mã 2FA
4. Kiểm tra secret key có đúng không

### Không hiển thị form 2FA

1. Kiểm tra account_type = 0 (Admin)
2. Kiểm tra two_fa_enable = 1 (Đã bật 2FA)
3. Kiểm tra session có hoạt động không

### Không thể bật 2FA

1. Đảm bảo đã đăng nhập với tài khoản admin
2. Kiểm tra Google Authenticator có hoạt động không
3. Thử tạo lại secret key
