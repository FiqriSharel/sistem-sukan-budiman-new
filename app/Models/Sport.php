<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sport extends Model
{
    protected $fillable = [
        'name',
        'category',
        'max_players_per_group',
        'duration_minutes',
        'group_code',
        'description',
        'rules',
        'equipment',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(SportRegistration::class);
    }
}
