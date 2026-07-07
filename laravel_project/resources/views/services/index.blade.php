@extends('layouts.app')

@section('title', $title)

@section('content')
    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [['title' => 'Услуги', 'url' => null]],
        ])
    </div>

    {{-- Hero section --}}
    <section class="section-bg">
        <div class="text-center mb-12">
            <h1 class="page-title">{{ $title }}</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Профессиональные услуги по продвижению и развитию вашего бизнеса в интернете
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                <article class="bg-white/80 backdrop-blur-sm rounded-md shadow-sm">
                    <!-- Service Image -->
                    @if($service->image)
                        <div class="aspect-video bg-gray-100 overflow-hidden rounded-t-lg">
                            <img src="{{ asset('storage/' . $service->image) }}"
                                 alt="{{ $service->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6">
                        <!-- Service Icon and Title -->
                        <div class="flex items-start mb-4">
                            @if($service->icon)
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-12 h-12 rounded-md flex items-center justify-center"
                                         style="background-color: {{ $service->color }}20; border: 1px solid {{ $service->color }}40;">
                                        <i class="material-icons text-2xl" style="color: {{ $service->color }}">{{ $service->icon }}</i>
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1">
                                <a href="{{ route('services.show', $service->slug) }}" class="group">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-cyan-500 transition-colors">
                                        {{ $service->title }}
                                    </h3>
                                </a>
                                @if($service->is_featured)
                                    <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-md">
                                        Рекомендуемая
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 120) }}</p>

                        <!-- Features -->
                        @if($service->features && is_array($service->features) && count($service->features) > 0)
                            <ul class="text-sm text-gray-500 mb-4 space-y-1">
                                @foreach(array_slice($service->features, 0, 3) as $feature)
                                    @if(is_string($feature))
                                        <li class="flex items-center">
                                            <i class="material-icons text-cyan-500 text-sm mr-2">check</i>
                                            {{ $feature }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        <!-- Price and CTA -->
                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-cyan-500">
                            <div>
                                @if($service->price_from)
                                    <div class="text-lg font-bold text-cyan-600">{{ $service->formatted_price }}</div>
                                @else
                                    <div class="text-sm text-gray-500">По договоренности</div>
                                @endif
                            </div>
                            <div>
                                <button class="btn text-sm px-4 py-2" onclick="openServiceOrderModal('{{ $service->title }}')">
                                    Заказать
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="material-icons text-6xl">business_center</i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Услуги временно недоступны</h3>
                    <p class="text-gray-500">Мы работаем над добавлением новых услуг</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- CTA Section --}}
    @if($services->count() > 0)
        <section class="section-bg">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center rounded-md">
                <h2 class="text-3xl font-bold mb-4">Готовы начать продвижение вашего сайта?</h2>
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
    @endif
@endsection
