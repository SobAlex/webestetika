<?php

namespace App\Support;

class Accent
{
    public static function palette(?string $hex): string
    {
        return match (strtolower(trim((string) $hex))) {
            '#10b981' => 'emerald',
            '#3b82f6' => 'blue',
            '#f59e0b' => 'amber',
            '#8b5cf6' => 'violet',
            default => 'cyan',
        };
    }

    public static function text(?string $hex): string
    {
        return 'text-'.self::palette($hex).'-500';
    }

    public static function panel(?string $hex): string
    {
        $name = self::palette($hex);

        return "border border-{$name}-500/30 bg-{$name}-500/10";
    }

    public static function icon(?string $hex): string
    {
        return 'bg-'.self::palette($hex).'-500/20';
    }

    public static function frame(?string $hex): string
    {
        $name = self::palette($hex);

        return "border-2 border-{$name}-500/40 bg-{$name}-500/20";
    }
}
