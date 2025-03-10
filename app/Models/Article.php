<?php

namespace App\Models;

use App\Enums\AreaEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'program_id',
        'user_id',
        'area',
        'name',
        'document',
        'image',
        'description',
        'status',
        'reason'
    ];
    public function program():BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
