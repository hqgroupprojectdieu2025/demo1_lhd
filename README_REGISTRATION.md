# Hướng dẫn sử dụng chức năng đăng ký

## Tổng quan

### 1. Các trường bắt buộc:

-   **Họ và tên**: Loại bỏ khoảng trắng ở 2 đầu dãy ký tự
-   **Email**: Định dạng hợp lệ (abc@example.com)
-   **Mật khẩu**: Tối thiểu 8 ký tự, bao gồm:
    -   Ít nhất 1 chữ in hoa
    -   Ít nhất 1 chữ thường
    -   Ít nhất 1 số
    -   Ít nhất 1 ký tự đặc biệt (@$!%\*?&)
-   **Xác nhận mật khẩu**: Phải khớp với mật khẩu
-   **Đồng ý điều khoản**: Bắt buộc phải tích chọn

### 2. Kiểm tra lỗi và thông báo:

-   Hiển thị thông báo lỗi cụ thể cho từng trường
-   Thông báo "X là trường bắt buộc"
-   Thông báo "Email có định dạng hợp lệ là abc@example.com"
-   Thông báo "Email đã được đăng ký. Vui lòng đăng nhập hoặc dùng email khác."
-   Thông báo thành công khi đăng ký

### 3. Gửi email xác nhận:

-   Tự động gửi email xác nhận sau khi đăng ký thành công
-   Liên kết kích hoạt có hiệu lực trong 60 phút

### 4. Tính năng mới - Đếm ngược và gửi lại email:

-   **Đếm ngược 60 giây**: Hiển thị thời gian chờ trước khi có thể gửi lại email
-   **Nút gửi lại email**: Sau 60 giây, hiển thị nút để gửi lại email xác nhận
-   **Tạo token mới**: Mỗi lần gửi lại sẽ tạo verification token mới
-   **Thông báo real-time**: Hiển thị thông báo thành công/lỗi ngay lập tức
-   **Loading state**: Hiển thị trạng thái đang gửi khi nhấn nút
-   **Giới hạn hàng ngày**: Tối đa 10 lần gửi email/ngày cho mỗi email
-   **Đếm ngược đến ngày mai**: Khi đạt giới hạn, hiển thị thời gian đến ngày tiếp theo

## Cấu hình email

### Development (Sử dụng log):

Hiện tại cấu hình đang sử dụng `log` driver, email sẽ được ghi vào file log thay vì gửi thực tế.

Để xem email trong development:

```bash
Get-Content storage/logs/laravel.log -Tail 50
```

### Production (Gửi email thực tế):

Cập nhật file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Cách sử dụng

### 1. Truy cập trang đăng ký:

```
http://your-domain/register
```

### 2. Điền thông tin:

-   Nhập họ và tên (không có khoảng trắng thừa)
-   Nhập email hợp lệ
-   Nhập mật khẩu theo yêu cầu
-   Xác nhận mật khẩu
-   Tích chọn đồng ý điều khoản

### 3. Xác thực email:

-   Sau khi đăng ký thành công, sẽ hiển thị đếm ngược 60 giây
-   Kiểm tra email đã đăng ký (cả hộp thư và spam folder)
-   Sau 60 giây, nút "Gửi lại email" sẽ xuất hiện
-   Nhấn vào liên kết "Xác nhận tài khoản" trong email
-   Hoặc copy link và paste vào trình duyệt
-   **Khi xác thực thành công**: Countdown sẽ tự động ẩn và hiển thị thông báo thành công với nút đăng nhập

### 4. Gửi lại email:

-   Sau 60 giây, nhấn nút "Gửi lại email"
-   Hệ thống sẽ tạo verification token mới
-   Email mới sẽ được gửi với link xác thực mới
-   Đếm ngược 60 giây sẽ bắt đầu lại

### 5. Đăng nhập:

-   Sau khi xác thực thành công, có thể đăng nhập

## Test Command:

Để test chức năng đăng ký:

```bash
php artisan test:registration test@example.com
```
