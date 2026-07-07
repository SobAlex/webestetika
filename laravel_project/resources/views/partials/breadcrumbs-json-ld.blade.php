@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    @php
        $jsonLdItems = [];

        // Добавляем главную страницу
        $jsonLdItems[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Главная',
            'item' => url(route('home')),
        ];

        // Добавляем остальные крошки
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $position = $index + 2;
            $item = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $breadcrumb['title'],
            ];

            // Если это не последний элемент, добавляем URL
            if (isset($breadcrumb['url']) && $breadcrumb['url']) {
                $item['item'] = url($breadcrumb['url']);
            }

            $jsonLdItems[] = $item;
        }

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $jsonLdItems,
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif
