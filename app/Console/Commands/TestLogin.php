<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:login {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test login functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $this->info('Testing login process...');
        
        try {
            // Kiểm tra user có tồn tại không
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("Email không tồn tại: " . $email);
                return 1;
            }

            $this->info("User found: " . $user->fullname);

            // Kiểm tra mật khẩu
            if (!Hash::check($password, $user->password)) {
                $this->error("Mật khẩu không chính xác");
                return 1;
            }

            $this->info("Mật khẩu chính xác!");

            // Kiểm tra trạng thái xác thực
            if (!$user->email_verified_at) {
                $this->warn("Tài khoản chưa được xác thực email");
            } else {
                $this->info("Tài khoản đã được xác thực");
            }

            $this->info("\n=== LOGIN TEST SUCCESSFUL ===");
            $this->info("User ID: " . $user->id);
            $this->info("Email: " . $user->email);
            $this->info("Fullname: " . $user->fullname);
            $this->info("Status: " . ($user->status ? 'Active' : 'Inactive'));
            $this->info("Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
            $this->info("=============================");

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
} 