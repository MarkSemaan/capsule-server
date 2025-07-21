<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\CapsuleService;
use Stevebauman\Location\Facades\Location;

class CapsuleController extends Controller
{
    use ApiResponse;
    protected $capsuleService;

    public function __construct(CapsuleService $capsuleService)
    {
        $this->capsuleService = $capsuleService;
    }

    public function store(Request $request)
    {
        $location = Location::Get($request->ip());
        $validatedData = $request->validate([
            "message" => "required|string",
            "location" => "nullable|string",
            "reveal_date" => 'required|date',
            'privacy' => 'required|in:private,public,unlisted',
            'surprise_mode' => 'required|boolean',
        ]);

        $validatedData['location'] = $location->countryName;

        $capsule = $this->capsuleService->store($validatedData, auth('api')->user());
        return  $this->successResponse($capsule);
    }

    public function show($id)
    {
        $capsule = $this->capsuleService->show($id);

        if (!$capsule) {
            return $this->notFoundResponse('Capsule not found');
        }

        return $this->successResponse($capsule->load(['capsuleMedia', 'tags']));
    }

    public function destroy($id)
    {
        $deleted = $this->capsuleService->deleteCapsule($id, auth('api')->user());

        if (!$deleted) {
            return $this->notFoundResponse('Capsule not found');
        }

        return $this->successResponse(null, 'Capsule deleted successfully');
    }

    public function myCapsules()
    {
        $capsules = $this->capsuleService->myCapsules(auth('api')->user());
        return $this->successResponse($capsules);
    }

    public function revealedCapsules()
    {
        $capsules = $this->capsuleService->revealedCapsules();
        return $this->successResponse($capsules);
    }

    public function publicCapsules()
    {
        $capsules = $this->capsuleService->publicCapsules();
        return $this->successResponse($capsules);
    }

    public function upcomingCapsules()
    {
        $capsules = $this->capsuleService->userUpcomingCapsules(auth('api')->user());
        return $this->successResponse($capsules);
    }
}
