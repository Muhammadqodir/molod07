<?php
namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class YouthProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bday',
        'phone',
        'address',
        'name',
        'l_name',
        'f_name',
        'pic',
        'sex',
        'telegram',
        'vk',
        'citizenship',
        'about',
    ];

    public function getName(){
        return $this->name . " " . $this->l_name . " " . $this->f_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
