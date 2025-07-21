<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capsule extends Model
{
    protected $fillable = [
        "user_id",
        "message",
        "location",
        "reveal_date",
        "privacy",
        "surprise_mode",
    ];

    protected $casts = [
        'reveal_date' => 'datetime',
        'surprise_mode' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function capsuleMedia()
    {
        return $this->hasMany(CapsuleMedia::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, "capsule_tags");
    }
}
