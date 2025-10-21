<?php

namespace App\Models;

use App\Models\Profiles\YouthProfile;
use App\Models\Profiles\PartnersProfile;
use App\Models\Profiles\AdminsProfile;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function getFullName()
    {
        return match ($this->role) {
            'youth' => $this->youthProfile->getName(),
            'partner' => $this->partnersProfile->getName(),
            'admin' => $this->adminsProfile->getName(),
            default => "Undefined",
        };
    }

    public function getFirstName()
    {
        return match ($this->role) {
            'youth' => $this->youthProfile->name,
            'partner' => $this->partnersProfile->name,
            'admin' => $this->adminsProfile->name,
            default => "Undefined",
        };
    }


    public function youthProfile()
    {
        return $this->hasOne(YouthProfile::class);
    }

    public function partnersProfile()
    {
        return $this->hasOne(PartnersProfile::class);
    }

    public function adminsProfile()
    {
        return $this->hasOne(AdminsProfile::class);
    }

    public function points()
    {
        return $this->hasMany(Points::class);
    }

    public function getProfile()
    {
        return match ($this->role) {
            'youth' => $this->youthProfile,
            'partner' => $this->partnersProfile,
            'admin' => $this->adminsProfile,
            default => null,
        };
    }

    public function getUserPic()
    {
        $profile = $this->getProfile();
        return $profile->pic;
    }

    public function getProfileOrFail()
    {
        $profile = $this->getProfile();
        if (!$profile) {
            throw new \Exception('Profile not found for role: ' . $this->role);
        }
        return $profile;
    }

    public function getUserId()
    {
        return sprintf('%06d', $this->id);
    }
}
