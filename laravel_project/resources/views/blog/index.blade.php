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
        <!-- Заголовок страницы -->
        <div class="text-center mb-12">
            <h1 class="page-title">Блог о SEO</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Актуальные статьи о поисковой оптимизации, веб-аналитике и продвижении сайтов.
                Делимся опытом и рассказываем о последних трендах в SEO.
            </p>
        </div>

        <!-- Категории -->
        @if ($activeBlogCategories && count($activeBlogCategories) > 0)
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                @foreach ($activeBlogCategories as $category)
                    <a href="{{ route('blog.category', $category['slug']) }}"
                        class="group bg-white/80 backdrop-blur-sm rounded-md shadow-sm p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-md" style="background-color: {{ $category['color'] }}20">
                                <i class="material-icons text-2xl"
                                    style="color: {{ $category['color'] }}">{{ $category['icon'] }}</i>
                            </div>
                            <h2
                                class="text-xl font-semibold text-gray-900 ml-4 group-hover:text-cyan-600 transition-colors">
                                {{ $category['name'] }}
                            </h2>
                        </div>
                        <p class="text-gray-600">
                            {{ $category['description'] ?? 'Статьи по данной теме' }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Последние статьи -->
        <div class="mb-8">
            <h2 class="block-title text-center">Последние статьи</h2>

            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
                @foreach ($articles as $article)
                    <article
                        class="bg-white/80 backdrop-blur-sm rounded-md shadow-sm overflow-hidden">
                        @if ($article->image)
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
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
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-cyan-100 text-cyan-800">
                                        {{ $article->active_category_name }}
                                    </span>
                                @endif
                                <span class="text-gray-500 text-sm ml-auto">
                                    {{ $article->formatted_published_at }}
                                </span>
                            </div>

                            <a href="{{ $article->url }}" class="group">
                                <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2 group-hover:text-cyan-600 transition-colors">
                                    {{ $article->title }}
                                </h3>
                            </a>

                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $article->excerpt }}
                            </p>

                            <div class="flex items-center">
                                <span class="text-sm text-gray-500 flex items-center">
                                    <i class="material-icons text-xs mr-1">schedule</i>
                                    {{ $article->reading_time ?? '5' }} мин чтения
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
@endsection
