<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    public function __construct(private readonly MediaService $mediaService) {}

    /**
     * Показать медиа библиотеку
     */
    public function index(Request $request)
    {
        $query = Media::query();

        $type = (string) $request->input('type', '');

        if ($type === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($type === 'pdf') {
            $query->where('mime_type', 'application/pdf');
        }

        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $like = '%'.addcslashes($search, '%_\\').'%';

            $query->where(function ($q) use ($like) {
                $q->where('original_name', 'like', $like)
                    ->orWhere('alt_text', 'like', $like)
                    ->orWhere('description', 'like', $like);
            });
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $stats = $this->mediaService->getStats();

        return view('admin.media-library', compact('media', 'stats'));
    }
}
