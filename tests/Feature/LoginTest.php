<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_form()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->first('email') === 'Email không tồn tại, vui lòng kiểm tra lại.';
        });
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->first('password') === 'Mật khẩu không chính xác, vui lòng thử lại.';
        });
    }

    public function test_user_cannot_login_with_empty_email()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->first('email') === 'Email là trường bắt buộc.';
        });
    }

    public function test_user_cannot_login_with_invalid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->first('email') === 'Email có định dạng hợp lệ là abc@example.com';
        });
    }

    public function test_user_cannot_login_with_empty_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->first('password') === 'Mật khẩu là trường bắt buộc.';
        });
    }

    public function test_user_can_logout()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    public function test_admin_requires_2fa()
    {
        $admin = User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 0, // Admin
            'two_fa_enable' => 1, // Đã bật 2FA
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/2fa');
        $response->assertSessionHas('2fa_user_id', $admin->id);
        $response->assertSessionHas('2fa_email', $admin->email);
    }

    public function test_admin_without_2fa_enabled()
    {
        $admin = User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 0, // Admin
            'two_fa_enable' => 0, // Chưa bật 2FA
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_does_not_require_2fa()
    {
        $user = User::create([
            'fullname' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 1, // User
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_2fa_form_requires_session()
    {
        $response = $this->get('/2fa');
        $response->assertRedirect('/login');
    }

    public function test_2fa_verification_with_valid_code()
    {
        $admin = User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 0,
            'two_fa_secret' => 'JBSWY3DPEHPK3PXP',
        ]);

        // Set up session
        $this->withSession([
            '2fa_user_id' => $admin->id,
            '2fa_email' => $admin->email,
            '2fa_temp_code' => '123456'
        ]);

        $response = $this->post('/2fa/verify', [
            'code' => '123456'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_2fa_verification_with_invalid_code()
    {
        $admin = User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 0,
            'two_fa_secret' => 'JBSWY3DPEHPK3PXP',
        ]);

        // Set up session
        $this->withSession([
            '2fa_user_id' => $admin->id,
            '2fa_email' => $admin->email,
            '2fa_temp_code' => '123456'
        ]);

        $response = $this->post('/2fa/verify', [
            'code' => '999999'
        ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertGuest();
    }

    public function test_2fa_resend_code()
    {
        $admin = User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 0,
        ]);

        // Set up session
        $this->withSession([
            '2fa_user_id' => $admin->id,
            '2fa_email' => $admin->email,
        ]);

        $response = $this->postJson('/2fa/resend');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Mã 2FA mới đã được gửi qua Google Authenticator.'
        ]);
    }

    public function test_2fa_resend_without_session()
    {
        $response = $this->postJson('/2fa/resend');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'message' => 'Phiên đăng nhập đã hết hạn.'
        ]);
    }

    public function test_account_lockout_after_three_failed_attempts()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        // First failed attempt
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword1',
        ]);
        $response->assertSessionHasErrors(['password']);

        // Second failed attempt
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword2',
        ]);
        $response->assertSessionHasErrors(['password']);

        // Third failed attempt - should lock account
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword3',
        ]);
        
        $response->assertSessionHas('lockout_remaining', 60);
        $response->assertSessionMissing('errors');
    }

    public function test_account_lockout_after_six_failed_attempts()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        // First 3 failed attempts
        for ($i = 1; $i <= 3; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword' . $i,
            ]);
        }

        // Wait for lockout to expire (for testing, we'll clear cache)
        cache()->forget('login.lockout.' . strtolower('test@example.com'));
        cache()->forget('login.lockout.' . strtolower('test@example.com') . '.expires_at');

        // 3 more failed attempts
        for ($i = 4; $i <= 6; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword' . $i,
            ]);
        }

        // Sixth attempt should show error message
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword7',
        ]);
        
        $response->assertSessionHas('error', 'Bạn đã sai quá nhiều lần. Vui lòng đặt lại mật khẩu.');
    }

    public function test_lockout_status_check_endpoint()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        // Lock the account
        $key = 'login.lockout.' . strtolower('test@example.com');
        $expiresAt = now()->addSeconds(60)->timestamp;
        cache()->put($key, true, now()->addSeconds(60));
        cache()->put($key . '.expires_at', $expiresAt, now()->addSeconds(60));

        $response = $this->postJson('/check-lockout-status', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'locked' => true,
            'remaining_time' => 60
        ]);
    }

    public function test_account_unlock_after_countdown()
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true,
        ]);

        // Lock the account with short duration for testing
        $key = 'login.lockout.' . strtolower('test@example.com');
        $expiresAt = now()->addSeconds(2)->timestamp; // 2 seconds for testing
        cache()->put($key, true, now()->addSeconds(2));
        cache()->put($key . '.expires_at', $expiresAt, now()->addSeconds(2));

        // Wait for lockout to expire
        sleep(3);

        // Try to login again
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
} 