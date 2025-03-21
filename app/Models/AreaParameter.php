<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParameter extends Model
{
    protected $fillable = [
        'name',
        'area_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

}
