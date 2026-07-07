@extends('layouts.app')

@section('title', $title . ' - SEO продвижение сайтов')

@section('content')
    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => array_filter([
                isset($categoryInfo)
                    ? ['title' => 'Кейсы', 'url' => route('cases.index')]
                    : ['title' => 'Кейсы', 'url' => null],
                isset($categoryInfo) ? ['title' => $categoryInfo['name'], 'url' => null] : null,
            ]),
        ])
    </div>

    {{-- Hero section --}}
    <section class="section-bg">
        <div class="text-center">
            <h1 class="page-title">{{ $title }}</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Реальные результаты нашей работы. Каждый кейс — это история успеха наших клиентов
                и доказательство эффективности наших методов продвижения.
            </p>
        </div>
    </section>

    {{-- Filter navigation --}}
    <section class="section-bg">
        <div class="text-center mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Фильтр по отраслям</h3>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('cases.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ !isset($selectedTag) ? 'bg-cyan-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Все кейсы
                </a>
                @foreach ($activeCategories as $category)
                    <a href="{{ route($category['route'], ...$category['route_params']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ isset($selectedTag) && $selectedTag === $category['slug'] ? 'bg-cyan-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $category['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cases by service --}}
    @if (empty($casesData))
        <section class="section-bg">
            <div class="text-center py-16">
                <h2 class="text-2xl font-bold text-gray-600 mb-4">Кейсы временно недоступны</h2>
                <p class="text-gray-500 mb-8">В данный момент у нас нет доступных кейсов для отображения.</p>
            </div>
        </section>
    @else
        @foreach ($casesData as $serviceKey => $serviceData)
            <section id="{{ $serviceKey }}" class="section-bg">
                <div class="mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($serviceData['cases'] as $case)
                            <article
                                class="bg-white/80 backdrop-blur-sm overflow-hidden rounded-lg shadow-sm flex flex-col h-full">
                                {{-- Case image --}}
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $case['image_url'] }}" alt="{{ $case['title'] }}"
                                        class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-4 left-4 text-white">
                                        @if ($case['has_valid_category'])
                                            <a href="{{ route('cases.category', $case['industry']) }}"
                                                class="bg-cyan-500 hover:bg-cyan-600 px-3 py-1 rounded-md text-sm font-medium transition-colors inline-block">
                                                {{ $case['industry_name'] }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Case content --}}
                                <div class="p-6 flex-1 flex flex-col">
                                    <a href="{{ route('cases.show', $case['id']) }}" class="group">
                                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-cyan-600 transition-colors">{{ $case['title'] }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-500 mb-3">{{ $case['client'] }} •
                                        {{ $case['period'] }}
                                    </p>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                        {{ Str::limit($case['description_clean'], 120) }}</p>

                                    {{-- Key metrics --}}
                                    <div class="space-y-2">
                                        @foreach (array_slice($case['results'], 0, 2) as $result)
                                            <div class="flex items-center text-sm">
                                                <i class="material-icons text-cyan-500 mr-2 text-base">trending_up</i>
                                                <span class="text-gray-700 font-medium">{{ $result }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    {{-- CTA section --}}
    <section class="section-bg">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center rounded-md">
            <h2 class="text-3xl font-bold mb-4">Хотите такой же результат?</h2>
            <p class="text-xl mb-6 opacity-90">Свяжитесь с нами для обсуждения вашего проекта</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                    onclick="openServiceOrderModal('Заказать продвижение')">
                    Заказать продвижение
                </button>
                <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                    onclick="window.dispatchEvent(new CustomEvent('open-callback'))">
                    Заказать звонок
                </button>
            </div>
        </div>
    </section>

@endsection
