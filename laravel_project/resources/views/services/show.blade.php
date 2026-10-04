@extends('layouts.app')

@section('title', $title)
@section('meta_description', $service->meta_description)
@section('meta_keywords', $service->meta_keywords)

@section('content')
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [
                ['title' => 'Услуги', 'url' => route('services.index')],
                ['title' => $service->title, 'url' => null],
            ],
        ])
    </div>

    <section class="section-bg">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="flex-1">
                <div class="flex items-center mb-6">
                    <div>
                        <h1>{{ $service->title }}</h1>
                        @if ($service->is_featured)
                            <span class="mt-2 inline-block {{ \App\Support\Accent::text($service->color) }}">Рекомендуемая услуга</span>
                        @endif
                    </div>
                </div>
                <p class="section-subtitle text-left">{{ $service->description }}</p>
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <div class="px-7 py-4 text-lg font-medium {{ \App\Support\Accent::panel($service->color) }}">
                        <div>Стоимость</div>
                        <div class="{{ \App\Support\Accent::text($service->color) }}">{{ $service->formatted_price }}</div>
                    </div>
                    @if ($service->price_type)
                        <div class="px-7 py-4 text-lg font-medium {{ \App\Support\Accent::panel($service->color) }}">
                            <div>Тип оплаты</div>
                            <div class="{{ \App\Support\Accent::text($service->color) }}">
                                @switch($service->price_type)
                                    @case('hour')
                                        Почасовая
                                    @break

                                    @case('month')
                                        Ежемесячная
                                    @break

                                    @case('project')
                                        За проект
                                    @break

                                    @default
                                        {{ $service->price_type }}
                                @endswitch
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <button class="btn" onclick="openServiceOrderModal('{{ $service->title }}')">Заказать
                        услугу</button>
                </div>
            </div>
            @if ($service->image)
                <div class="flex-1 lg:max-w-md">
                    <div class="aspect-square bg-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($service->content)
        <section class="section-bg">
            <div class="service-content max-w-none">
                @php
                    $content = $service->content;
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
        </section>
    @endif

    @if ($service->features && is_array($service->features) && count($service->features) > 0)
        <section class="section-bg">
            <h2 class="section-title">Что входит в услугу</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($service->features as $feature)
                    @if (is_string($feature))
                        <div class="flex items-start p-4 bg-white shadow-sm">
                            <div class="flex-shrink-0 mr-4">
                                <div class="flex h-8 w-8 items-center justify-center {{ \App\Support\Accent::icon($service->color) }}">
                                    <i class="material-icons text-sm {{ \App\Support\Accent::text($service->color) }}">check</i>
                                </div>
                            </div>
                            <div>{{ $feature }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    @if ($relatedServices->count() > 0)
        <section class="section-bg">
            <h2 class="section-title">Другие услуги</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($relatedServices as $relatedService)
                    <article class="card">
                        @if ($relatedService->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $relatedService->image) }}"
                                    alt="{{ $relatedService->title }}" class="w-full h-full object-cover object-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        @else
                            <div class="h-48 bg-gray-100 flex items-center justify-center">
                                @if ($relatedService->icon)
                                    <div class="flex h-16 w-16 items-center justify-center {{ \App\Support\Accent::frame($relatedService->color) }}">
                                        <i class="material-icons text-4xl {{ \App\Support\Accent::text($relatedService->color) }}">{{ $relatedService->icon }}</i>
                                    </div>
                                @else
                                    <i class="material-icons text-6xl text-gray-300">business</i>
                                @endif
                            </div>
                        @endif
                        <div class="p-6">
                            <a href="{{ route('services.show', $relatedService->slug) }}" class="group">
                                <h3 class="mb-2 group-hover:text-cyan-500 transition-colors">{{ $relatedService->title }}
                                </h3>
                            </a>
                            <p class="mb-4">{{ Str::limit($relatedService->description, 100) }}</p>
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span class="text-lg font-medium {{ \App\Support\Accent::text($relatedService->color) }}">{{ $relatedService->formatted_price }}</span>
                                <button class="btn"
                                    onclick="openServiceOrderModal('{{ $relatedService->title }}')">Заказать</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($relatedArticles->count() > 0)
        <section class="section-bg">
            <h2 class="section-title">Полезные статьи</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($relatedArticles as $article)
                    {{-- Добавлены классы h-full, flex, flex-col для вертикальной растяжки --}}
                    <article class="card h-full flex flex-col">
                        @if ($article->image)
                            <div class="relative h-48 overflow-hidden flex-shrink-0">
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover object-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                @if ($article->category)
                                    <div class="absolute top-3 left-3">
                                        <a href="{{ route('blog.category', $article->category->slug ?? 'uncategorized') }}"
                                            class="inline-block px-2 py-1 bg-white/90 hover:bg-white transition-colors">
                                            {{ $article->category->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-48 bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <div class="w-16 h-16 bg-cyan-500 flex items-center justify-center">
                                    <i class="material-icons text-white text-4xl">article</i>
                                </div>
                            </div>
                        @endif

                        {{-- Внутренний контейнер – растягиваемая колонка --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="mb-2 line-clamp-2">
                                <a href="{{ $article->url }}" class="hover:text-cyan-500">{{ $article->title }}</a>
                            </h3>
                            <p class="mb-4 leading-relaxed line-clamp-3 flex-1">{{ Str::limit($article->excerpt, 120) }}
                            </p>
                            {{-- Нижний блок прижат к низу --}}
                            <div class="flex items-center justify-between mt-auto">
                                <span>{{ $article->published_at->format('d.m.Y') }}</span>
                                <a href="{{ $article->url }}" class="hover:underline">Читать →</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($relatedCases->count() > 0)
        <section class="section-bg">
            <h2 class="section-title">Наши кейсы</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($relatedCases as $case)
                    <article class="shadow-sm">
                        {{-- Изображение --}}
                        @if ($case->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $case->image) }}" alt="{{ $case->title }}"
                                    class="w-full h-full object-cover object-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                @if ($case->industryCategory)
                                    <div class="absolute top-3 left-3">
                                        <a href="{{ route('cases.category', $case->industryCategory->slug ?? 'uncategorized') }}"
                                            class="inline-block px-2 py-1 bg-white/90 hover:bg-white transition-colors">
                                            {{ $case->industryCategory->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Контент --}}
                        <div class="p-6">
                            {{-- Заголовок --}}
                            <a href="{{ $case->url }}" class="group">
                                <h3 class="mb-2 group-hover:text-cyan-500 transition-colors">{{ $case->title }}</h3>
                            </a>

                            {{-- Клиент и период вместе --}}
                            <div class="mb-4">
                                @if ($case->client)
                                    <p>Клиент: {{ $case->client }}</p>
                                @endif
                                @if ($case->period)
                                    <p>Период: {{ $case->period }}</p>
                                @endif
                            </div>

                            {{-- Выдержка --}}
                            <p class="mb-4">{{ $case->excerpt }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($servicesFaqs->count() > 0)
        <section class="section-bg">
            <h2 class="section-title">Часто задаваемые вопросы</h2>
            <div class="max-w-3xl mx-auto">
                @foreach ($servicesFaqs as $faq)
                    <div class="mb-4">
                        <details class="group">
                            <summary
                                class="faq-summary flex w-full cursor-pointer items-center justify-between bg-white p-4 shadow-sm hover:bg-gray-50">
                                <span class="min-w-0">{{ $faq->question }}</span>
                                <i
                                    class="material-icons shrink-0 text-gray-400 transition-transform group-open:rotate-180">expand_more</i>
                            </summary>
                            <div class="p-4 bg-gray-50 shadow-sm">
                                <p>{{ $faq->answer }}</p>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section-bg mt-24">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center">
            <h2 class="text-white">Готовы заказать {{ $service->title }}?</h2>
            <p class="mb-6 section-subtitle text-white">Свяжитесь с нами для обсуждения вашего проекта</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="btn" onclick="openServiceOrderModal('{{ $service->title }}')">Заказать
                    {{ $service->title }}</button>
            </div>
        </div>
    </section>
@endsection
