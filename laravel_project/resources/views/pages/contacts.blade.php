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

    <section class="section-bg">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="section-title">Наши контакты</h2>
                <div class="space-y-8">
                    <!-- Адрес -->
                    <div class="flex items-start space-x-4">
                        <i class="material-icons mt-0.5 shrink-0 text-cyan-500">place</i>
                        <div>
                            <p class="contact-label">Адрес офиса</p>
                            <p>{{ $contactInfo['address'] }}</p>
                        </div>
                    </div>

                    <!-- Телефон -->
                    <div class="flex items-start space-x-4">
                        <i class="material-icons mt-0.5 shrink-0 text-cyan-500">call</i>
                        <div>
                            <p class="contact-label">Телефон</p>
                            <a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}">{{ $contactInfo['phone'] }}</a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4">
                        <i class="material-icons mt-0.5 shrink-0 text-cyan-500">mail</i>
                        <div>
                            <p class="contact-label">Email</p>
                            <a href="mailto:{{ $contactInfo['email'] }}">{{ $contactInfo['email'] }}</a>
                        </div>
                    </div>

                    <!-- Режим работы -->
                    <div class="flex items-start space-x-4">
                        <i class="material-icons mt-0.5 shrink-0 text-cyan-500">schedule</i>
                        <div>
                            <p class="contact-label">Режим работы</p>
                            <p>{{ $contactInfo['working_hours'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Социальные сети -->
                @if ($contactInfo['social']['telegram'] || $contactInfo['social']['whatsapp'] || $contactInfo['social']['vk'])
                    <div class="mt-12">
                        <p class="contact-label">Мы в социальных сетях</p>
                        <div class="flex items-center gap-4">
                            @if ($contactInfo['social']['telegram'])
                                <a href="{{ $contactInfo['social']['telegram'] }}" target="_blank" rel="noopener noreferrer"
                                    class="text-cyan-500 hover:text-cyan-600" aria-label="Telegram">
                                    <i class="material-icons">send</i>
                                </a>
                            @endif
                            @if ($contactInfo['social']['whatsapp'])
                                <a href="{{ $contactInfo['social']['whatsapp'] }}" target="_blank" rel="noopener noreferrer"
                                    class="text-cyan-500 hover:text-cyan-600" aria-label="WhatsApp">
                                    <i class="material-icons">chat</i>
                                </a>
                            @endif
                            @if ($contactInfo['social']['vk'])
                                <a href="{{ $contactInfo['social']['vk'] }}" target="_blank" rel="noopener noreferrer"
                                    class="text-cyan-500 hover:text-cyan-600" aria-label="ВКонтакте">
                                    <i class="material-icons">group</i>
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
