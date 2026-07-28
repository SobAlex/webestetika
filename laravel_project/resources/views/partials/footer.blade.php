{{-- Footer --}}
<footer class="mt-16 bg-gray-800">
    <div class="max-w-7xl mx-auto px-6">
        <div class="py-10 grid grid-cols-1 gap-10 sm:grid-cols-2 md:grid-cols-4">
            {{-- Brand / About --}}
            <div>
                <div>
                    <a href="{{ route('home') }}"
                        class="font-train text-4xl whitespace-nowrap text-white hover:text-cyan-500 transition">
                        SobAlex
                    </a>
                    <p class="mt-2 text-white">Оптимизация сайтов и SEO продвижение.</p>
                </div>

                <div class="mt-5">
                    <p class="footer-heading">Контакты</p>
                    <ul class="mt-4 space-y-3">
                        <li class="flex items-start space-x-3 text-cyan-500">
                            <i class="material-icons mt-0.5">place</i>
                            <span>{{ $contactInfo['address'] }}</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="material-icons mt-0.5 text-cyan-500">call</i>
                            <a href="tel:{{ str_replace(' ', '', $contactInfo['phone']) }}"
                                class="hover:text-cyan-400 transition">{{ $contactInfo['phone'] }}</a>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="material-icons mt-0.5 text-cyan-500">mail</i>
                            <a href="mailto:{{ $contactInfo['email'] }}"
                                class="hover:text-cyan-400 transition">{{ $contactInfo['email'] }}</a>
                        </li>
                    </ul>
                </div>

                <div class="mt-5 flex items-center space-x-4">
                    @if ($contactInfo['social']['telegram'])
                        <a href="{{ $contactInfo['social']['telegram'] }}" aria-label="Telegram"
                            class="hover:text-cyan-400 transition" target="_blank" rel="noopener">
                            <i class="material-icons">send</i>
                        </a>
                    @endif
                    @if ($contactInfo['social']['whatsapp'])
                        <a href="{{ $contactInfo['social']['whatsapp'] }}" aria-label="WhatsApp"
                            class="hover:text-cyan-400 transition" target="_blank" rel="noopener">
                            <i class="material-icons">chat</i>
                        </a>
                    @endif
                    @if ($contactInfo['social']['vk'])
                        <a href="{{ $contactInfo['social']['vk'] }}" aria-label="ВКонтакте"
                            class="hover:text-cyan-400 transition" target="_blank" rel="noopener">
                            <i class="material-icons">group</i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Services --}}
            <div>
                <p class="footer-heading">Услуги</p>
                <ul class="mt-4 space-y-2">
                    @php
                        $footerServices = \App\Models\Service::published()->showOnHomepage()->ordered()->take(6)->get();
                    @endphp
                    @forelse($footerServices as $service)
                        <li>
                            <a href="{{ route('services.show', $service->slug) }}"
                                class="hover:text-cyan-400 transition">
                                {{ $service->title }}
                            </a>
                        </li>
                    @empty
                        <li><span>Услуги загружаются...</span></li>
                    @endforelse
                    <li class="pt-2 border-t border-gray-700">
                        <a href="{{ route('services.index') }}" class="hover:text-cyan-400 transition">
                            Все услуги →
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Cases --}}
            <div>
                <p class="footer-heading">Кейсы</p>
                <ul class="mt-4 space-y-2">
                    @foreach ($activeCaseCategories as $category)
                        <li><a href="{{ route($category['route'], ...$category['route_params']) }}"
                                class="hover:text-cyan-400 transition">{{ $category['name'] }}</a></li>
                    @endforeach
                    <li class="pt-2 border-t border-gray-700">
                        <a href="{{ route('cases.index') }}" class="hover:text-cyan-400 transition">Все кейсы →</a>
                    </li>
                </ul>
            </div>

            {{-- Blog --}}
            <div>
                <p class="footer-heading">Блог</p>
                <ul class="mt-4 space-y-2">
                    @foreach ($activeBlogCategories as $category)
                        <li><a href="{{ route('blog.category', $category['slug']) }}"
                                class="hover:text-cyan-400 transition">{{ $category['name'] }}</a></li>
                    @endforeach
                    <li class="pt-2 border-t border-gray-700">
                        <a href="{{ route('blog.index') }}" class="hover:text-cyan-400 transition">Все статьи →</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="py-4 border-t border-gray-700 flex flex-col sm:flex-row items-center justify-between">
            <p class="text-white mb-2 sm:mb-0">© {{ date('Y') }} SobAlex. Все права защищены.</p>
            <div class="flex items-center space-x-4">
                <a href="{{ route('policy') }}" class="text-white">Политика конфиденциальности</a>
                <span>|</span>
                <a href="{{ route('terms') }}" class="hover:underline text-white">Пользовательское соглашение</a>
            </div>
        </div>
    </div>
</footer>
