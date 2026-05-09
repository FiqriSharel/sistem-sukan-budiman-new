<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class House extends Model
{
    protected $fillable = ['name', 'color', 'description'];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
