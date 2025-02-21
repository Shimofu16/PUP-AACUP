<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Area extends Model
{
    protected $fillable = [
        'areas',
        'program_ids',
        'user_id'
    ];

    // cast
    protected $casts = [
        'areas' =>  Json::class,
        'program_ids' =>  Json::class,
    ];

    public function getProgramsAttribute()
    {
        $programNames = Program::whereIn('id', $this->program_ids)->pluck('code')->toArray();
        return implode(', ', $programNames);
    }

    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class, 'user_id');
    }
}
