<?php

namespace App\Services;

use App\Models\Capsule;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CapsuleService
{
    public function store(array $data, User $user)
    {
        return $user->capsules()->create($data);
    }
    public function show($id)
    {
        return Capsule::find($id);
    }
    public function deleteCapsule(int $id, User $user)
    {
        return $user->capsules()->where("id", $id)->delete();
    }
    public function myCapsules(User $user)
    {
        return $user->capsules()->with(['capsuleMedia', 'tags'])->get();
    }
    public function revealedCapsules()
    {
        return Capsule::where('reveal_date', '<=', now())
            ->with(['capsuleMedia', 'tags'])
            ->get();
    }
    public function publicCapsules()
    {
        return Capsule::where('reveal_date', '<=', now())
            ->where('privacy', 'public')
            ->with(['capsuleMedia', 'tags'])
            ->get();
    }
    public function userUpcomingCapsules(User $user)
    {
        return $user->capsules()->where('reveal_date', '>', now())
            ->with(['capsuleMedia', 'tags'])->get();
    }
}
