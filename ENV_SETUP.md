# Hướng dẫn cấu hình Environment Variables

## Tạo file .env

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Email Configuration
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Các bước cần thiết:

1. **Tạo APP_KEY:**

    ```bash
    php artisan key:generate
    ```

2. **Chạy migrations:**

    ```bash
    php artisan migrate
    ```

3. **Clear cache:**
    ```bash
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    ```

## Cấu hình Email:

### Development (Sử dụng log):

```env
MAIL_MAILER=log
```

Email sẽ được ghi vào `storage/logs/laravel.log`

### Production (Gửi email thực tế):

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

## Debug Mode:

Để bật debug mode (hiển thị lỗi chi tiết):

```env
APP_DEBUG=true
```

Để tắt debug mode (production):

```env
APP_DEBUG=false
```
