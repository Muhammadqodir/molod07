<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ministry extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }
}
