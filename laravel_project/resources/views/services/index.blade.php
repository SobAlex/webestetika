@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="pt-8">
        @include('partials.breadcrumbs', ['breadcrumbs' => [['title' => 'Услуги', 'url' => null]]])
    </div>

    <section class="section-bg">
        <div class="text-center mb-12">
            <h1>{{ $title }}</h1>
            <p class="section-subtitle">
                Профессиональные услуги по продвижению и развитию вашего бизнеса в интернете
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <article class="bg-white/80 backdrop-blur-sm shadow-sm flex flex-col">
                    @if ($service->image)
                        <div class="aspect-video bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex-1">
                            <div class="flex items-start mb-4">
                                <div class="flex-1">
                                    <h2 class="group-hover:text-cyan-500 transition-colors">
                                        <a href="{{ route('services.show', $service->slug) }}" class="hover:text-cyan-500">
                                            {{ $service->title }}
                                        </a>
                                    </h2>
                                    @if ($service->is_featured)
                                        <span class="inline-block px-2 py-1 bg-yellow-100">Рекомендуемая</span>
                                    @endif
                                </div>
                            </div>
                            <p class="mb-4">{{ Str::limit($service->description, 120) }}</p>
                            @if ($service->features && is_array($service->features) && count($service->features) > 0)
                                <ul class="mb-4 space-y-1">
                                    @foreach (array_slice($service->features, 0, 3) as $feature)
                                        @if (is_string($feature))
                                            <li class="flex items-center">
                                                <i class="material-icons text-cyan-500 text-sm mr-2">check</i>
                                                {{ $feature }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-cyan-500 pt-4">
                            <div class="font-medium text-lg">
                                @if ($service->price_from)
                                    <div>{{ $service->formatted_price }}</div>
                                @else
                                    <div>По договоренности</div>
                                @endif
                            </div>
                            <div>
                                <button class="btn"
                                    onclick="openServiceOrderModal('{{ $service->title }}')">Заказать</button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4"><i class="material-icons text-6xl">business_center</i></div>
                    <h2 class="mb-2">Услуги временно недоступны</h2>
                    <p>Мы работаем над добавлением новых услуг</p>
                </div>
            @endforelse
        </div>
    </section>

    @if ($services->count() > 0)
        <section class="section-bg">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center">
                <h2 class="text-white">Готовы начать продвижение вашего сайта?</h2>
                <p class="mb-6 section-subtitle text-white">Свяжитесь с нами для обсуждения вашего проекта</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="btn" onclick="openServiceOrderModal('Заказать продвижение')">Заказать
                        продвижение</button>
                </div>
            </div>
        </section>
    @endif
@endsection
