<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacancyResponse extends Model
{
    protected $fillable = [
        'user_id',
        'vacancy_id',
        'response', // it must be empty, and fills with response from partner
        'resume_path',
        'prefered_contact',
        'cover_letter',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }
}
