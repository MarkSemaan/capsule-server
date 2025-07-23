<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\TagService;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    use ApiResponse;
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function store(Request $request)
    {
        Log::info('Tag creation request received', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $validatedData = $request->validate([
            "name" => "required|string",
        ]);

        $tag = $this->tagService->store($validatedData);
        Log::info('Tag created:', $tag->toArray());
        return $this->successResponse($tag);
    }

    public function show($id)
    {
        $tag = $this->tagService->show($id);

        if (!$tag) {
            return $this->notFoundResponse('Tag not found');
        }

        return $this->successResponse($tag);
    }

    public function destroy($id)
    {
        $deleted = $this->tagService->destroy($id);

        if (!$deleted) {
            return $this->notFoundResponse('Tag not found');
        }

        return $this->successResponse(null, 'Tag deleted!');
    }

    public function index()
    {
        $tags = $this->tagService->index();

        return $this->successResponse($tags);
    }

    public function findByName(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string'
        ]);

        $tag = $this->tagService->findByName($validatedData['name']);

        if (!$tag) {
            return $this->notFoundResponse('Tag not found');
        }

        return $this->successResponse($tag);
    }

    public function attachToCapsule(Request $request, $capsuleId)
    {
        $validatedData = $request->validate([
            'tag_id' => 'required|exists:tags,id'
        ]);

        Log::info('Attaching tag to capsule:', ['tag_id' => $validatedData['tag_id'], 'capsule_id' => $capsuleId]);

        $attached = $this->tagService->attachToCapsule($validatedData['tag_id'], $capsuleId);

        if (!$attached) {
            Log::error('Failed to attach tag to capsule');
            return $this->notFoundResponse('Capsule or tag not found');
        }

        Log::info('Tag successfully attached to capsule');
        return $this->successResponse(null, 'Tag attached to capsule');
    }

    public function detachFromCapsule($capsuleId, $tagId)
    {
        $detached = $this->tagService->detachFromCapsule($tagId, $capsuleId);

        if (!$detached) {
            return $this->notFoundResponse('Capsule or tag not found');
        }

        return $this->successResponse(null, 'Tag detached from capsule');
    }

    public function getCapsuleTags($capsuleId)
    {
        $tags = $this->tagService->getCapsuleTags($capsuleId);
        return $this->successResponse($tags);
    }
}
