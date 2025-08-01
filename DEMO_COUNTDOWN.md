# Demo chức năng đếm ngược khi tài khoản bị khóa

## Cách test chức năng đếm ngược

### 1. Tạo tài khoản test

```bash
php artisan test:registration demo@example.com
```

### 2. Test đăng nhập sai 3 lần

#### Bước 1: Truy cập trang đăng nhập

```
http://localhost:8000/login
```

#### Bước 2: Đăng nhập sai lần 1

-   Email: demo@example.com
-   Password: wrongpassword1
-   Kết quả: Hiển thị "Mật khẩu không chính xác, vui lòng thử lại."

#### Bước 3: Đăng nhập sai lần 2

-   Email: demo@example.com
-   Password: wrongpassword2
-   Kết quả: Hiển thị "Mật khẩu không chính xác, vui lòng thử lại."

#### Bước 4: Đăng nhập sai lần 3

-   Email: demo@example.com
-   Password: wrongpassword3
-   Kết quả:
    -   Hiển thị thông báo "Bạn đã sai 3 lần. Vui lòng thử lại sau 60 giây." trong alert màu vàng
    -   Form bị khóa (disabled)
    -   Hiển thị đếm ngược từ 60 giây
    -   Nút "Sign In" chuyển thành "Đang khóa..." với icon spinner
    -   Không có thông báo lỗi dưới ô email

### 3. Quan sát đếm ngược

#### Hiển thị đếm ngược:

-   Thời gian đếm ngược hiển thị trong alert màu vàng
-   Format: "Tài khoản bị khóa. Vui lòng thử lại sau X giây"
-   Số giây còn lại được cập nhật mỗi giây

#### Trạng thái form:

-   Tất cả input fields bị disabled
-   Tất cả buttons bị disabled
-   Nút "Sign In" hiển thị "Đang khóa..." với icon spinner

### 4. Khi đếm ngược kết thúc

#### Tự động mở khóa:

-   Form được enable lại
-   Input fields được enable
-   Buttons được enable
-   Nút "Sign In" trở về trạng thái bình thường

#### Thông báo thành công:

-   Hiển thị alert màu xanh: "Tài khoản đã được mở khóa. Bạn có thể đăng nhập ngay bây giờ."
-   Alert tự động biến mất sau 5 giây

### 5. Test đăng nhập thành công sau khi mở khóa

#### Đăng nhập với thông tin đúng:

-   Email: demo@example.com
-   Password: Test123!@#
-   Kết quả: Chuyển đến dashboard với thông báo "Đăng nhập thành công!"

## Tính năng bổ sung

### 1. Kiểm tra trạng thái khóa khi nhập email

-   Khi người dùng nhập email, hệ thống tự động kiểm tra trạng thái khóa
-   Nếu tài khoản bị khóa, hiển thị đếm ngược ngay lập tức
-   Nếu tài khoản không bị khóa, form hoạt động bình thường

### 2. API kiểm tra trạng thái khóa

```bash
curl -X POST http://localhost:8000/check-lockout-status \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"email": "demo@example.com"}'
```

Response:

```json
{
    "locked": true,
    "remaining_time": 45
}
```

### 3. Test qua command line

```bash
# Test đăng nhập với tài khoản bị khóa
php artisan test:login demo@example.com Test123!@#
```

## Lưu ý quan trọng

1. **Cache driver**: Đảm bảo cache driver hoạt động đúng (file, redis, memcached)
2. **Session**: Đảm bảo session driver hoạt động đúng
3. **JavaScript**: Đảm bảo JavaScript được enable trong browser
4. **CSRF token**: Đảm bảo CSRF token được include trong requests

## Troubleshooting

### 1. Đếm ngược không hoạt động

-   Kiểm tra JavaScript console trong browser
-   Kiểm tra network tab để xem API calls
-   Kiểm tra cache driver trong `.env`

### 2. Form không được mở khóa

-   Kiểm tra cache có được clear đúng không
-   Kiểm tra thời gian server có đồng bộ không
-   Restart queue worker: `php artisan queue:restart`

### 3. API không trả về đúng response

-   Kiểm tra route có được đăng ký đúng không
-   Kiểm tra CSRF token có hợp lệ không
-   Kiểm tra cache key có đúng format không
