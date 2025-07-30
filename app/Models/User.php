<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'user';

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'avatar',
        'phone',
        'dob',
        'address',
        'email_verified_at',
        'account_type',
        'two_fa_enable',
        'two_fa_secret',
        'recovery_code',
        'status',
    ];

    protected $hidden = [
        'password',
        'two_fa_secret',
        'recovery_code',
        'remember_token',
    ];

    protected $cast = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
        'two_fa_enable' => 'boolean',
        'account_type' => 'boolean',
        'status' => 'boolean',
    ];

    public function otps()
    {
        return $this->hasMany(Otps::class);
    }

    public function performedActions()
    {
        return $this->hasMany(UserActionLogs::class, 'performed_by');
    }

    public function targetedByActions()
    {
        return $this->hasMany(UserActionLogs::class, 'target_user_id');
    }

}
