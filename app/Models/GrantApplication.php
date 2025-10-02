<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantApplication extends Model
{
    protected $fillable = [
        'grant_id', 'user_id', 'name', 'email', 'comment', 'submitted_at'
    ];

    public function grant()
    {
        return $this->belongsTo(Grant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
