@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    @php
        $jsonLdItems = [];

        // Добавляем главную страницу
        $homeUrl = route('home');
        $jsonLdItems[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Главная',
            'item' => $homeUrl,
            'url' => $homeUrl,
        ];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $item = [
                '@type' => 'ListItem',
                'position' => $index + 2,
                'name' => $breadcrumb['title'],
            ];

            if (! empty($breadcrumb['url'])) {
                $crumbUrl = url($breadcrumb['url']);
                $item['item'] = $crumbUrl;
                $item['url'] = $crumbUrl;
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
        {!! \App\Support\StructuredData::encode($jsonLd) !!}
    </script>
@endif
