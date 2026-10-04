@extends('layouts.app')

@section('title', 'SEO продвижение сайтов в интернете')

@section('content')
    {{-- hero --}}
    <section id="hero" class="section-bg relative">
        @if ($activeHeroes && $activeHeroes->count() > 0)
            @if ($activeHeroes->count() === 1)
                @include('partials.hero-single', ['activeHeroes' => $activeHeroes->first()])
            @else
                @include('partials.hero-slider', ['activeHeroes' => $activeHeroes])
            @endif
        @else
            {{-- @include('partials.hero-fallback') --}}
        @endif
    </section>

    {{-- Service --}}
    <section id="services" class="section-bg">
        <h2 class="section-title">Услуги</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-6">
            @forelse($featuredServices as $service)
                <article class="card-glass flex flex-col">
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
                                    <h3>
                                        <a href="{{ route('services.show', $service->slug) }}" class="hover:text-cyan-500">
                                            {{ $service->title }}
                                        </a>
                                    </h3>
                                    <p class="mb-4">{{ $service->description }}</p>
                                </div>
                            </div>
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
                            <div class="text-cyan-500 font-medium text-lg">
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
                    <p>Услуги временно недоступны</p>
                </div>
            @endforelse
        </div>

        @if ($featuredServices->count() > 0)
            <div class="text-center mt-8">
                <a href="{{ route('services.index') }}" class="transition-colors duration-200">
                    Все услуги →
                </a>
            </div>
        @endif
    </section>

    {{-- Why we --}}
    <section id="why" class="section-bg">
        <h2 class="section-title">Почему мы</h2>
        @if ($whyUsBlocks && $whyUsBlocks->count() > 0)
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($whyUsBlocks as $block)
                    <li class="bg-white/80 backdrop-blur-sm shadow-sm p-4 flex justify-center items-start">
                        <div class="flex flex-col items-center">
                            @if ($block->icon)
                                <i class="material-icons mb-3 text-5xl {{ \App\Support\Accent::text($block->color ?: '#06b6d4') }}" aria-label="{{ $block->title }}" role="img">{{ $block->icon }}</i>
                            @endif
                            <div class="text-center">
                                <h3>{{ $block->title }}</h3>
                                @if ($block->description)
                                    <p>{{ $block->description }}</p>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Cases --}}
    <section id="cases" class="section-bg">
        <h2 class="section-title">Наши кейсы</h2>
        <p class="section-subtitle">
            Реальные результаты нашей работы. Каждый кейс — это история успеха наших клиентов
            и доказательство эффективности наших методов продвижения.
        </p>

        @if (!empty($transformlatestCases))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($transformlatestCases as $case)
                    <article class="card-glass flex flex-col h-full">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $case['image_url'] }}" alt="{{ $case['title'] }}"
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute right-4 bottom-4 left-4">
                                @if ($case['has_valid_category'])
                                    <a href="{{ route('cases.category', $case['industry']) }}"
                                        class="bg-cyan-500 hover:bg-cyan-600 px-3 py-1 transition-colors inline-block text-white">
                                        {{ $case['industry_name'] }}
                                    </a>
                                @else
                                    <span class="bg-gray-500 px-3 py-1 inline-block">{{ $case['industry_name'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <a href="{{ route('cases.show', $case['id']) }}" class="group">
                                <h3 class="group-hover:text-cyan-600 transition-colors">{{ $case['title'] }}</h3>
                            </a>
                            <p class="mb-3">{{ $case['client'] }} • {{ $case['period'] }}</p>
                            <p class="mb-4 line-clamp-2">{{ Str::limit($case['description_clean'], 120) }}</p>
                            <div class="space-y-2">
                                @if (!empty($case['results']) && is_array($case['results']))
                                    @foreach (array_slice($case['results'], 0, 2) as $result)
                                        <div class="flex items-start">
                                            <i class="material-icons mr-2 shrink-0 text-base text-cyan-500">trending_up</i>
                                            <span class="min-w-0">{{ $result }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('cases.index') }}" class="transition-colors duration-200">Все кейсы →</a>
            </div>
        @else
            <div class="text-center py-16">
                <h3 class="mb-4">Кейсы временно недоступны</h3>
                <p class="mb-8">В данный момент у нас нет доступных кейсов для отображения.</p>
            </div>
        @endif
    </section>

    {{-- Blog --}}
    <section id="blog" class="section-bg">
        <h2 class="section-title">Последние статьи</h2>
        <p class="section-subtitle">
            Полезные материалы о SEO, аналитике и продвижении сайтов.
            Делимся опытом и актуальными новостями из мира поисковой оптимизации.
        </p>

        @if (!empty($latestArticles))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($latestArticles as $article)
                    <article class="card-glass flex flex-col h-full">
                        @if ($article->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            </div>
                        @else
                            <div
                                class="relative h-48 overflow-hidden bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                                <i class="material-icons text-white text-6xl">
                                    @if ($article->blogCategory && $article->blogCategory->slug === 'seo-news')
                                        trending_up
                                    @elseif($article->blogCategory && $article->blogCategory->slug === 'analytics')
                                        analytics
                                    @else
                                        tips_and_updates
                                    @endif
                                </i>
                            </div>
                        @endif
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="meta-info justify-between mb-3">
                                <a href="{{ route('blog.category', $article->blogCategory->slug ?? 'uncategorized') }}"
                                    class="tag">
                                    {{ $article->category_name }}
                                </a>
                                <span>{{ $article->formatted_published_at }}</span>
                            </div>
                            <a href="{{ $article->url }}" class="group">
                                <h3 class="line-clamp-2 group-hover:text-cyan-600 transition-colors">
                                    {{ $article->title }}</h3>
                            </a>
                            <p class="mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                            <div class="meta-info mt-auto"> <!-- ← добавлен mt-auto -->
                                <i class="material-icons mr-1">schedule</i>
                                <span>{{ $article->reading_time ?? '5' }} мин чтения</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('blog.index') }}" class="transition-colors duration-200">Все статьи →</a>
            </div>
        @else
            <div class="text-center py-16">
                <h3 class="mb-4">Статьи временно недоступны</h3>
                <p class="mb-8">В данный момент у нас нет доступных статей для отображения.</p>
            </div>
        @endif
    </section>

    {{-- FAQ --}}
    <div id="faq">
        @include('partials.faq-section')
    </div>

    {{-- Reviews --}}
    <section id="reviews" class="section-bg">
        <h2 class="section-title">Отзывы наших клиентов</h2>
        <p class="section-subtitle">
            Реальные истории успеха от наших клиентов. Узнайте, как мы помогли им достичь
            выдающихся результатов в SEO-продвижении.
        </p>
        @if (!empty($randomReviews))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($randomReviews as $review)
                    <article class="card-glass flex flex-col h-full">
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex-1">
                                <div class="flex items-center mb-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="material-icons text-yellow-400 text-sm">{{ $i <= $review['rating'] ? 'star' : 'star_border' }}</i>
                                    @endfor
                                </div>
                                <blockquote class="mb-6 flex-1 italic line-clamp-4">"{{ $review['text'] }}"</blockquote>
                            </div>
                            <div class="flex items-center">
                                <div class="w-12 h-12 overflow-hidden mr-4 flex-shrink-0">
                                    <img src="{{ asset('images/' . $review['avatar']) }}" alt="{{ $review['name'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div>{{ $review['name'] }}</div>
                                    <div>{{ $review['position'] }}</div>
                                    <div>{{ $review['company'] }}</div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <h3 class="mb-4">Отзывы временно недоступны</h3>
                <p class="mb-8">В данный момент у нас нет доступных отзывов для отображения.</p>
            </div>
        @endif
    </section>

    {{-- Contact block on welcome page --}}
    <section id="contact-form" class="section-bg flex flex-col lg:items-start items-center lg:flex-row lg:justify-around">
        <div class="w-full sm:w-2/3 md:basis-1/3 mb-9 lg:mb-0">
            <h2 class="section-title">Контакты</h2>
            <p class="mb-6">Заполните форму, и наша команда свяжется с вами в течение 24 часов. Или свяжитесь с нами
                напрямую:</p>
            <div class="space-y-4">
                <!-- Адрес -->
                <div class="flex items-start space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-500 flex-shrink-0 mt-0.5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div>
                        <p class="contact-label">Наш офис:</p>
                        <p>{{ $contactInfo['address'] }}</p>
                    </div>
                </div>
                <!-- Телефон -->
                <div class="flex items-start space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-500 flex-shrink-0 mt-0.5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div>
                        <p class="contact-label">Телефон:</p>
                        <a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}"
                            class="text-cyan-500">{{ $contactInfo['phone'] }}</a>
                    </div>
                </div>
                <!-- Email -->
                <div class="flex items-start space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-500 flex-shrink-0 mt-0.5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <p class="contact-label">Email:</p>
                        <a href="mailto:{{ $contactInfo['email'] }}"
                            class="text-cyan-500">{{ $contactInfo['email'] }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full sm:w-2/3 md:basis-1/3">
            <h2 class="section-title" id="contact_form_title">Отправить заявку</h2>
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4"
                aria-labelledby="contact_form_title">
                @csrf
                <div><label for="name_contact">Имя</label>
                    <input type="text" name="name" id="name_contact" required placeholder="Ваше имя"
                        aria-required="true" aria-label="Имя" class="form-control mt-1">
                </div>
                <div>
                    <label for="email_contact">Email</label>
                    <input type="email" name="email" id="email_contact" required placeholder="your@email.com"
                        aria-required="true" aria-label="Email" class="form-control mt-1">
                </div>
                <div>
                    <label for="phone_contact">Телефон *</label>
                    <input type="tel" name="phone" id="phone_contact" required aria-label="Телефон"
                        placeholder="+7 (999) 999-99-99" class="form-control mt-1">
                </div>
                <div>
                    <label for="message_contact">Сообщение</label>
                    <textarea name="message" id="message_contact" rows="5" required placeholder="Расскажите о вашем проекте..."
                        aria-required="true" aria-label="Сообщение" class="form-control mt-1">{{ old('message') }}</textarea>
                </div>
                @include('partials._consent')
                <div><button type="submit" class="btn" aria-label="Отправить сообщение">Отправить</button></div>
            </form>
        </div>
    </section>
@endsection
