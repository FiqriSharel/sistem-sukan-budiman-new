<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SportRegistration extends Model
{
    protected $fillable = ['participant_id', 'sport_id', 'status', 'remarks'];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
