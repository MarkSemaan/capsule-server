<?php

namespace App\Services;

use App\Models\User;
use App\Models\Capsule;
use App\Models\CapsuleMedia;

class CapsuleMediaService
{
    public function  store(array $data)
    {
        return CapsuleMedia::create($data);
    }
    public function show(int $id)
    {
        return CapsuleMedia::find($id);
    }
    public function getCapsuleMedia(int $capsuleId)
    {
        return CapsuleMedia::where('capsule_id', $capsuleId)->get();
    }
    public function destroy(int $id)
    {
        $media = CapsuleMedia::find($id);
        if (!$media) {
            return false;
        }
        return $media->delete();
    }
}
