<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapsuleMedia extends Model
{
    protected $fillable = [
        "capsule_id",
        "type",
        "content",
    ];
    public function capsule()
    {
        return $this->belongsTo(Capsule::class);
    }
}
