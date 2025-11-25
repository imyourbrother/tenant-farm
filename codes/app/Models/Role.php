<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission; // Assuming Permission model is in the same namespace or App\Models

class Role extends Model
{
    protected $fillable = ['farm_id', 'name', 'slug'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
    //
}
