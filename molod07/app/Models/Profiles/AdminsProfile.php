<?php
namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AdminsProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'l_name',
        'f_name',
        'phone',
        'permissions',
    ];

    public function getName(){
        return $this->name . " " . $this->l_name . " " . $this->f_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
