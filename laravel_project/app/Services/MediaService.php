<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaService
{
    /**
     * Загрузить файл и создать запись в медиа библиотеке
     */
    public function uploadFile(UploadedFile $file, ?string $altText = null, ?string $description = null): Media
    {
        // Генерируем уникальное имя файла
        $originalName = mb_substr($file->getClientOriginalName(), 0, 255);
        $extension = $this->extensionForMime((string) $file->getMimeType());

        if ($extension === null) {
            throw ValidationException::withMessages([
                'file' => 'Допустимы только изображения JPEG, PNG, WebP, GIF и PDF.',
            ]);
        }

        $filename = Str::uuid() . '.' . $extension;
        $directory = 'media/' . date('Y/m');
        $path = $file->storeAs($directory, $filename, 'public');

        if (! is_string($path) || $path === '') {
            throw new \RuntimeException('Не удалось сохранить файл');
        }

        $url = Storage::disk('public')->url($path);

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

    private function extensionForMime(string $mime): ?string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            default => null,
        };
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
