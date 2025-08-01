<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Test2FA extends Command
{
    protected $signature = 'test:2fa {email} {password}';
    protected $description = 'Test 2FA functionality with different user types';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $this->info("=== 2FA Test Results ===");
        $this->info("Email: {$user->email}");
        $this->info("Fullname: {$user->fullname}");
        $this->info("Account Type: " . ($user->account_type == 0 ? 'Admin' : 'User'));
        $this->info("2FA Enabled: " . ($user->two_fa_enable == 1 ? 'Yes' : 'No'));
        $this->info("2FA Secret: " . ($user->two_fa_secret ? 'Set' : 'Not set'));

        // Test password verification
        if (Hash::check($password, $user->password)) {
            $this->info("✅ Password is correct");
            
            // Check if 2FA is required
            if ($user->account_type == 0 && $user->two_fa_enable == 1) {
                $this->info("🔐 2FA is REQUIRED for this admin account");
                $this->info("   - Account type: Admin (0)");
                $this->info("   - 2FA enabled: Yes (1)");
                $this->info("   - Login flow: Email/Password → 2FA Form → Dashboard");
            } elseif ($user->account_type == 0 && $user->two_fa_enable == 0) {
                $this->info("⚠️  Admin account but 2FA is NOT enabled");
                $this->info("   - Account type: Admin (0)");
                $this->info("   - 2FA enabled: No (0)");
                $this->info("   - Login flow: Email/Password → Dashboard (No 2FA)");
            } else {
                $this->info("👤 User account - No 2FA required");
                $this->info("   - Account type: User (1)");
                $this->info("   - Login flow: Email/Password → Dashboard");
            }
        } else {
            $this->error("❌ Password is incorrect");
            return 1;
        }

        $this->info("\n=== Recommendations ===");
        if ($user->account_type == 0 && $user->two_fa_enable == 0) {
            $this->warn("Consider enabling 2FA for admin account for better security");
            $this->info("Visit: /2fa/setup to enable 2FA");
        }

        return 0;
    }
} 