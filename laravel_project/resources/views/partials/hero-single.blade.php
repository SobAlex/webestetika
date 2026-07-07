{{-- Одиночный Hero блок --}}
<div class="hero-container flex flex-col md:flex-row gap-4">
    <!-- Левая часть -->
    <div class="w-full md:basis-1/2 lg:basis-2/3 md:w-auto flex flex-col justify-between px-4 pt-4 pb-16 text-center sm:text-left">
        <!-- Контент -->
        <div>
            <!-- Заголовок -->
            <h1 class="text-3xl leading-relaxed mb-6 sm:mb-8">
                {{ $activeHeroes->title }}
            </h1>

            @if($activeHeroes->description)
                <!-- Описание -->
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    {{ $activeHeroes->description }}
                </p>
            @endif
        </div>

        <!-- Форма -->
        @include('partials.hero-form', [
            'buttonText' => $activeHeroes->button_text,
            'buttonAriaLabel' => $activeHeroes->button_text,
            'idSuffix' => ''
        ])
    </div>

    <!-- Правая часть (картинка) -->
    <div class="w-full md:basis-1/2 lg:basis-1/3 md:w-auto hero-image-container px-4 py-6 overflow-hidden flex items-center justify-center">
        @if($activeHeroes->image)
            <img src="{{ $activeHeroes->image_url }}" alt="{{ $activeHeroes->title }}"
                loading="eager" decoding="async" fetchpriority="high" />
        @else
            <img src="{{ asset('images/human.webp') }}" alt="{{ $activeHeroes->title }}"
                loading="eager" decoding="async" fetchpriority="high" />
        @endif
    </div>
</div>
