# Hướng dẫn chạy test chức năng đăng nhập

## Chạy test tự động

### 1. Chạy tất cả test

```bash
php artisan test
```

### 2. Chạy test đăng nhập cụ thể

```bash
php artisan test tests/Feature/LoginTest.php
```

### 3. Chạy test với coverage

```bash
php artisan test --coverage
```

## Test thủ công

### 1. Test đăng ký user

```bash
php artisan test:registration test@example.com
```

### 2. Test đăng nhập

```bash
php artisan test:login test@example.com Test123!@#
```

## Test qua giao diện web

### 1. Truy cập trang đăng nhập

```
http://localhost:8000/login
```

### 2. Test các trường hợp

#### Trường hợp 1: Đăng nhập thành công

-   Email: test@example.com
-   Password: Test123!@#
-   Kết quả: Chuyển đến dashboard với thông báo "Đăng nhập thành công!"

#### Trường hợp 2: Email không tồn tại

-   Email: nonexistent@example.com
-   Password: anypassword
-   Kết quả: Hiển thị "Email không tồn tại, vui lòng kiểm tra lại."

#### Trường hợp 3: Mật khẩu sai

-   Email: test@example.com
-   Password: wrongpassword
-   Kết quả: Hiển thị "Mật khẩu không chính xác, vui lòng thử lại."

#### Trường hợp 4: Email sai định dạng

-   Email: invalid-email
-   Password: anypassword
-   Kết quả: Hiển thị "Email có định dạng hợp lệ là abc@example.com"

#### Trường hợp 5: Bỏ trống trường bắt buộc

-   Email: (để trống)
-   Password: (để trống)
-   Kết quả: Hiển thị "Email là trường bắt buộc" và "Mật khẩu là trường bắt buộc"

#### Trường hợp 6: Rate limiting

-   Thử đăng nhập sai 3 lần liên tiếp
-   Kết quả: Tài khoản bị khóa 60 giây
-   Thử đăng nhập sai thêm 3 lần nữa
-   Kết quả: Yêu cầu đặt lại mật khẩu

## Kiểm tra log

### 1. Xem log Laravel

```bash
tail -f storage/logs/laravel.log
```

### 2. Xem log cache

```bash
php artisan cache:clear
```

## Troubleshooting

### 1. Test không chạy được

-   Kiểm tra database connection
-   Chạy migration: `php artisan migrate`
-   Clear cache: `php artisan cache:clear`

### 2. Rate limiting không hoạt động

-   Kiểm tra cache driver trong `.env`
-   Restart queue worker: `php artisan queue:restart`

### 3. Session không hoạt động

-   Kiểm tra session driver trong `.env`
-   Clear session: `php artisan session:clear`

## Kết quả mong đợi

### Test tự động

-   Tất cả test phải pass (green)
-   Coverage > 80%

### Test thủ công

-   Form validation hoạt động đúng
-   Rate limiting hoạt động đúng
-   Session management hoạt động đúng
-   Redirect hoạt động đúng
-   Error messages hiển thị đúng
