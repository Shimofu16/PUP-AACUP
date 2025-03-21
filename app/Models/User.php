<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAssignedAreasAttribute()
    {
       $arrayAreas = [];
         foreach ($this->areas as $area) {
              $arrayAreas[] = $area->area->name;
         }
        //  return implode(', ', $arrayAreas);
        return $arrayAreas;
    }

    public function getAssignedProgramsAttribute(){
        $arrayPrograms = [];
        foreach ($this->programs as $program) {
            $arrayPrograms[] = $program->program->name;
        }
        return $arrayPrograms;
    }
    

    public function areas(): HasMany
    {
        return $this->hasMany(UserArea::class, 'user_id');
    }
    public function programs(): HasMany
    {
        return $this->hasMany(UserProgram::class, 'user_id');
    }


}
