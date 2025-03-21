<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;
class UserArea extends Model
{
    // use LogsActivity;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'area_id',
    ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //     ->logOnly(['user_id', 'area_id']);
    // }

    public function user(): BelongsTo
    {
       return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
       return $this->belongsTo(Area::class, 'area_id');
    }

}
