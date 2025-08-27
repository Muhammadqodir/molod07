<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'cover',
        'title',
        'short_description',
        'length',
        'episode_numbers',
        'link',
        'status',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
