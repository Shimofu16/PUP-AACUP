<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'name',
    ];

    public function parameters(): HasMany
    {
        return $this->hasMany(AreaParameter::class, 'area_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserArea::class, 'area_id');
    }
}
