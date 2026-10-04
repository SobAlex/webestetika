@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    {{-- JSON-LD структурированные данные --}}
    @include('partials.breadcrumbs-json-ld', compact('breadcrumbs'))

    <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-x-1 gap-y-2 md:gap-x-3" itemscope
            itemtype="https://schema.org/BreadcrumbList">
            <!-- Главная страница всегда первая -->
            <li class="inline-flex items-center" itemprop="itemListElement" itemscope
                itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="inline-flex items-center" itemprop="item">
                    <i class="material-icons text-sm mr-1">home</i>
                    <span itemprop="name">Главная</span>
                </a>
                <meta itemprop="position" content="1" />
            </li>

            <!-- Остальные крошки -->
            @foreach ($breadcrumbs as $key => $breadcrumb)
                <li class="min-w-0 max-w-full" @if ($loop->last) aria-current="page" @endif itemprop="itemListElement" itemscope
                    itemtype="https://schema.org/ListItem">
                    <div class="flex min-w-0 items-center">
                        <i class="material-icons shrink-0 text-sm text-gray-400">chevron_right</i>
                        @if ($loop->last)
                            <!-- Последний элемент - не ссылка -->
                            <span class="ml-1 min-w-0 truncate md:ml-2 @if (isset($breadcrumb['truncate']) && $breadcrumb['truncate']) max-w-[70vw] sm:max-w-md @endif"
                                itemprop="name">
                                {{ $breadcrumb['title'] }}
                            </span>
                        @else
                            <!-- Промежуточные элементы - ссылки -->
                            <a href="{{ $breadcrumb['url'] }}" class="ml-1 md:ml-2" itemprop="item">
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
