<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSendLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'type',
        'verification_token',
        'sent_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function getDailySendCount($email, $type = 'verification')
    {
        return self::where('email', $email)
            ->where('type', $type)
            ->whereDate('sent_at', now()->toDateString())
            ->count();
    }

    public static function canSendEmail($email, $type = 'verification', $dailyLimit = 10)
    {
        $dailyCount = self::getDailySendCount($email, $type);
        return $dailyCount < $dailyLimit;
    }

    public static function getRemainingSends($email, $type = 'verification', $dailyLimit = 10)
    {
        $dailyCount = self::getDailySendCount($email, $type);
        return max(0, $dailyLimit - $dailyCount);
    }
}
