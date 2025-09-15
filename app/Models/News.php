<?php

namespace App\Models;

use App\Traits\HasInteractions;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasInteractions;

    protected $fillable = [
        'user_id',
        'title',
        'short_description',
        'description',
        'cover',
        'category',
        'admin_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
