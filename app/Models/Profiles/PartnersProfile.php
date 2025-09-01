<?php

namespace App\Models\Profiles;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vacancy;

class PartnersProfile extends Model
{
    protected $fillable = [
        'user_id',
        'pic',
        'org_name',
        'org_address',
        'person_name',
        'person_lname',
        'person_fname',
        'phone',
        'web',
        'telegram',
        'vk',
        'about',
    ];

    public function getName()
    {
        return $this->org_name;
    }

    public function getDirector()
    {
        return trim("{$this->person_name} {$this->person_lname}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMyEventsCount()
    {
        return Event::where('user_id', $this->user_id)->count();
    }

    public function getMyVacanciesCount()
    {
        return Vacancy::where('user_id', $this->user_id)->count();
    }
}
