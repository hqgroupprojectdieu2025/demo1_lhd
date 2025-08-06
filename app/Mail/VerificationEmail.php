<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $url;

    public function __construct(User $user, string $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận địa chỉ email của bạn',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin.verify-email',
            with: [
                'user' => $this->user,
                'url' => $this->url,
            ],
        );
    }

    // private function generateVerificationUrl(): string
    // {
    //     return URL::temporarySignedRoute(
    //         'verify.email',
    //         now()->addMinutes(60), // Link hết hạn sau 60 phút
    //         [
    //             'id' => $this->user->getKey(),
    //             'hash' => sha1($this->user->getEmailForVerification()),
    //         ]
    //     );
    // }
}