@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title . ' - Блог SobAlex')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')
    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => array_filter([
                ['title' => 'Блог', 'url' => route('blog.index')],
                $article->hasActiveCategory() ? [
                    'title' => $article->active_category_name,
                    'url' => route('blog.category', $article->blogCategory->slug ?? 'uncategorized'),
                ] : null,
                ['title' => $article->title, 'url' => null, 'truncate' => true],
            ]),
        ])
    </div>

    <div class="max-w-4xl mx-auto py-12">
        <!-- Заголовок статьи -->
        <article class="bg-white rounded-md shadow-sm overflow-hidden">
            <!-- Изображение статьи -->
            @if ($article->image)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="aspect-video bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
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
                <!-- Метаданные статьи -->
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    @if ($article->hasActiveCategory())
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-cyan-100 text-cyan-800">
                            {{ $article->active_category_name }}
                        </span>
                    @endif
                    <span class="text-gray-500 text-sm flex items-center">
                        <i class="material-icons text-xs mr-1">schedule</i>
                        {{ $article->reading_time ?? '5' }} мин чтения
                    </span>
                    <span class="text-gray-500 text-sm flex items-center">
                        <i class="material-icons text-xs mr-1">calendar_today</i>
                        {{ $article->formatted_published_at }}
                    </span>
                </div>

                <!-- Заголовок -->
                <h1 class="page-title">
                    {{ $article->title }}
                </h1>

                <!-- Описание -->
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    {{ $article->excerpt }}
                </p>

                <!-- Содержание статьи -->
                <div class="prose prose-lg max-w-none">
                    {!! $article->content !!}
                </div>
            </div>
        </article>

        <!-- Навигация между статьями -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between">
                @if ($article->hasActiveCategory())
                    <a href="{{ route('blog.category', $article->blogCategory->slug ?? 'uncategorized') }}"
                        class="btn inline-flex items-center">
                        <i class="material-icons text-sm mr-2">arrow_back</i>
                        Все статьи в категории
                    </a>
                @else
                    <div></div>
                @endif

                <a href="{{ route('blog.index') }}" class="btn inline-flex items-center">
                    Все статьи блога
                    <i class="material-icons text-sm ml-2">arrow_forward</i>
                </a>
            </div>
        </div>

        <!-- Похожие статьи -->
        @if ($relatedArticles->count() > 0)
            <div class="mt-16">
                <h2 class="block-title">Похожие статьи</h2>

                <div class="grid md:grid-cols-3 gap-6">
                    @foreach ($relatedArticles as $relatedArticle)
                        <article class="group bg-white rounded-md shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <a href="{{ $relatedArticle->url }}">
                                @if ($relatedArticle->image)
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ $relatedArticle->image_url }}" alt="{{ $relatedArticle->title }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="aspect-video bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
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
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-cyan-100 text-cyan-800 mb-2 hover:bg-cyan-200 transition-colors">
                                        {{ $relatedArticle->category_name }}
                                    </a>
                                @endif

                                <a href="{{ $relatedArticle->url }}">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 group-hover:text-cyan-600 transition-colors line-clamp-2">
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
