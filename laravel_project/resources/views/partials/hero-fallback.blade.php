{{-- Fallback статичный Hero блок --}}
<div class="hero-container flex flex-col md:flex-row gap-4">
    <!-- Левая часть -->
    <div class="w-full md:basis-1/2 lg:basis-2/3 md:w-auto flex flex-col justify-between px-4 pt-4 pb-16 text-center sm:text-left">
        <!-- Контент -->
        <div>
            <!-- Заголовок -->
            <h1 class="text-3xl leading-relaxed mb-8">
                Загружу работой ваших менеджеров по продажам<br>SEO продвижение сайтов в интернете.
            </h1>
        </div>

        <!-- Форма -->
        @include('partials.hero-form', [
            'buttonText' => 'Отправить заявку',
            'buttonAriaLabel' => 'Отправить заявку',
            'idSuffix' => ''
        ])
    </div>

    <!-- Правая часть (картинка) -->
    <div class="w-full md:basis-1/2 lg:basis-1/3 md:w-auto hero-image-container px-4 py-6 overflow-hidden flex items-center justify-center">
        <img src="{{ asset('images/human.webp') }}" alt="right photo"
            loading="eager" decoding="async" fetchpriority="high" />
    </div>
</div>
