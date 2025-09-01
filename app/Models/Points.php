<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    protected $fillable = [
        'user_id',
        'partner_id',
        'event_id',
        'extra',
        'points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
