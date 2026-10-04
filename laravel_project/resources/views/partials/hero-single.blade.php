{{-- Одиночный Hero блок --}}
<div class="hero-container flex flex-col gap-6 lg:flex-row lg:items-center">
    <!-- Левая часть (заголовок, описание, форма) – на мобилках будет второй, на десктопах первым -->
    <div
        class="order-2 flex w-full flex-col justify-between pt-4 pb-8 text-center sm:text-left lg:order-1 lg:w-auto lg:basis-2/3">
        <div>
            <h1 class="mb-6 sm:mb-8">
                {{ $activeHeroes->title }}
            </h1>

            @if ($activeHeroes->description)
                <p class="mb-8">
                    {{ $activeHeroes->description }}
                </p>
            @endif
        </div>

        @include('partials.hero-form', [
            'buttonText' => $activeHeroes->button_text,
            'buttonAriaLabel' => $activeHeroes->button_text,
            'idSuffix' => '',
        ])
    </div>

    <!-- Правая часть (картинка) – на мобилках будет первой, на десктопах второй -->
    <div
        class="hero-image-container order-1 flex w-full items-center justify-center overflow-hidden lg:order-2 lg:w-auto lg:basis-1/3">
        @if ($activeHeroes->image)
            <img src="{{ $activeHeroes->image_url }}" alt="{{ $activeHeroes->title }}"
                class="h-auto max-h-64 w-full object-contain sm:max-h-80 lg:max-h-96" loading="eager" decoding="async"
                fetchpriority="high" />
        @else
            <img src="{{ asset('images/human.webp') }}" alt="{{ $activeHeroes->title }}"
                class="h-auto max-h-64 w-full object-contain sm:max-h-80 lg:max-h-96" loading="eager"
                decoding="async" fetchpriority="high" />
        @endif
    </div>
</div>
