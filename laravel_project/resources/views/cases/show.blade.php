@extends('layouts.app')

@section('title', $caseData['meta_title'] ?: $caseData['title'] . ' - SEO продвижение сайтов')

@section('meta_description', $caseData['meta_description'])

@section('content')
    <!-- Breadcrumbs -->
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

    {{-- Hero section --}}
    <section class="section-bg">
        <div>
            <div class="flex items-center mb-8">
                <a href="{{ route('cases.index') }}" class="text-cyan-600 hover:text-cyan-700 mr-4">
                    <i class="material-icons">arrow_back</i>
                </a>
                <div>
                    <h1 class="page-title">{{ $caseData['title'] }}</h1>
                    <div class="flex items-center text-gray-600">
                        <i class="material-icons mr-2">{{ $serviceData['service_icon'] }}</i>
                        <a href="{{ $serviceData['service_slug'] }}"
                            class="hover:text-cyan-600 transition-colors">{{ $serviceData['service_name'] }}</a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Case image --}}
                <div class="relative h-64 lg:h-80 overflow-hidden rounded-md">
                    <img src="{{ $caseData['image_url'] }}" alt="{{ $caseData['title'] }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        @if ($caseData['has_valid_category'])
                            <a href="{{ route('cases.category', $caseData['industry']) }}"
                                class="bg-cyan-500 hover:bg-cyan-600 px-3 py-1 rounded-md text-sm font-medium transition-colors inline-block">
                                {{ $caseData['industry_name'] }}
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Case info --}}
                <div class="space-y-6">
                    <div>
                        <h2 class="block-title">Описание проекта</h2>
                        <div class="text-gray-600 leading-relaxed prose prose-gray max-w-none">
                            {!! $caseData['description'] !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-md" style="background-color: #06b6d410; border: 1px solid #06b6d430;">
                            <div class="text-sm text-gray-600 mb-1">Клиент</div>
                            <div class="text-2xl" style="color: #06b6d4;">{{ $caseData['client'] }}</div>
                        </div>
                        <div class="p-4 rounded-md" style="background-color: #06b6d410; border: 1px solid #06b6d430;">
                            <div class="text-sm text-gray-600 mb-1">Период</div>
                            <div class="text-2xl" style="color: #06b6d4;">{{ $caseData['period'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Results section --}}
    <section class="section-bg">
        <div>
            <h2 class="block-title text-center">Ключевые результаты</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($caseData['results'] as $result)
                    <div
                        class="flex items-start space-x-3 p-4 bg-white rounded-md shadow-sm">
                        <i class="material-icons text-cyan-500 mt-1">check_circle</i>
                        <span class="text-gray-700">{{ $result }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Before/After section --}}
    @if (isset($caseData['before_after']) && !empty($caseData['before_after']))
        <section class="section-bg">
            <div>
                <h2 class="block-title text-center">До и после</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($caseData['before_after'] as $metric => $values)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-4 text-center">
                                <h3 class="font-semibold text-gray-800 mb-4">
                                    {{ $values['label'] }}
                                </h3>
                                <div class="space-y-3">
                                    <div class="text-center p-3 bg-red-50">
                                        <div class="text-xs text-gray-500 mb-1">До</div>
                                        <div class="text-lg font-bold text-red-600">{{ $values['before'] }}</div>
                                    </div>
                                    <div class="flex justify-center">
                                        <i class="material-icons text-cyan-500">arrow_downward</i>
                                    </div>
                                    <div class="text-center p-3 bg-green-50">
                                        <div class="text-xs text-gray-500 mb-1">После</div>
                                        <div class="text-lg font-bold text-green-600">{{ $values['after'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Additional content section --}}
    @if (isset($caseData['content']) && !empty($caseData['content']))
        <section class="section-bg">
            <div>
                <h2 class="block-title text-center">Дополнительная информация</h2>
                <div class="prose prose-lg max-w-none mx-auto">
                    {!! $caseData['content'] !!}
                </div>
            </div>
        </section>
    @endif

    {{-- CTA section --}}
    <section class="section-bg">
        <div>
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center rounded-md">
                <h2 class="text-3xl font-bold mb-4">Хотите такой же результат?</h2>
                <p class="text-xl mb-6 opacity-90">Свяжитесь с нами для обсуждения вашего проекта</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                        onclick="openServiceOrderModal('{{ $serviceData['service_name'] }}')">
                        Заказать услугу
                    </button>
                    <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                        onclick="window.dispatchEvent(new CustomEvent('open-callback'))">
                        Заказать звонок
                    </button>
                </div>
            </div>
        </div>
    </section>

@endsection
