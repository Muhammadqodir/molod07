<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opportunity extends Model
{
    protected $fillable = [
        'ministry_id',
        'program_name',
        'participation_conditions',
        'implementation_period',
        'required_documents',
        'legal_documents',
        'responsible_person',
    ];

    protected $casts = [
        'legal_documents' => 'array',
        'responsible_person' => 'array',
    ];

    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }
}
