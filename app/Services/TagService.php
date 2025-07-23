<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Capsule;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
    public function store(array $data)
    {
        // First check if tag already exists
        $existingTag = $this->findByName($data['name']);
        if ($existingTag) {
            return $existingTag;
        }

        return Tag::create($data);
    }

    public function findByName($name)
    {
        return Tag::where('name', $name)->first();
    }

    public function show($id)
    {
        return Tag::find($id);
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return false;
        }
        return Tag::destroy($id);
    }

    public function index()
    {
        return Tag::all();
    }

    public function attachToCapsule(int $tagId, int $capsuleId)
    {
        $capsule = Capsule::find($capsuleId);
        $tag = Tag::find($tagId);

        if (!$capsule || !$tag) {
            return false;
        }

        // Check if already attached to avoid duplicates
        if (!$capsule->tags()->where('tag_id', $tagId)->exists()) {
            $capsule->tags()->attach($tagId);
        }

        return true;
    }

    public function detachFromCapsule(int $tagId, int $capsuleId)
    {
        $capsule = Capsule::find($capsuleId);
        $tag = Tag::find($tagId);

        if (!$capsule || !$tag) {
            return false;
        }

        Capsule::find($capsuleId)->tags()->detach($tagId);
        return true;
    }

    public function getCapsuleTags(int $capsuleId)
    {
        $capsule = Capsule::find($capsuleId);

        if (!$capsule) {
            return collect();
        }

        return Capsule::find($capsuleId)->tags()->get();
    }
}
