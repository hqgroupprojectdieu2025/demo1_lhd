<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Events\ForgotPassword;
use App\Listeners\SendVerificationEmail;
use App\Listeners\SendForgotEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // UserRegistered::class => [
        //     SendVerificationEmail::class,
        // ],
        // ForgotPassword::class => [
        //     SendForgotEmail::class,
        // ],
    ];

    public function boot(): void
    {
        //
    }
}