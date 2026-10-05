<?php

namespace App\Support;

use App\Models\Blog;

class StructuredData
{
    public const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP;

    public static function encode(array $data): string
    {
        return (string) json_encode($data, self::JSON_FLAGS);
    }

    /**
     * @param  array<string, mixed>  $contact
     * @return array<string, mixed>
     */
    public static function organization(array $contact): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => 'SobAlex',
            'url' => route('home'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => url('/apple-touch-icon.png'),
                'width' => 180,
                'height' => 180,
            ],
        ];

        $phone = self::realValue($contact['phone'] ?? null, 'Телефон не указан');
        $email = self::realValue($contact['email'] ?? null, 'Email не указан');
        $address = self::realValue($contact['address'] ?? null, 'Адрес не указан');

        if ($phone !== null) {
            $data['telephone'] = $phone;
        }

        if ($email !== null) {
            $data['email'] = $email;
        }

        if ($address !== null) {
            $data['address'] = self::postalAddress($address);
        }

        $hours = self::openingHours($contact['working_hours'] ?? null);

        if ($hours !== []) {
            $data['openingHours'] = $hours;
        }

        $sameAs = [];

        foreach (($contact['social'] ?? []) as $url) {
            if (is_string($url) && str_starts_with($url, 'http')) {
                $sameAs[] = $url;
            }
        }

        if ($sameAs !== []) {
            $data['sameAs'] = $sameAs;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public static function blogPosting(Blog $article): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $article->title,
            'mainEntityOfPage' => $article->url,
            'author' => self::author($article),
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SobAlex',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('/apple-touch-icon.png'),
                    'width' => 180,
                    'height' => 180,
                ],
            ],
        ];

        $description = trim((string) $article->excerpt);

        if ($description !== '') {
            $data['description'] = $description;
        }

        $image = $article->image_url;

        if (is_string($image) && $image !== '') {
            $data['image'] = str_starts_with($image, 'http') ? $image : url($image);
        }

        if ($article->published_at) {
            $data['datePublished'] = $article->published_at->toAtomString();
        }

        if ($article->updated_at) {
            $data['dateModified'] = $article->updated_at->toAtomString();
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    private static function author(Blog $article): array
    {
        $name = trim((string) $article->user?->name);

        if ($name !== '') {
            return [
                '@type' => 'Person',
                'name' => $name,
            ];
        }

        return [
            '@type' => 'Organization',
            'name' => 'SobAlex',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function postalAddress(string $address): array
    {
        $postal = [
            '@type' => 'PostalAddress',
            'addressCountry' => 'RU',
        ];

        if (preg_match('/^г\.\s*([^,]+),\s*(.+)$/u', $address, $matches) === 1) {
            $postal['addressLocality'] = trim($matches[1]);
            $postal['streetAddress'] = trim($matches[2]);

            return $postal;
        }

        $postal['streetAddress'] = $address;

        return $postal;
    }

    /**
     * @return list<string>
     */
    private static function openingHours(?string $text): array
    {
        $text = self::realValue($text, 'Часы работы не указаны');

        if ($text === null) {
            return [];
        }

        $days = [
            'пн-вс' => 'Mo-Su',
            'пн-сб' => 'Mo-Sa',
            'пн-пт' => 'Mo-Fr',
            'сб-вс' => 'Sa-Su',
            'пн' => 'Mo',
            'вт' => 'Tu',
            'ср' => 'We',
            'чт' => 'Th',
            'пт' => 'Fr',
            'сб' => 'Sa',
            'вс' => 'Su',
        ];

        $hours = [];

        foreach (preg_split('/\s*,\s*/u', $text) ?: [] as $part) {
            if (preg_match('/^(.+?)\s*:\s*(\d{1,2}:\d{2})\s*[-–—]\s*(\d{1,2}:\d{2})$/u', trim($part), $matches) !== 1) {
                return [];
            }

            $day = $days[mb_strtolower(str_replace([' ', '.'], '', $matches[1]))] ?? null;

            if ($day === null) {
                return [];
            }

            $hours[] = $day.' '.self::clock($matches[2]).'-'.self::clock($matches[3]);
        }

        return $hours;
    }

    private static function clock(string $time): string
    {
        [$hour, $minute] = explode(':', $time);

        return sprintf('%02d:%s', (int) $hour, $minute);
    }

    private static function realValue(?string $value, string $placeholder): ?string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === $placeholder) {
            return null;
        }

        return $value;
    }
}
