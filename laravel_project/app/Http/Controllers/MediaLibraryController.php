<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Показать медиа библиотеку
     */
    public function index(Request $request)
    {
        $query = Media::query();

        // Фильтр по типу файла
        if ($request->has('type')) {
            $type = $request->get('type');
            if ($type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($type === 'pdf') {
                $query->where('mime_type', 'application/pdf');
            } elseif ($type === 'text') {
                $query->where('mime_type', 'like', 'text/%');
            }
        }

        // Поиск
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(20);
        $stats = $this->mediaService->getStats();

        return view('admin.media-library', compact('media', 'stats'));
    }
}
