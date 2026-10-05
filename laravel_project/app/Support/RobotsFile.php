<?php

namespace App\Support;

use RuntimeException;

class RobotsFile
{
    public const MAX_BYTES = 100_000;

    public static function path(): string
    {
        return public_path('robots.txt');
    }

    public static function read(): string
    {
        $path = self::path();

        if (! is_file($path)) {
            return '';
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException('Не удалось прочитать robots.txt.');
        }

        return self::normalize($content);
    }

    public static function write(string $content): void
    {
        $content = self::normalize($content);

        if (str_contains($content, "\0")) {
            throw new RuntimeException('В файле не должно быть служебных символов.');
        }

        if (strlen($content) > self::MAX_BYTES) {
            throw new RuntimeException('Файл больше 100 КБ.');
        }

        $written = file_put_contents(self::path(), $content, LOCK_EX);

        if ($written === false) {
            throw new RuntimeException('Не удалось сохранить robots.txt.');
        }
    }

    public static function normalize(string $content): string
    {
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        if ($content !== '' && ! str_ends_with($content, "\n")) {
            $content .= "\n";
        }

        return $content;
    }
}
