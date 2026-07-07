<!-- Модальное окно: Заказать звонок -->
<div x-data="{ callbackOpen: false }" @open-callback.window="callbackOpen = true" @keydown.escape.window="callbackOpen = false">
    <div x-show="callbackOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center"
        style="display: none;">
        <!-- Фон -->
        <div class="absolute inset-0 bg-black/50" aria-hidden="true" @click="callbackOpen = false"></div>

        <!-- Диалог -->
        <div class="relative bg-white w-full max-w-md mx-4 shadow-sm p-6" role="dialog" aria-modal="true"
            aria-labelledby="callback_title">
            <div class="flex items-start justify-between mb-4">
                <h3 id="callback_title" class="text-xl font-semibold">Заказать звонок</h3>
                <button class="text-gray-500 hover:text-gray-700 rounded-lg" aria-label="Закрыть" @click="callbackOpen = false">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form action="{{ route('contact.hero') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name_callback" class="block mb-1">Имя</label>
                    <input type="text" id="name_callback" name="name" required placeholder="Ваше имя"
                        class="w-full px-3 py-2 rounded-lg" aria-required="true" aria-label="Имя" />
                </div>

                <div>
                    <label for="phone_callback" class="block mb-1">Телефон</label>
                    <input type="tel" id="phone_callback" name="phone" required placeholder="+7 (999) 999-99-99"
                        class="w-full px-3 py-2 rounded-lg" aria-required="true" aria-label="Телефон" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" class="btn" @click="callbackOpen = false">Отмена</button>
                    <button type="submit" class="btn">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно: Заказ услуги -->
<div id="serviceOrderModal" class="fixed inset-0 z-50 flex items-start sm:items-center justify-center overflow-y-auto"
    style="display: none;">
    <!-- Фон -->
    <div class="absolute inset-0 bg-black/50" aria-hidden="true" onclick="closeServiceOrderModal()"></div>

    <!-- Диалог -->
    <div class="relative bg-white w-full max-w-lg mx-4 my-4 sm:my-8 shadow-sm p-4 sm:p-6" role="dialog"
        aria-modal="true" aria-labelledby="service_order_title">
        <!-- Заголовок с кнопкой закрытия - всегда видимый -->
        <div class="flex items-start justify-between mb-4 sticky top-0 bg-white pb-2 border-b border-gray-200">
            <h3 id="service_order_title" class="text-lg sm:text-xl font-semibold pr-2">Заказ услуги</h3>
            <button class="text-gray-500 hover:text-gray-700 flex-shrink-0 p-1 rounded-lg" aria-label="Закрыть"
                onclick="closeServiceOrderModal()">
                <span class="material-icons text-2xl">close</span>
            </button>
        </div>

        <!-- Прокручиваемая область формы -->
        <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
            <!-- Сообщение об успехе -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">
                    <p class="text-sm text-green-600">{{ session('status') }}</p>
                </div>
            @endif

            <form id="serviceOrderForm" action="{{ route('service.order') }}" method="POST" class="space-y-4"
                enctype="multipart/form-data">
                @csrf

                <!-- Скрытое поле с названием услуги -->
                <input type="hidden" name="service_name" id="service_name_input" required value="{{ old('service_name') }}">
                @error('service_name')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @enderror

                <div>
                    <label for="service_display" class="block mb-1 text-sm font-medium">Услуга</label>
                    <input type="text" id="service_display" readonly
                        class="w-full px-3 py-2 bg-gray-100 text-gray-600 rounded-lg border" />
                </div>

                <div>
                    <label for="name_service" class="block mb-1 text-sm font-medium">Имя *</label>
                    <input type="text" id="name_service" name="name" required placeholder="Ваше имя"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-required="true" aria-label="Имя"
                        value="{{ old('name') }}"
                        aria-invalid="@error('name') true @else false @enderror" />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email_service" class="block mb-1 text-sm font-medium">Email *</label>
                    <input type="email" id="email_service" name="email" required placeholder="your@email.com"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-required="true" aria-label="Email"
                        value="{{ old('email') }}"
                        aria-invalid="@error('email') true @else false @enderror" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_service" class="block mb-1 text-sm font-medium">Телефон *</label>
                    <input type="tel" id="phone_service" name="phone" required placeholder="+7 (999) 999-99-99"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-required="true" aria-label="Телефон"
                        value="{{ old('phone') }}"
                        aria-invalid="@error('phone') true @else false @enderror" />
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message_service" class="block mb-1 text-sm font-medium">Сообщение</label>
                    <textarea id="message_service" name="message" rows="3" placeholder="Опишите ваши требования или вопросы..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-label="Сообщение"
                        aria-invalid="@error('message') true @else false @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="attachment_service" class="block mb-1 text-sm font-medium">Прикрепить файл</label>
                    <input type="file" id="attachment_service" name="attachment"
                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-label="Прикрепить файл"
                        aria-invalid="@error('attachment') true @else false @enderror" />
                    @error('attachment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Поддерживаются: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF (до
                        10 МБ)</p>
                </div>
            </form>
        </div>

        <!-- Кнопки - всегда видимые внизу -->
        <div class="flex items-center justify-end gap-3 pt-4 mt-4 border-t border-gray-200 bg-white sticky bottom-0">
            <button type="button" class="btn" onclick="closeServiceOrderModal()">Отмена</button>
            <button type="submit" form="serviceOrderForm" class="btn">Заказать услугу</button>
        </div>
    </div>
</div>

<script>
// Автоматически открыть модальное окно заказа услуги при ошибках валидации
document.addEventListener('DOMContentLoaded', function() {
    @if ($errors->has('service_name') || $errors->has('name') || $errors->has('email') || $errors->has('phone') || $errors->has('message') || $errors->has('attachment'))
        // Есть ошибки валидации для формы заказа услуги - открыть модальное окно
        document.getElementById('serviceOrderModal').style.display = 'flex';

        // Восстановить значение названия услуги из old input
        @if (old('service_name'))
            document.getElementById('service_display').value = '{{ old('service_name') }}';
            document.getElementById('service_name_input').value = '{{ old('service_name') }}';
        @endif
    @endif

    @if (session('status') && request()->route()->getName() === 'service.order')
        // Показать сообщение об успехе в модальном окне
        document.getElementById('serviceOrderModal').style.display = 'flex';

        // Автоматически закрыть через 3 секунды
        setTimeout(function() {
            closeServiceOrderModal();
        }, 3000);
    @endif
});
</script>
