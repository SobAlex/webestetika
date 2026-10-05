<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MediaController extends Controller
{
    public function __construct(private readonly MediaService $mediaService) {}

    /**
     * Загрузить файл.
     */
    public function upload(MediaUploadRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->mediaService->uploadFile(
                $request->file('file'),
                $validated['alt_text'] ?? null,
                $validated['description'] ?? null,
            );

            return response()->json([
                'success' => true,
                'message' => 'Файл загружен',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Media upload error', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Не удалось загрузить файл',
            ], 500);
        }
    }

    /**
     * Удалить файл.
     */
    public function destroy(Media $media): RedirectResponse
    {
        try {
            $this->mediaService->deleteFile($media);

            return back()->with('status', 'Файл удалён');
        } catch (\Throwable $e) {
            Log::error('Media delete error', [
                'media_id' => $media->id,
                'exception' => $e,
            ]);

            return back()->with('error', 'Не удалось удалить файл');
        }
    }
}
