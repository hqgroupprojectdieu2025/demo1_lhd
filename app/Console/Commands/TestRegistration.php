<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

class TestRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test registration functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing registration process...');
        
        try {
            // Tạo user test
            $user = User::create([
                'fullname' => 'Test User',
                'email' => $email,
                'password' => Hash::make('Test123!@#'),
                'verification_token' => Str::random(64),
                'status' => false,
            ]);

            $this->info("User created with ID: " . $user->id);

            // Tạo URL xác thực
            $url = URL::temporarySignedRoute(
                'verify.email',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => $user->verification_token]
            );

            $this->info("Verification URL created: " . $url);

            // Gửi email
            Mail::to($user->email)->send(new VerifyEmail($user, $url));
            $this->info("Email sent successfully to: " . $user->email);

            // Hiển thị thông tin để test
            $this->info("\n=== TEST INFORMATION ===");
            $this->info("User ID: " . $user->id);
            $this->info("Email: " . $user->email);
            $this->info("Verification Token: " . $user->verification_token);
            $this->info("Verification URL: " . $url);
            $this->info("========================");

            $this->info("\nTo clean up, run: php artisan test:cleanup " . $user->id);

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
