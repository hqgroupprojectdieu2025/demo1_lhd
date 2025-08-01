<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin {email} {password}';
    protected $description = 'Create an admin account';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Kiểm tra email đã tồn tại chưa
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Tạo admin user
        $user = User::create([
            'fullname' => 'Admin User',
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'status' => true,
            'account_type' => 0, // Admin
            'two_fa_enable' => 0, // Chưa bật 2FA
        ]);

        $this->info("✅ Admin account created successfully!");
        $this->info("Email: {$user->email}");
        $this->info("Account Type: Admin (0)");
        $this->info("2FA Enabled: No (0)");
        $this->info("Password: {$password}");

        return 0;
    }
} 