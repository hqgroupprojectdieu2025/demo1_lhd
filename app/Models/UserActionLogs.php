<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActionLogs extends Model
{
    protected $table = 'user_action_logs';

    protected $fillable = [
        'performed_by',
        'target_user_id',
        'performed_at',
        'action_description',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function performer(): BelongsTo
    {
        return $this -> belongsTo(User::class, 'performed_by');
    }

    public function targetUser(): BelongsTo
    {
        return $this -> belongsTo(User::class, 'target_user_id');
    }
}
