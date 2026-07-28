@extends('layouts.app')

@section('title', 'Блог о SEO и веб-аналитике')
@section('meta_description',
    'Актуальные статьи о SEO, веб-аналитике и продвижении сайтов. Советы экспертов, новости
    поисковых систем и практические руководства.')

@section('content')
    <!-- Breadcrumbs -->
    <div class="pt-8">
        @include('partials.breadcrumbs', [
            'breadcrumbs' => [['title' => 'Блог', 'url' => null]],
        ])
    </div>

    <div class="py-12">
        <div class="text-center mb-12">
            <h1>Блог о SEO</h1>
            <p class="section-subtitle">
                Актуальные статьи о поисковой оптимизации, веб-аналитике и продвижении сайтов.
                Делимся опытом и рассказываем о последних трендах в SEO.
            </p>
        </div>

        @if ($activeBlogCategories && count($activeBlogCategories) > 0)
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                @foreach ($activeBlogCategories as $category)
                    <a href="{{ route('blog.category', $category['slug']) }}"
                        class="group bg-white/80 backdrop-blur-sm shadow-sm p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-4">
                            <i class="material-icons text-2xl mr-4"
                                style="color: {{ $category['color'] }}">{{ $category['icon'] }}</i>
                            <h2 class="group-hover:text-cyan-600 transition-colors mb-0">
                                {{ $category['name'] }}
                            </h2>
                        </div>
                        <p>{{ $category['description'] ?? 'Статьи по данной теме' }}</p>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mb-8">
            <h2 class="section-title">Последние статьи</h2>
            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
                @foreach ($articles as $article)
                    <article class="card-glass">
                        @if ($article->image)
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @else
                            <div
                                class="aspect-video bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
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
                            <div class="flex items-center mb-3">
                                @if ($article->hasActiveCategory())
                                    <span class="tag">{{ $article->active_category_name }}</span>
                                @endif
                                <span class="ml-auto">{{ $article->formatted_published_at }}</span>
                            </div>

                            <a href="{{ $article->url }}" class="group">
                                <h3 class="line-clamp-2 group-hover:text-cyan-600 transition-colors">
                                    {{ $article->title }}
                                </h3>
                            </a>

                            <p class="line-clamp-3">{{ $article->excerpt }}</p>

                            <div class="meta-info">
                                <i class="material-icons text-xs mr-1">schedule</i>
                                {{ $article->reading_time ?? '5' }} мин чтения
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">{{ $articles->links() }}</div>
        </div>
    </div>
@endsection
