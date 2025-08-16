<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
}
