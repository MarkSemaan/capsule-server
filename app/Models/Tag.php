<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        "name",
    ];
    public function capsule()
    {
        return $this->belongsToMany(Capsule::class, "capsule_tags");
    }
}
