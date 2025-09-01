<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'role',
        'shift',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    function getPoints()
    {
        $points = json_decode($this->role ?? '[]', true)['points'] ?? 0;
        return $points;
    }

    function getRoleTitle()
    {
        $title = json_decode($this->role ?? '[]', true)['title'] ?? null;

        return $title; // Fallback to raw role if not found in roles list
    }
}
