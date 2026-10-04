{{-- Fallback статичный Hero блок --}}
<div class="hero-container flex flex-col md:flex-row gap-4">
    <!-- Левая часть -->
    <div
        class="flex w-full flex-col justify-between px-4 pt-4 pb-8 text-center sm:text-left md:w-auto md:basis-1/2 lg:basis-2/3">
        <!-- Контент -->
        <div>
            <!-- Заголовок -->
            <h1 class="mb-8">
                Загружу работой ваших менеджеров по продажам<br>SEO продвижение сайтов в интернете.
            </h1>
        </div>

        <!-- Форма -->
        @include('partials.hero-form', [
            'buttonText' => 'Отправить заявку',
            'buttonAriaLabel' => 'Отправить заявку',
            'idSuffix' => '',
        ])
    </div>

    <!-- Правая часть (картинка) -->
    <div
        class="hero-image-container flex w-full items-center justify-center overflow-hidden px-4 py-6 md:w-auto md:basis-1/2 lg:basis-1/3">
        <img src="{{ asset('images/human.webp') }}" alt="right photo"
            class="h-auto max-h-64 w-full object-contain sm:max-h-80 lg:max-h-96" loading="eager" decoding="async"
            fetchpriority="high" />
    </div>
</div>
