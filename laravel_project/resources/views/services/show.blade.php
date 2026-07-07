@extends('layouts.app')

@section('title', $title)
@section('meta_description', $service->meta_description)
@section('meta_keywords', $service->meta_keywords)

@section('content')
    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [
                ['title' => 'Услуги', 'url' => route('services.index')],
                ['title' => $service->title, 'url' => null]
            ],
        ])
    </div>

    {{-- Hero section --}}
    <section class="section-bg">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <!-- Левая часть -->
            <div class="flex-1">
                <div class="flex items-center mb-6">
                    @if($service->icon)
                        <div class="w-16 h-16 rounded-md flex items-center justify-center mr-6"
                             style="background: linear-gradient(135deg, {{ $service->color }}20, {{ $service->color }}10); border: 2px solid {{ $service->color }}40;">
                            <i class="material-icons text-4xl" style="color: {{ $service->color }}">{{ $service->icon }}</i>
                        </div>
                    @endif
                    <div>
                        <h1 class="page-title">{{ $service->title }}</h1>
                        @if($service->is_featured)
                            <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-md">
                                Рекомендуемая услуга
                            </span>
                        @endif
                    </div>
                </div>

                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    {{ $service->description }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <div class="p-4 rounded-md" style="background-color: {{ $service->color }}10; border: 1px solid {{ $service->color }}30;">
                        <div class="text-sm text-gray-600">Стоимость</div>
                        <div class="text-2xl" style="color: {{ $service->color }}">{{ $service->formatted_price }}</div>
                    </div>
                    @if($service->price_type)
                        <div class="p-4 rounded-md" style="background-color: {{ $service->color }}10; border: 1px solid {{ $service->color }}30;">
                            <div class="text-sm text-gray-600">Тип оплаты</div>
                            <div class="text-2xl" style="color: {{ $service->color }}">
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
                    <button class="btn text-lg px-8 py-3" onclick="openServiceOrderModal('{{ $service->title }}')">
                        Заказать услугу
                    </button>
                    <a href="{{ route('contacts') }}" class="btn text-lg px-8 py-3 text-center">
                        Получить консультацию
                    </a>
                </div>
            </div>

            <!-- Правая часть (изображение) -->
            @if($service->image)
                <div class="flex-1 lg:max-w-md">
                    <div class="aspect-square bg-gray-100 rounded-md overflow-hidden">
                        <img src="{{ asset('storage/' . $service->image) }}"
                             alt="{{ $service->title }}"
                             class="w-full h-full object-cover">
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Content section --}}
    @if($service->content)
        <section class="section-bg">
            <div class="service-content max-w-none" style="color: #4b5563; line-height: 1.6;">
                {!! html_entity_decode($service->content, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}
            </div>
        </section>
    @endif

    {{-- Features section --}}
    @if($service->features && is_array($service->features) && count($service->features) > 0)
        <section class="section-bg">
            <h2 class="block-title text-center">Что входит в услугу</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($service->features as $feature)
                    @if(is_string($feature))
                        <div class="flex items-start p-4 bg-white rounded-md shadow-sm">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-8 h-8 rounded-md flex items-center justify-center" style="background-color: {{ $service->color }}20;">
                                    <i class="material-icons text-sm" style="color: {{ $service->color }}">check</i>
                                </div>
                            </div>
                            <div class="text-gray-700">{{ $feature }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif

    {{-- Related Services --}}
    @if($relatedServices->count() > 0)
        <section class="section-bg">
            <h2 class="block-title text-center">Другие услуги</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedServices as $relatedService)
                    <article class="rounded-md shadow-sm overflow-hidden">
                        @if($relatedService->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $relatedService->image) }}"
                                     alt="{{ $relatedService->title }}"
                                     class="w-full h-full object-cover object-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        @else
                            <div class="h-48 bg-gray-100 flex items-center justify-center">
                                @if($relatedService->icon)
                                    <div class="w-16 h-16 rounded-md flex items-center justify-center"
                                         style="background-color: {{ $relatedService->color }}20; border: 2px solid {{ $relatedService->color }}40;">
                                        <i class="material-icons text-4xl" style="color: {{ $relatedService->color }}">{{ $relatedService->icon }}</i>
                                    </div>
                                @else
                                    <i class="material-icons text-6xl text-gray-300">business</i>
                                @endif
                            </div>
                        @endif
                        <div class="p-6">
                            <a href="{{ route('services.show', $relatedService->slug) }}" class="group">
                                <h3 class="text-lg font-semibold group-hover:text-cyan-500 transition-colors mb-2">
                                    {{ $relatedService->title }}
                                </h3>
                            </a>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($relatedService->description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold" style="color: {{ $relatedService->color }}">
                                    {{ $relatedService->formatted_price }}
                                </span>
                                <button class="btn text-xs px-3 py-1" onclick="openServiceOrderModal('{{ $relatedService->title }}')">
                                    Заказать
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Related Articles --}}
    @if($relatedArticles->count() > 0)
        <section class="section-bg">
            <h2 class="block-title text-center">Полезные статьи</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedArticles as $article)
                    <article class="rounded-md shadow-sm overflow-hidden">
                        @if($article->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $article->image) }}"
                                     alt="{{ $article->title }}"
                                     class="w-full h-full object-cover object-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                @if($article->category)
                                    <div class="absolute top-3 left-3">
                                        <a href="{{ route('blog.category', $article->category->slug ?? 'uncategorized') }}"
                                           class="inline-block px-2 py-1 bg-white/90 text-gray-800 text-xs rounded-md font-medium hover:bg-white transition-colors">
                                            {{ $article->category->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-48 bg-gray-100 flex items-center justify-center">
                                <div class="w-16 h-16 bg-cyan-500 rounded-lg flex items-center justify-center">
                                    <i class="material-icons text-white text-4xl">article</i>
                                </div>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-lg font-semibold leading-tight mb-2">
                                <a href="{{ $article->url }}" class="hover:text-cyan-500">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ Str::limit($article->excerpt, 120) }}</p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">{{ $article->published_at->format('d.m.Y') }}</span>
                                <a href="{{ $article->url }}" class="text-cyan-500 font-medium hover:underline">
                                    Читать →
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Related Cases --}}
    @if($relatedCases->count() > 0)
        <section class="section-bg">
            <h2 class="block-title text-center">Наши кейсы</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedCases as $case)
                    <article class="rounded-md shadow-sm">
                        @if($case->image)
                            <div class="relative h-48 overflow-hidden rounded-t-md">
                                <img src="{{ asset('storage/' . $case->image) }}" alt="{{ $case->title }}" class="w-full h-full object-cover object-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                @if($case->industryCategory)
                                    <div class="absolute top-3 left-3">
                                        <a href="{{ route('cases.category', $case->industryCategory->slug ?? 'uncategorized') }}"
                                           class="inline-block px-2 py-1 bg-white/90 text-gray-800 text-xs rounded-md font-medium hover:bg-white transition-colors">
                                            {{ $case->industryCategory->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-start mb-4">
                                <div class="w-12 h-12 bg-cyan-500 rounded-md flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="material-icons text-white text-xl">trending_up</i>
                                </div>
                                <div class="flex-1">
                                    <a href="{{ $case->url }}" class="group">
                                        <h3 class="text-lg font-semibold leading-tight mb-2 group-hover:text-cyan-500 transition-colors">
                                            {{ $case->title }}
                                        </h3>
                                    </a>
                                    @if($case->client)
                                        <p class="text-sm text-gray-500 mb-2">Клиент: {{ $case->client }}</p>
                                    @endif
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $case->excerpt }}</p>
                            <div class="flex items-center text-sm">
                                @if($case->period)
                                    <span class="text-gray-500">{{ $case->period }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- FAQ Section --}}
    @if($servicesFaqs->count() > 0)
        <section class="section-bg">
            <h2 class="block-title text-center">Часто задаваемые вопросы</h2>
            <div class="max-w-3xl mx-auto">
                @foreach($servicesFaqs as $faq)
                    <div class="mb-4">
                        <details class="group">
                            <summary class="flex items-center justify-between w-full p-4 bg-white rounded-md shadow-sm cursor-pointer hover:bg-gray-50">
                                <span class="font-medium text-gray-800">{{ $faq->question }}</span>
                                <i class="material-icons text-gray-400 group-open:rotate-180 transition-transform">expand_more</i>
                            </summary>
                            <div class="p-4 bg-gray-50 rounded-b-md shadow-sm">
                                <p class="text-gray-600">{{ $faq->answer }}</p>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA Section --}}
    <section class="section-bg mt-24">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center rounded-md">
            <h2 class="text-3xl font-bold mb-4">Готовы заказать {{ $service->title }}?</h2>
            <p class="text-xl mb-6 opacity-90">Свяжитесь с нами для обсуждения вашего проекта</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                    onclick="openServiceOrderModal('{{ $service->title }}')">
                    Заказать {{ $service->title }}
                </button>
                <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                    onclick="window.dispatchEvent(new CustomEvent('open-callback'))">
                    Заказать звонок
                </button>
            </div>
        </div>
    </section>
@endsection
