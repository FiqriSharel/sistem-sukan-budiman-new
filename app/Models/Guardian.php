<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guardian extends Model
{
    protected $fillable = ['name', 'phone', 'relationship'];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
