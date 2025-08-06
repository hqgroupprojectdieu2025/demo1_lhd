<?php

namespace App\Listeners;

use App\Events\ForgotPassword;
use App\Mail\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendForgotEmail implements ShouldQueue
{
    use InteractsWithQueue;

    // public string $url;
    public function handle(ForgotPassword $event): void
    {
        // Gửi email xác nhận
        Mail::to($event->user->email)->send(new ResetPassword($event->user, $event->resetUrl));
    }

    public function failed(ForgotPassword $event, $exception): void
    {
        // Xử lý khi gửi email thất bại
        \Log::error('Failed to send verification email', [
            'user_id' => $event->user->id,
            'error' => $exception->getMessage()
        ]);
    }
}