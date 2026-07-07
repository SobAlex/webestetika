<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    /**
     * Показать демонстрацию использования медиа библиотеки
     */
    public function index()
    {
        // Получаем несколько изображений для демонстрации
        $images = Media::where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('demo.media-usage', compact('images'));
    }
}
