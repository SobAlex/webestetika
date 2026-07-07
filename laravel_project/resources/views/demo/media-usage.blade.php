<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Демонстрация медиа библиотеки</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Демонстрация медиа библиотеки</h1>
                        <p class="text-sm text-gray-600">Примеры использования загруженных изображений</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="/admin/media" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="material-icons mr-2">admin_panel_settings</i>
                            Админ панель
                        </a>
                        <a href="/admin/media" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="material-icons mr-2">folder</i>
                            Медиа библиотека
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-blue-900 mb-2">
                    <i class="material-icons mr-2">info</i>
                    Как использовать медиа библиотеку
                </h2>
                <div class="text-blue-800 space-y-2">
                    <p>1. Перейдите в <a href="/admin/media" class="underline font-medium">админ панель</a> для загрузки файлов</p>
                    <p>2. Загрузите изображения, PDF или текстовые файлы</p>
                    <p>3. Скопируйте URL файла и используйте его в HTML коде</p>
                    <p>4. Для изображений используйте кнопку "Копировать HTML" для получения готового кода</p>
                </div>
            </div>

            @if($images->count() > 0)
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-sm mb-8">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Галерея изображений</h2>
                        <p class="text-gray-600 mt-1">Примеры загруженных изображений из медиа библиотеки</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($images as $image)
                                <div class="rounded-lg shadow-sm overflow-hidden">
                                    <div class="aspect-video bg-gray-100">
                                        <img src="{{ $image->url }}"
                                             alt="{{ $image->alt_text ?: $image->original_name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-medium text-gray-900 mb-2">{{ $image->original_name }}</h3>
                                        @if($image->alt_text)
                                            <p class="text-sm text-gray-600 mb-2">{{ $image->alt_text }}</p>
                                        @endif
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ $image->formatted_size }}</span>
                                            <span>{{ $image->created_at->format('d.m.Y') }}</span>
                                        </div>

                                        <!-- Code Examples -->
                                        <div class="mt-3 space-y-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">URL:</label>
                                                <code class="block text-xs bg-gray-100 p-2 rounded break-all">{{ $image->url }}</code>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">HTML код:</label>
                                                <code class="block text-xs bg-gray-100 p-2 rounded break-all">
                                                    &lt;img src="{{ $image->url }}" alt="{{ $image->alt_text ?: '' }}"&gt;
                                                </code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Code Examples -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Примеры использования в коде</h2>
                        <p class="text-gray-600 mt-1">Как вставлять изображения в HTML, CSS и PHP</p>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- HTML Example -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">HTML</h3>
                            <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                                <pre><code>&lt;!-- Простое изображение --&gt;
&lt;img src="{{ $images->first()->url }}" alt="{{ $images->first()->alt_text ?: 'Описание изображения' }}"&gt;

&lt;!-- Изображение с классами CSS --&gt;
&lt;img src="{{ $images->first()->url }}"
     alt="{{ $images->first()->alt_text ?: 'Описание изображения' }}"
     class="w-full h-auto rounded-lg"&gt;

&lt;!-- Изображение с ссылкой --&gt;
&lt;a href="/some-page"&gt;
    &lt;img src="{{ $images->first()->url }}"
         alt="{{ $images->first()->alt_text ?: 'Описание изображения' }}"
         class="hover:opacity-80 transition-opacity"&gt;
&lt;/a&gt;</code></pre>
                            </div>
                        </div>

                        <!-- CSS Example -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">CSS (фоновое изображение)</h3>
                            <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                                <pre><code>.hero-section {
    background-image: url('{{ $images->first()->url }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}</code></pre>
                            </div>
                        </div>

                        <!-- PHP Example -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">PHP (Laravel Blade)</h3>
                            <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                                <pre><code>&lt;!-- В Blade шаблоне --&gt;
@foreach($images as $image)
    &lt;img src="{{ $image->url }}"
         alt="{{ $image->alt_text ?: $image->original_name }}"
         class="w-full h-auto"&gt;
@endforeach

&lt;!-- Или с проверкой существования --&gt;
@if($image->exists())
    &lt;img src="{{ $image->url }}" alt="{{ $image->alt_text }}"&gt;
@else
    &lt;p&gt;Изображение не найдено&lt;/p&gt;
@endif</code></pre>
                            </div>
                        </div>

                        <!-- JavaScript Example -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">JavaScript</h3>
                            <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                                <pre><code>// Динамическое добавление изображения
const img = document.createElement('img');
img.src = '{{ $images->first()->url }}';
img.alt = '{{ $images->first()->alt_text ?: "Описание изображения" }}';
img.className = 'w-full h-auto rounded-lg';
document.body.appendChild(img);

// Загрузка изображения через fetch
fetch('{{ $images->first()->url }}')
    .then(response => response.blob())
    .then(blob => {
        const url = URL.createObjectURL(blob);
        // Используйте URL для отображения изображения
    });</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Images -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="text-center py-12">
                        <i class="material-icons text-6xl text-gray-400 mb-4">image_not_supported</i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Изображения не найдены</h3>
                        <p class="text-gray-500 mb-4">Загрузите изображения в медиа библиотеку для демонстрации</p>
                        <a href="/admin/media" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="material-icons mr-2">upload</i>
                            Загрузить изображения
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
