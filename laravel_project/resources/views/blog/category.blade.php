@extends('layouts.app')

@section('title', $category . ' - Блог SobAlex')
@section('meta_description',
    'Статьи в категории ' .
    $category .
    '. Актуальная информация о SEO, веб-аналитике и продвижении
    сайтов.')

@section('content')
    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [
                ['title' => 'Блог', 'url' => route('blog.index')],
                ['title' => $category, 'url' => null],
            ],
        ])
    </div>

    <div class="py-12">
        <div class="text-center mb-12">
            <h1>{{ $category }}</h1>
            <p class="max-w-3xl mx-auto section-subtitle">
                {{ $blogCategory->description ?? 'Статьи по данной теме' }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($articles as $article)
                <article class="card">
                    @if ($article->image)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div
                            class="media-fallback aspect-video">
                            <i class="material-icons text-white text-4xl">
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

                    <div class="p-6">
                        <div class="flex justify-between mb-3">
                            @if ($article->hasActiveCategory())
                                <span class="tag">{{ $article->active_category_name }}</span>
                            @endif
                            <span class="ml-auto">{{ $article->formatted_published_at }}</span>
                        </div>

                        <a href="{{ $article->url }}" class="group">
                            <h2 class="line-clamp-2 group-hover:text-cyan-600 transition-colors">
                                {{ $article->title }}
                            </h2>
                        </a>

                        <p class="line-clamp-3">{{ $article->excerpt }}</p>

                        <div class="meta-info mt-4">
                            <i class="material-icons text-xs mr-1">schedule</i>
                            {{ $article->reading_time ?? '5' }} мин чтения
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-12">{{ $articles->links() }}</div>

        @if ($activeBlogCategories && count($activeBlogCategories) > 1)
            <div class="mt-16 pt-12 border-t border-gray-200">
                <h2 class="section-title">Другие категории</h2>
                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    @foreach ($activeBlogCategories as $category)
                        @if ($category['slug'] !== $categorySlug)
                            <a href="{{ route('blog.category', $category['slug']) }}"
                                class="card-glass group p-6">
                                <div class="flex items-center mb-4">
                                    <i class="material-icons mr-4 text-xl {{ \App\Support\Accent::text($category['color']) }}">{{ $category['icon'] }}</i>
                                    <h3 class="group-hover:text-cyan-600 transition-colors mb-0">
                                        {{ $category['name'] }}
                                    </h3>
                                </div>
                                <p>{{ $category['description'] ?? 'Статьи по данной теме' }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
