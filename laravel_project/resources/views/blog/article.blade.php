@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title . ' - Блог SobAlex')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')
    @include('partials.article-json-ld')

    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => array_filter([
                ['title' => 'Блог', 'url' => route('blog.index')],
                $article->hasActiveCategory()
                    ? [
                        'title' => $article->active_category_name,
                        'url' => route('blog.category', $article->blogCategory->slug ?? 'uncategorized'),
                    ]
                    : null,
                ['title' => $article->title, 'url' => null, 'truncate' => true],
            ]),
        ])
    </div>

    <div class="max-w-4xl mx-auto py-12">
        <article class="card">
            @if ($article->image)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="media-fallback aspect-video">
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

            <div class="p-8">
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    @if ($article->hasActiveCategory())
                        <span class="tag">{{ $article->active_category_name }}</span>
                    @endif
                    <span class="meta-info">
                        <i class="material-icons text-xs mr-1">schedule</i>
                        {{ $article->reading_time ?? '5' }} мин чтения
                    </span>
                    <span class="meta-info">
                        <i class="material-icons text-xs mr-1">calendar_today</i>
                        {{ $article->formatted_published_at }}
                    </span>
                </div>

                <h1>{{ $article->title }}</h1>
                <p>{{ $article->excerpt }}</p>
                <div class="service-content">{!! $article->content !!}</div>
            </div>
        </article>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                @if ($article->hasActiveCategory())
                    <a href="{{ route('blog.category', $article->blogCategory->slug ?? 'uncategorized') }}">← Все статьи в категории</a>
                @else
                    <div></div>
                @endif
                <a href="{{ route('blog.index') }}">Все статьи блога →</a>
            </div>
        </div>

        @if ($relatedArticles->count() > 0)
            <div class="mt-16">
                <h2>Похожие статьи</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach ($relatedArticles as $relatedArticle)
                        <article class="card-glass">
                            <a href="{{ $relatedArticle->url }}">
                                @if ($relatedArticle->image)
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ $relatedArticle->image_url }}" alt="{{ $relatedArticle->title }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="media-fallback aspect-video">
                                        <i class="material-icons text-white text-2xl">
                                            @if ($relatedArticle->blogCategory && $relatedArticle->blogCategory->slug === 'seo-news')
                                                trending_up
                                            @elseif($relatedArticle->blogCategory && $relatedArticle->blogCategory->slug === 'analytics')
                                                analytics
                                            @else
                                                tips_and_updates
                                            @endif
                                        </i>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                @if ($relatedArticle->hasActiveCategory())
                                    <a href="{{ route('blog.category', $relatedArticle->blogCategory->slug ?? 'uncategorized') }}"
                                        class="inline-flex items-center px-2 py-1 bg-cyan-100 text-cyan-800 mb-2 hover:bg-cyan-200 transition-colors">
                                        {{ $relatedArticle->category_name }}
                                    </a>
                                @endif
                                <a href="{{ $relatedArticle->url }}">
                                    <h3 class="group-hover:text-cyan-600 transition-colors line-clamp-2">
                                        {{ $relatedArticle->title }}
                                    </h3>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
