@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    {{-- JSON-LD структурированные данные --}}
    @include('partials.breadcrumbs-json-ld', compact('breadcrumbs'))

    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3" itemscope
            itemtype="https://schema.org/BreadcrumbList">
            <!-- Главная страница всегда первая -->
            <li class="inline-flex items-center" itemprop="itemListElement" itemscope
                itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-cyan-600 inline-flex items-center"
                    itemprop="item">
                    <i class="material-icons text-sm mr-1">home</i>
                    <span itemprop="name">Главная</span>
                </a>
                <meta itemprop="position" content="1" />
            </li>

            <!-- Остальные крошки -->
            @foreach ($breadcrumbs as $key => $breadcrumb)
                <li @if ($loop->last) aria-current="page" @endif itemprop="itemListElement" itemscope
                    itemtype="https://schema.org/ListItem">
                    <div class="flex items-center">
                        <i class="material-icons text-gray-400 text-sm">chevron_right</i>
                        @if ($loop->last)
                            <!-- Последний элемент - не ссылка -->
                            <span
                                class="ml-1 text-gray-500 md:ml-2 @if (isset($breadcrumb['truncate']) && $breadcrumb['truncate']) line-clamp-1 @endif"
                                itemprop="name">
                                {{ $breadcrumb['title'] }}
                            </span>
                        @else
                            <!-- Промежуточные элементы - ссылки -->
                            <a href="{{ $breadcrumb['url'] }}" class="ml-1 text-gray-700 hover:text-cyan-600 md:ml-2"
                                itemprop="item">
                                <span itemprop="name">{{ $breadcrumb['title'] }}</span>
                            </a>
                        @endif
                    </div>
                    <meta itemprop="position" content="{{ $loop->index + 2 }}" />
                </li>
            @endforeach
        </ol>
    </nav>
@endif
