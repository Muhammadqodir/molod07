<?php

namespace App\Models;

use App\Traits\HasInteractions;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasInteractions;

    protected $fillable = [
        'user_id',
        'category',
        'cover',
        'title',
        'short_description',
        'description',
        'address',
        'settlement',
        'deadline',
        'docs',
        'web',
        'telegram',
        'vk',
        'conditions',
        'requirements',
        'reward',
        'status',
        'admin_id',
    ];

    protected $casts = [
        'deadline' => 'date',
        'docs' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
