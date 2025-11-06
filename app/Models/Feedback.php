<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'subject',
        'message',
        'screenshot',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'Новое',
            'in_progress' => 'В работе',
            'resolved' => 'Решено',
            'closed' => 'Закрыто',
            default => 'Неизвестно'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getSenderNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->getFullName();
        }

        return $this->guest_name ?? 'Гость';
    }

    public function getSenderEmailAttribute(): string
    {
        if ($this->user) {
            return $this->user->email;
        }

        return $this->guest_email ?? 'Не указан';
    }
}
