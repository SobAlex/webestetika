<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Загрузить файл и создать запись в медиа библиотеке
     */
    public function uploadFile(UploadedFile $file, ?string $altText = null, ?string $description = null): Media
    {
        // Генерируем уникальное имя файла
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;

        // Определяем директорию для загрузки
        $directory = 'media/' . date('Y/m');

        // Сохраняем файл
        $path = $file->storeAs($directory, $filename, 'public');

        // Создаем URL
        $url = Storage::url($path);

        // Создаем запись в базе данных
        $media = Media::create([
            'filename' => $filename,
            'original_name' => $originalName,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
            'url' => $url,
            'alt_text' => $altText,
            'description' => $description,
        ]);

        return $media;
    }

    /**
     * Удалить файл и запись из медиа библиотеки
     */
    public function deleteFile(Media $media): bool
    {
        // Удаляем файл с диска
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }

        // Удаляем запись из базы данных
        return $media->delete();
    }

    /**
     * Получить все изображения
     */
    public function getImages()
    {
        return Media::where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Получить все файлы определенного типа
     */
    public function getFilesByType(string $mimeType)
    {
        return Media::where('mime_type', $mimeType)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Поиск файлов по имени
     */
    public function searchFiles(string $query)
    {
        return Media::where('original_name', 'like', "%{$query}%")
            ->orWhere('alt_text', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Получить статистику медиа библиотеки
     */
    public function getStats(): array
    {
        $totalFiles = Media::count();
        $totalSize = Media::sum('size');
        $imageCount = Media::where('mime_type', 'like', 'image/%')->count();
        $pdfCount = Media::where('mime_type', 'application/pdf')->count();

        return [
            'total_files' => $totalFiles,
            'total_size' => $totalSize,
            'formatted_size' => $this->formatBytes($totalSize),
            'image_count' => $imageCount,
            'pdf_count' => $pdfCount,
        ];
    }

    /**
     * Форматировать размер в байтах
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
