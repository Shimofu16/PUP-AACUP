<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'image',
        'code',
        'name',
        'description',
    ];

    public function articles():HasMany
    {
        return $this->hasMany(Article::class, 'program_id');
    }
}
