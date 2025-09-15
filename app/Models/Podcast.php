<?php

namespace App\Models;

use App\Traits\HasInteractions;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasInteractions;
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
