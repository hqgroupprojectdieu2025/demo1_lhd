<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otps extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'user_id',
        'otp_code',
        'module',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'module' =>'boolean',
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    //Lien ket voi User
    public function user()
    {
        return $this -> belongsTo(User::class);
    }
}
