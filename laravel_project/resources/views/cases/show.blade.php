@extends('layouts.app')

@section('title', $caseData['meta_title'] ?: $caseData['title'] . ' - SEO продвижение сайтов')
@section('meta_description', $caseData['meta_description'])

@section('content')
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => array_filter([
                ['title' => 'Кейсы', 'url' => route('cases.index')],
                $categoryInfo
                    ? [
                        'title' => $categoryInfo['name'],
                        'url' =>
                            $categoryInfo['route'] === 'cases.category'
                                ? route($categoryInfo['route'], ...$categoryInfo['route_params'])
                                : route($categoryInfo['route']),
                    ]
                    : null,
                ['title' => $caseData['title'], 'url' => null, 'truncate' => true],
            ]),
        ])
    </div>

    <section class="section-bg">
        <div>
            <div class="flex items-center mb-8">
                <div>
                    <h1>{{ $caseData['title'] }}</h1>
                    <div class="flex items-center text-gray-600">
                        <i class="material-icons mr-2">{{ $serviceData['service_icon'] }}</i>
                        <a href="{{ $serviceData['service_slug'] }}"
                            class="hover:text-cyan-600 transition-colors">{{ $serviceData['service_name'] }}</a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="relative h-64 lg:h-80 overflow-hidden">
                    <img src="{{ $caseData['image_url'] }}" alt="{{ $caseData['title'] }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute right-4 bottom-4 left-4 text-white">
                        @if ($caseData['has_valid_category'])
                            <a href="{{ route('cases.category', $caseData['industry']) }}"
                                class="bg-cyan-500 hover:bg-cyan-600 px-3 py-1 transition-colors inline-block text-white">{{ $caseData['industry_name'] }}</a>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h2>Описание проекта</h2>
                        <div class="service-content text-left">
                            {!! $caseData['description'] !!}
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border border-cyan-500/30 bg-cyan-500/10 p-4">
                            <div class="mb-1 text-lg font-medium text-gray-600">Клиент</div>
                            <div class="text-lg font-medium text-cyan-500">{{ $caseData['client'] }}</div>
                        </div>
                        <div class="border border-cyan-500/30 bg-cyan-500/10 p-4">
                            <div class="mb-1 text-lg font-medium text-gray-600">Период</div>
                            <div class="text-lg font-medium text-cyan-500">{{ $caseData['period'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-bg">
        <div>
            <h2 class="section-title">Ключевые результаты</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($caseData['results'] as $result)
                    <div class="flex items-start space-x-3 p-4 bg-white shadow-sm">
                        <i class="material-icons text-cyan-500 mt-1">check_circle</i>
                        <span class="text-gray-700">{{ $result }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if (isset($caseData['before_after']) && !empty($caseData['before_after']))
        <section class="section-bg">
            <div>
                <h2 class="section-title">До и после</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($caseData['before_after'] as $metric => $values)
                        <div class="bg-white shadow-sm overflow-hidden">
                            <div class="p-4 text-center">
                                <h3 class="mb-4">{{ $values['label'] }}</h3>
                                <div class="space-y-3">
                                    <div class="text-center p-3 bg-red-50">
                                        <div class="text-xs text-gray-500 mb-1">До</div>
                                        <div class="text-red-600">{{ $values['before'] }}</div>
                                    </div>
                                    <div class="flex justify-center"><i
                                            class="material-icons text-cyan-500">arrow_downward</i></div>
                                    <div class="text-center p-3 bg-green-50">
                                        <div class="text-xs text-gray-500 mb-1">После</div>
                                        <div class="text-green-600">{{ $values['after'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (isset($caseData['content']) && !empty($caseData['content']))
        <section class="section-bg">
            <div>
                <h2 class="section-title">Дополнительная информация</h2>
                <div class="service-content max-w-none">
                    @php
                        $content = $caseData['content'];
                        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $content = preg_replace_callback(
                            '/<pre><code>(.*?)<\/code><\/pre>/s',
                            function ($matches) {
                                $inner = htmlspecialchars($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                return '<pre><code>' . $inner . '</code></pre>';
                            },
                            $content,
                        );
                    @endphp
                    {!! $content !!}
                </div>
            </div>
        </section>
    @endif

    <section class="section-bg">
        <div>
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center">
                <h2 class="text-white">Хотите такой же результат?</h2>
                <p class="mb-6 text-white section-subtitle">Свяжитесь с нами для обсуждения вашего проекта</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="btn" onclick="openServiceOrderModal('{{ $serviceData['service_name'] }}')">Заказать
                        услугу</button>
                </div>
            </div>
        </div>
    </section>
@endsection
