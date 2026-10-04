@extends('layouts.app')

@section('title', $title . ' - SEO продвижение сайтов')

@section('content')
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

    <section class="section-bg">
        <div class="text-center">
            <h1>{{ $title }}</h1>
            <p class="section-subtitle">
                Реальные результаты нашей работы. Каждый кейс — это история успеха наших клиентов
                и доказательство эффективности наших методов продвижения.
            </p>
        </div>
    </section>

    <section class="section-bg">
        <div class="text-center mb-8">
            <h2 class="mb-4">Фильтр по отраслям</h2>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('cases.index') }}"
                    class="px-4 py-2 transition-colors {{ !isset($selectedTag) ? 'bg-cyan-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Все
                    кейсы</a>
                @foreach ($activeCategories as $category)
                    <a href="{{ route($category['route'], ...$category['route_params']) }}"
                        class="px-4 py-2 transition-colors {{ isset($selectedTag) && $selectedTag === $category['slug'] ? 'bg-cyan-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $category['name'] }}</a>
                @endforeach
            </div>
        </div>
    </section>

    @if (empty($casesData))
        <section class="section-bg">
            <div class="text-center py-16">
                <h2 class="mb-4">Кейсы временно недоступны</h2>
                <p class="mb-8">В данный момент у нас нет доступных кейсов для отображения.</p>
            </div>
        </section>
    @else
        @foreach ($casesData as $serviceKey => $serviceData)
            <section id="{{ $serviceKey }}" class="section-bg">
                <div class="mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($serviceData['cases'] as $case)
                            <article class="card-glass flex flex-col h-full">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $case['image_url'] }}" alt="{{ $case['title'] }}"
                                        class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute right-4 bottom-4 left-4 text-white">
                                        @if ($case['has_valid_category'])
                                            <a href="{{ route('cases.category', $case['industry']) }}"
                                                class="bg-cyan-500 hover:bg-cyan-600 px-3 py-1 transition-colors inline-block text-white">{{ $case['industry_name'] }}</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <a href="{{ route('cases.show', $case['id']) }}" class="group">
                                        <h3 class="group-hover:text-cyan-600 transition-colors">{{ $case['title'] }}
                                        </h3>
                                    </a>
                                    <p class="mb-3">{{ $case['client'] }} • {{ $case['period'] }}</p>
                                    <p class="mb-4 line-clamp-2">{{ Str::limit($case['description_clean'], 120) }}</p>
                                    <div class="space-y-2">
                                        @foreach (array_slice($case['results'], 0, 2) as $result)
                                            <div class="flex items-start">
                                                <i class="material-icons mr-2 shrink-0 text-base text-cyan-500">trending_up</i>
                                                <span class="min-w-0">{{ $result }}</span>
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

    <section class="section-bg">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center">
            <h2 class="text-white">Хотите такой же результат?</h2>
            <p class="mb-6 section-subtitle text-white">Свяжитесь с нами для обсуждения вашего проекта</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="btn" onclick="openServiceOrderModal('Заказать продвижение')">Заказать продвижение</button>
            </div>
        </div>
    </section>
@endsection
