<header class="border-b border-cyan-500 py-2 flex items-center justify-between relative" x-data="{ open: false }">
    <!-- Логотип слева -->
    <div>
        <a href="{{ route('home') }}"
            class="font-inter font-medium text-[38px] text-cyan-500 whitespace-nowrap hover:text-cyan-600 transition">Web<span class="font-cormorant italic font-light tracking-wide text-5xl">Estetika</span>
        </a>

        <p class="text-md">Оптимизация сайтов</p>
    </div>

    <!-- Главное меню (по центру ≥lg) -->
    <nav class="flex-1 flex items-center justify-center">
        <ul class="hidden lg:flex items-center space-x-8">
            <li class="relative group">
                <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-cyan-500 flex items-center">
                    Услуги
                    <i class="material-icons ml-1 text-sm">keyboard_arrow_down</i>
                </a>
                <div
                    class="absolute top-full left-0 mt-2 w-56 bg-white rounded-md shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-3">Популярные услуги:</div>
                        <div class="space-y-2">
                            @php
                                $headerServices = \App\Models\Service::published()->showOnHomepage()->ordered()->take(5)->get();
                            @endphp
                            @forelse($headerServices as $service)
                                <a href="{{ route('services.show', $service->slug) }}"
                                    class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                    @if($service->icon)
                                        <i class="material-icons text-sm mr-2" style="color: {{ $service->color ?? '#06b6d4' }}">{{ $service->icon }}</i>
                                    @endif
                                    {{ Str::limit($service->title, 25) }}
                                </a>
                            @empty
                                <div class="text-sm text-gray-500">Услуги загружаются...</div>
                            @endforelse
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3">
                            <a href="{{ route('services.index') }}"
                               class="flex items-center text-sm font-medium text-cyan-600 hover:text-cyan-700 transition">
                                <i class="material-icons text-sm mr-2">arrow_forward</i>
                                Все услуги
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="relative group">
                <a href="{{ route('cases.index') }}" class="text-gray-700 hover:text-cyan-500 flex items-center">
                    Кейсы
                    <i class="material-icons ml-1 text-sm">keyboard_arrow_down</i>
                </a>
                <div
                    class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-3">Популярные отрасли:</div>
                        <div class="space-y-2">
                            @if(isset($activeCaseCategories) && !empty($activeCaseCategories))
                                @foreach ($activeCaseCategories as $category)
                                    <a href="{{ route($category['route'], ...$category['route_params']) }}"
                                        class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                        <i class="material-icons text-sm mr-2"
                                            style="color: {{ $category['color'] }}">{{ $category['icon'] }}</i>
                                        {{ $category['name'] }}
                                    </a>
                                @endforeach
                            @else
                                {{-- Статичные категории для главной страницы --}}
                                <a href="{{ route('cases.category', 'clothing') }}" class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                    <i class="material-icons text-sm mr-2" style="color: #FFD700">apparel</i>
                                    Одежда
                                </a>
                                <a href="{{ route('cases.category', 'production') }}" class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                    <i class="material-icons text-sm mr-2" style="color: #3B82F6">factory</i>
                                    Production
                                </a>
                                <a href="{{ route('cases.category', 'electronics') }}" class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                    <i class="material-icons text-sm mr-2" style="color: #10B981">devices</i>
                                    Electronics
                                </a>
                                <a href="{{ route('cases.category', 'furniture') }}" class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                    <i class="material-icons text-sm mr-2" style="color: #8B5CF6">chair</i>
                                    Furniture
                                </a>
                            @endif
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3">
                            <a href="{{ route('cases.index') }}"
                                class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                                Все кейсы →
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="relative group">
                <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-cyan-500 flex items-center">
                    Блог
                    <i class="material-icons ml-1 text-sm">keyboard_arrow_down</i>
                </a>
                <div
                    class="absolute top-full left-0 mt-2 w-48 bg-white rounded-md shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-3">Популярные темы:</div>
                        <div class="space-y-2">
                            @foreach ($activeBlogCategories as $category)
                                <a href="{{ route('blog.category', $category['slug']) }}"
                                    class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                    <i class="material-icons text-sm mr-2"
                                        style="color: {{ $category['color'] }}">{{ $category['icon'] }}</i>
                                    {{ $category['name'] }}
                                </a>
                            @endforeach
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3">
                            <a href="{{ route('blog.index') }}"
                                class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                                Все статьи →
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="relative group">
                <a href="{{ route('contacts') }}" class="text-gray-700 hover:text-cyan-500 flex items-center">
                    Контакты
                    <i class="material-icons ml-1 text-sm">keyboard_arrow_down</i>
                </a>
                <div
                    class="absolute top-full left-0 mt-2 min-w-48 max-w-80 bg-white rounded-md shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="p-4">
                        <div class="text-sm font-semibold text-gray-600 mb-3">Свяжитесь с нами:</div>
                        <div class="space-y-2">
                            <a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}"
                                class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition whitespace-nowrap">
                                <i class="material-icons text-sm mr-2 text-cyan-500">phone</i>
                                {{ $contactInfo['phone'] }}
                            </a>
                            <a href="mailto:{{ $contactInfo['email'] }}"
                                class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition whitespace-nowrap">
                                <i class="material-icons text-sm mr-2 text-cyan-500">email</i>
                                {{ $contactInfo['email'] }}
                            </a>
                            <a href="#"
                                class="flex items-center text-sm text-gray-700 hover:text-cyan-600 transition">
                                <i class="material-icons text-sm mr-2 text-cyan-500">location_on</i>
                                <span class="break-words">{{ $contactInfo['address'] }}</span>
                            </a>
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3">
                            <a href="{{ route('contacts') }}"
                                class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                                Подробнее →
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Кнопка "Заказать звонок" (только ≥lg) -->
    <div class="hidden lg:block ml-4">
        <button class="btn" @click="$dispatch('open-callback')">
            Заказать звонок
        </button>
    </div>

    <!-- Бургер справа (<lg) -->
    <button @click="open = !open" class="lg:hidden flex-shrink-0 focus:outline-none ml-4 rounded-md" aria-label="Открыть меню"
        :aria-expanded="open.toString()">
        <svg x-show="!open" class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            style="display: block;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="open" class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Мобильное меню -->
    <div x-show="open" @click.away="open = false"
        class="absolute left-0 right-0 top-full bg-white rounded-md shadow-sm z-50 lg:hidden"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2" style="display: none;">
        <ul class="flex flex-col space-y-6 p-6">
            <li x-data="{ msub: false }">
                <button @click="msub = !msub"
                    class="w-full text-left flex items-center justify-between text-gray-700 hover:text-cyan-500 text-lg font-medium mt-4 rounded-md"
                    type="button" :aria-expanded="msub.toString()" aria-haspopup="true">
                    Услуги
                    <svg class="w-5 h-5 ml-2 transform transition-transform duration-200"
                        :class="msub ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="msub" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                    class="pl-6 space-y-4 overflow-hidden" style="display: none;">
                    <div class="text-gray-600 text-sm font-medium mb-3">Популярные услуги:</div>
                    <div class="space-y-2">
                        @php
                            $mobileServices = \App\Models\Service::published()->showOnHomepage()->ordered()->take(4)->get();
                        @endphp
                        @forelse($mobileServices as $service)
                            <a href="{{ route('services.show', $service->slug) }}"
                                class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                @if($service->icon)
                                    <i class="material-icons text-sm mr-2" style="color: {{ $service->color ?? '#06b6d4' }}">{{ $service->icon }}</i>
                                @endif
                                {{ Str::limit($service->title, 30) }}
                            </a>
                        @empty
                            <div class="text-sm text-gray-500">Услуги загружаются...</div>
                        @endforelse
                        <div class="border-t border-gray-200 mt-3 pt-3">
                            <a href="{{ route('services.index') }}"
                               class="flex items-center text-sm font-medium text-cyan-600 hover:text-cyan-700 transition">
                                <i class="material-icons text-sm mr-2">arrow_forward</i>
                                Все услуги
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li x-data="{ msub: false }">
                <button @click="msub = !msub"
                    class="w-full text-left flex items-center justify-between text-gray-700 hover:text-cyan-500 text-lg font-medium rounded-lg"
                    type="button" :aria-expanded="msub.toString()" aria-haspopup="true">
                    Кейсы
                    <svg class="w-5 h-5 ml-2 transform transition-transform duration-200"
                        :class="msub ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="msub" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                    class="pl-6 space-y-4 overflow-hidden" style="display: none;">
                    <div class="text-gray-600 text-sm font-medium mb-3">Популярные отрасли:</div>
                    <div class="space-y-2">
                        @if(isset($activeCategories) && !empty($activeCategories))
                            @foreach ($activeCategories as $category)
                                <a href="{{ route($category['route'], ...$category['route_params']) }}"
                                    class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                    <i class="material-icons text-sm mr-2"
                                        style="color: {{ $category['color'] }}">{{ $category['icon'] }}</i>
                                    {{ $category['name'] }}
                                </a>
                            @endforeach
                        @else
                            {{-- Статичные категории для мобильного меню --}}
                            <a href="{{ route('cases.category', 'clothing') }}" class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                <i class="material-icons text-sm mr-2" style="color: #FFD700">apparel</i>
                                Одежда
                            </a>
                            <a href="{{ route('cases.category', 'production') }}" class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                <i class="material-icons text-sm mr-2" style="color: #3B82F6">factory</i>
                                Production
                            </a>
                            <a href="{{ route('cases.category', 'electronics') }}" class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                <i class="material-icons text-sm mr-2" style="color: #10B981">devices</i>
                                Electronics
                            </a>
                            <a href="{{ route('cases.category', 'furniture') }}" class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                <i class="material-icons text-sm mr-2" style="color: #8B5CF6">chair</i>
                                Furniture
                            </a>
                        @endif
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <a href="{{ route('cases.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                            Все кейсы →
                        </a>
                    </div>
                </div>
            </li>
            <li x-data="{ msub: false }">
                <button @click="msub = !msub"
                    class="w-full text-left flex items-center justify-between text-gray-700 hover:text-cyan-500 text-lg font-medium rounded-md"
                    type="button" :aria-expanded="msub.toString()" aria-haspopup="true">
                    Блог
                    <svg class="w-5 h-5 ml-2 transform transition-transform duration-200"
                        :class="msub ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="msub" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                    class="pl-6 space-y-4 overflow-hidden" style="display: none;">
                    <div class="text-gray-600 text-sm font-medium mb-3">Популярные темы:</div>
                    <div class="space-y-2">
                        @foreach ($activeBlogCategories as $category)
                            <a href="{{ route('blog.category', $category['slug']) }}"
                                class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                                <i class="material-icons text-sm mr-2"
                                    style="color: {{ $category['color'] }}">{{ $category['icon'] }}</i>
                                {{ $category['name'] }}
                            </a>
                        @endforeach
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <a href="{{ route('blog.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                            Все статьи →
                        </a>
                    </div>
                </div>
            </li>
            <li x-data="{ msub: false }">
                <button @click="msub = !msub"
                    class="w-full text-left flex items-center justify-between text-gray-700 hover:text-cyan-500 text-lg font-medium rounded-md"
                    type="button" :aria-expanded="msub.toString()" aria-haspopup="true">
                    Контакты
                    <svg class="w-5 h-5 ml-2 transform transition-transform duration-200"
                        :class="msub ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="msub" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                    class="pl-6 space-y-4 overflow-hidden" style="display: none;">
                    <div class="text-gray-600 text-sm font-medium mb-3">Свяжитесь с нами:</div>
                    <div class="space-y-2">
                        <a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}"
                            class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                            <i class="material-icons text-sm mr-2" style="color: #06b6d4">phone</i>
                            {{ $contactInfo['phone'] }}
                        </a>
                        <a href="mailto:{{ $contactInfo['email'] }}"
                            class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                            <i class="material-icons text-sm mr-2" style="color: #06b6d4">email</i>
                            <span class="break-all">{{ $contactInfo['email'] }}</span>
                        </a>
                        <a href="#"
                            class="flex items-center text-sm text-gray-600 hover:text-cyan-600 transition">
                            <i class="material-icons text-sm mr-2" style="color: #06b6d4">location_on</i>
                            <span class="break-words">{{ $contactInfo['address'] }}</span>
                        </a>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <a href="{{ route('contacts') }}"
                            class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                            Подробнее →
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
