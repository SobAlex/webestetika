<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Медиа библиотека</title>
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
                        <h1 class="text-2xl font-bold text-gray-900">Медиа библиотека</h1>
                        <p class="text-sm text-gray-600">Управление файлами и изображениями</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="/admin/media" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="material-icons mr-2">admin_panel_settings</i>
                            Админ панель
                        </a>
                        <button onclick="uploadFile()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="material-icons mr-2">upload</i>
                            Загрузить файл
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Stats -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="material-icons text-blue-600">folder</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Всего файлов</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_files'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="material-icons text-green-600">image</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Изображения</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['image_count'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <i class="material-icons text-red-600">picture_as_pdf</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">PDF файлы</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pdf_count'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="material-icons text-purple-600">storage</i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Общий размер</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['formatted_size'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Поиск по имени файла, alt тексту или описанию..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип файла</label>
                        <select name="type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Все типы</option>
                            <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Изображения</option>
                            <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>PDF файлы</option>
                            <option value="text" {{ request('type') === 'text' ? 'selected' : '' }}>Текстовые файлы</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="material-icons mr-2">search</i>
                        Поиск
                    </button>

                    @if(request('search') || request('type'))
                        <a href="{{ route('media.library') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="material-icons mr-2">clear</i>
                            Сбросить
                        </a>
                    @endif
                </form>
            </div>

            <!-- Media Grid -->
            <div class="bg-white rounded-lg shadow-sm">
                @if($media->count() > 0)
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Файлы ({{ $media->total() }})</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 p-4">
                        @foreach($media as $file)
                            <div class="rounded-lg shadow-sm overflow-hidden transition-shadow">
                                <!-- Preview -->
                                <div class="aspect-square bg-gray-100 flex items-center justify-center">
                                    @if(str_starts_with($file->mime_type, 'image/'))
                                        <img src="{{ $file->url }}" alt="{{ $file->alt_text ?: $file->original_name }}"
                                             class="w-full h-full object-cover">
                                    @elseif($file->mime_type === 'application/pdf')
                                        <div class="text-center text-red-600">
                                            <i class="material-icons text-4xl">picture_as_pdf</i>
                                            <p class="text-sm font-medium">PDF</p>
                                        </div>
                                    @else
                                        <div class="text-center text-gray-600">
                                            <i class="material-icons text-4xl">insert_drive_file</i>
                                            <p class="text-sm font-medium">{{ $file->mime_type }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="p-3">
                                    <h3 class="font-medium text-gray-900 text-sm truncate" title="{{ $file->original_name }}">
                                        {{ $file->original_name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $file->formatted_size }}</p>
                                    <p class="text-xs text-gray-400">{{ $file->created_at->format('d.m.Y H:i') }}</p>

                                    <!-- Actions -->
                                    <div class="flex space-x-1 mt-2">
                                        <button onclick="copyUrl('{{ $file->url }}')"
                                                class="flex-1 bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded hover:bg-blue-200 transition-colors"
                                                title="Копировать URL">
                                            <i class="material-icons text-sm">link</i>
                                        </button>

                                        @if(str_starts_with($file->mime_type, 'image/'))
                                            <button onclick="copyHtml('{{ $file->url }}', '{{ $file->alt_text ?: '' }}')"
                                                    class="flex-1 bg-green-100 text-green-600 text-xs px-2 py-1 rounded hover:bg-green-200 transition-colors"
                                                    title="Копировать HTML">
                                                <i class="material-icons text-sm">code</i>
                                            </button>
                                        @endif

                                        <a href="{{ $file->url }}" target="_blank"
                                           class="flex-1 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded hover:bg-gray-200 transition-colors text-center"
                                           title="Открыть файл">
                                            <i class="material-icons text-sm">open_in_new</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 py-3 border-t">
                        {{ $media->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="material-icons text-6xl text-gray-400 mb-4">folder_open</i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Файлы не найдены</h3>
                        <p class="text-gray-500 mb-4">
                            @if(request('search') || request('type'))
                                Попробуйте изменить параметры поиска
                            @else
                                Загрузите первый файл в медиа библиотеку
                            @endif
                        </p>
                        <button onclick="uploadFile()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="material-icons mr-2">upload</i>
                            Загрузить файл
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold">Загрузка файла</h3>
                </div>
                <form id="uploadForm" class="p-4" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Выберите файл</label>
                        <input type="file" name="file" id="fileInput"
                               accept="image/*,application/pdf,text/*"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt текст (для изображений)</label>
                        <input type="text" name="alt_text"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeUploadModal()"
                                class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Загрузить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function uploadFile() {
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadForm').reset();
        }

        function copyUrl(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert('URL скопирован в буфер обмена!');
            });
        }

        function copyHtml(url, alt) {
            const html = `<img src="${url}" alt="${alt}">`;
            navigator.clipboard.writeText(html).then(function() {
                alert('HTML код скопирован в буфер обмена!');
            });
        }

        // Upload form handling
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/api/media/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Файл успешно загружен!');
                    location.reload();
                } else {
                    alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при загрузке файла: ' + error.message);
            });
        });

        // Close modal on outside click
        document.getElementById('uploadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
            }
        });
    </script>
</body>
</html>
