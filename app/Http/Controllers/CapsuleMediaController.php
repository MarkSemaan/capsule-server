<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\CapsuleMediaService;
use App\Models\CapsuleMedia;
use Illuminate\Support\Facades\Log;

class CapsuleMediaController extends Controller
{
    use ApiResponse;
    protected $mediaService;

    public function __construct(CapsuleMediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function store(Request $request)
    {
        Log::info('Media upload request received', [
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'headers' => $request->headers->all()
        ]);

        try {
            $capsuleId = $request->input('capsule_id');
            $file = $request->file("file");

            if (!$file) {
                Log::error('No file received in request');
                return $this->errorResponse('No file received', 400);
            }

            if (!$file->isValid()) {
                Log::error('Invalid file received', ['file_error' => $file->getErrorMessage()]);
                return $this->errorResponse('Invalid file: ' . $file->getErrorMessage(), 400);
            }

            $type = $this->determineFileType($file);
            $this->validateFile($request, $type);

            $user = auth('api')->user();
            $capsule = $user->capsules()->find($capsuleId);
            if (!$capsule) {
                Log::error('Capsule not found or access denied', [
                    'capsule_id' => $capsuleId,
                    'user_id' => $user->id
                ]);
                return $this->notFoundResponse('Capsule not found or access denied');
            }

            $base64 = base64_encode(file_get_contents($file->getRealPath()));

            $media = $this->mediaService->store([
                "capsule_id" => $capsuleId,
                "type" => $type,
                'content' => $base64,
            ]);

            Log::info('Media uploaded successfully', ['media_id' => $media->id]);
            return $this->successResponse($media);
        } catch (\Exception $e) {
            Log::error('Media upload failed with exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Media upload failed: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $media = $this->mediaService->show($id);
        if (!$media) {
            return $this->notFoundResponse('Media not found!');
        }
        return $this->successResponse($media);
    }

    public function getCapsuleMedia($capsuleId)
    {
        $media = $this->mediaService->getCapsuleMedia($capsuleId);
        return $this->successResponse($media);
    }

    public function destroy($id)
    {

        $user = auth('api')->user();
        $media = CapsuleMedia::whereHas('capsule', function ($query) use ($user) {
            $query->where('user_id', $user->getKey());
        })->find($id);

        if (!$media) {
            return $this->notFoundResponse('Media not found or access denied!');
        }
        $deleted = $this->mediaService->destroy($id);
        if (!$deleted) {
            return $this->notFoundResponse('Media not found!');
        }
        return $this->successResponse(null, 'Media deleted.');
    }


    // helper functions to determine and validate files
    private function determineFileType($file)
    {
        $mimeType = $file->getMimeType();
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }

        throw new \Exception('Unsupported!');
    }

    private function validateFile(Request $request, $type)
    {
        $rules = [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'audio' => 'required|mimes:mp3,wav,m4a,aac|max:10240',
        ];

        $request->validate([
            'file' => $rules[$type],
            'capsule_id' => 'required|exists:capsules,id',
        ]);
    }
}
