<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\VerificationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail 
{
    public function handle(UserRegistered $event): void
    {
        // Gửi email xác nhận
        Mail::to($event->user->email)->send(new VerificationEmail($event->user));
    }

    public function failed(UserRegistered $event, $exception): void
    {
        // Xử lý khi gửi email thất bại
        \Log::error('Failed to send verification email', [
            'user_id' => $event->user->id,
            'error' => $exception->getMessage()
        ]);
    }
}