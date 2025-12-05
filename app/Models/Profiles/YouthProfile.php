<?php
namespace App\Models\Profiles;

use App\Models\Points;
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
        return $this->l_name . " " . $this->name . " " . $this->f_name;
    }

    public function getMyPointsSum()
    {
        return Points::where('user_id', $this->user_id)->sum('points');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
