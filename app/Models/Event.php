<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'type',
        'cover',
        'title',
        'short_description',
        'description',
        'address',
        'settlement',
        'start',
        'end',
        'supervisor_id',
        'supervisor_name',
        'supervisor_l_name',
        'supervisor_phone',
        'supervisor_email',
        'docs',
        'images',
        'videos',
        'web',
        'telegram',
        'vk',
        'roles',
        'status',
        'admin_id',
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
        'docs' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'roles' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSupervisorFullName()
    {
        return trim("{$this->supervisor_name} {$this->supervisor_l_name}");
    }

    public function getPoints()
    {
        $roles = is_string($this->roles) ? json_decode($this->roles, true) : $this->roles;
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (isset($role['points'])) {
                    return $role['points'];
                }
            }
        }
        return 0;
    }

    public function getAddress()
    {
        return "{$this->settlement} {$this->address}";
    }

    public function getRolesAsList(){
        return is_string($this->roles) ? json_decode($this->roles, true) : $this->roles;
    }
}
