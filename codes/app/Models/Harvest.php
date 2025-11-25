<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\FarmScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ScopedBy([FarmScope::class])]
class Harvest extends Model
{
    use HasFactory;

    protected $fillable = ['farm_id', 'crop_name', 'quantity', 'harvested_at'];

    protected $casts = [
        'harvested_at' => 'datetime',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
