<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'farm_user')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }
}
