<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'note',
        'remind_at',
        'isSent',
        'sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
