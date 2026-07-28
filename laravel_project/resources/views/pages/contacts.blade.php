@extends('layouts.app')

@section('title', $title . ' - SEO продвижение сайтов')

@section('content')
    <div class="pt-8">
        @include('partials.breadcrumbs', ['breadcrumbs' => [['title' => 'Контакты', 'url' => null]]])
    </div>

    <section class="section-bg">
        <div class="text-center">
            <h1>{{ $title }}</h1>
            <p class="section-subtitle">
                Свяжитесь с нами для получения консультации по SEO продвижению вашего сайта.
                Мы всегда готовы ответить на ваши вопросы и предложить оптимальное решение.
            </p>
        </div>
    </section>

    <section class="section-bg" itemscope itemtype="https://schema.org/Organization">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="section-title">Наши контакты</h2>
                <div class="space-y-8">
                    <!-- Адрес -->
                    <div class="flex items-start space-x-4" itemprop="address" itemscope
                        itemtype="https://schema.org/PostalAddress">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600 flex-shrink-0 mt-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="contact-label">Адрес офиса</p>
                            <p itemprop="streetAddress">{{ $contactInfo['address'] }}</p>
                        </div>
                    </div>

                    <!-- Телефон -->
                    <div class="flex items-start space-x-4" itemprop="contactPoint" itemscope
                        itemtype="https://schema.org/ContactPoint">
                        <meta itemprop="contactType" content="customer service">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600 flex-shrink-0 mt-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <div>
                            <p class="contact-label">Телефон</p>
                            <a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}"
                                itemprop="telephone">{{ $contactInfo['phone'] }}</a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600 flex-shrink-0 mt-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="contact-label">Email</p>
                            <a href="mailto:{{ $contactInfo['email'] }}" itemprop="email">{{ $contactInfo['email'] }}</a>
                        </div>
                    </div>

                    <!-- Режим работы -->
                    <div class="flex items-start space-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600 flex-shrink-0 mt-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div itemscope itemtype="https://schema.org/OpeningHoursSpecification">
                            <p class="contact-label">Режим работы</p>
                            <p itemprop="description">{{ $contactInfo['working_hours'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Социальные сети -->
                @if ($contactInfo['social']['telegram'] || $contactInfo['social']['whatsapp'] || $contactInfo['social']['vk'])
                    <div class="mt-12">
                        <p class="contact-label">Мы в социальных сетях</p>
                        <div class="flex space-x-4">
                            @if ($contactInfo['social']['telegram'])
                                <a href="{{ $contactInfo['social']['telegram'] }}" target="_blank" rel="noopener noreferrer"
                                    class="bg-gray-500 text-white p-3 hover:bg-gray-800 transition" itemprop="sameAs">
                                    @svg('uni-telegram-alt', 'h-6 w-6 text-white')
                                </a>
                            @endif
                            @if ($contactInfo['social']['whatsapp'])
                                <a href="{{ $contactInfo['social']['whatsapp'] }}" target="_blank" rel="noopener noreferrer"
                                    class="bg-gray-500 text-white p-3 hover:bg-gray-800 transition" itemprop="sameAs">
                                    @svg('uni-whatsapp-o', 'h-6 w-6 text-white')
                                </a>
                            @endif
                            @if ($contactInfo['social']['vk'])
                                <a href="{{ $contactInfo['social']['vk'] }}" target="_blank" rel="noopener noreferrer"
                                    class="bg-gray-500 text-white p-3 hover:bg-gray-800 transition" itemprop="sameAs">
                                    @svg('uni-vk-alt', 'h-6 w-6 text-white')
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Форма -->
            <div>
                <h2 class="section-title">Напишите нам</h2>
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name_contact" class="block mb-2">Имя *</label>
                            <input type="text" name="name" id="name_contact" required placeholder="Ваше имя"
                                class="form-control">
                        </div>
                        <div>
                            <label for="email_contact" class="block mb-2">Email *</label>
                            <input type="email" name="email" id="email_contact" required placeholder="your@email.com"
                                class="form-control">
                        </div>
                    </div>
                    <div>
                        <label for="phone_contact" class="block mb-2">Телефон *</label>
                        <input type="tel" name="phone" id="phone_contact" required
                            placeholder="+7 (999) 999-99-99" class="form-control">
                    </div>
                    <div>
                        <label for="message_contact" class="block mb-2">Сообщение *</label>
                        <textarea name="message" id="message_contact" rows="6" required class="form-control"
                            placeholder="Опишите ваш проект или задайте вопрос..."></textarea>
                    </div>
                    @include('partials._consent')
                    <button type="submit" class="w-full btn">Отправить сообщение</button>
                </form>
            </div>
        </div>
    </section>
@endsection
