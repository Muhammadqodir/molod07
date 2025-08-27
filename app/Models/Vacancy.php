<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'description',
        'salary_from',
        'salary_to',
        'salary_negotiable',
        'type',
        'experience',
        'org_name',
        'org_phone',
        'org_email',
        'org_address',
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
