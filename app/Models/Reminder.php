<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reminder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'note',
        'remind_at',
        'isSent',
        'sent_at',
    ];
    // cast the remind_at and sent_at fields to datetime and isSent to boolean
    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at'   => 'datetime',
        'isSent'   => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected static function booted(): void
    {
        static::creating(function ($reminder) {
            $reminder->user_id ??= Auth::id();
        });
    }
}
