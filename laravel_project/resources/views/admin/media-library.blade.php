<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Медиатека</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body>
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6">
            <div>
                <h1 class="mb-1 text-2xl font-semibold text-gray-900 sm:text-2xl md:text-2xl">Медиатека</h1>
                <p class="text-sm text-gray-500">Файлы можно скопировать и вставить в материал вручную.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ url('/admin') }}" class="btn-outline gap-2">
                    <i class="material-icons text-base leading-none">arrow_back</i>
                    Админ-панель
                </a>
                <button type="button" class="btn gap-2" data-open-upload>
                    <i class="material-icons text-base leading-none">upload</i>
                    Загрузить файл
                </button>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6">
        @if (session('status'))
            <p class="mb-4 border border-gray-200 bg-white px-4 py-3 text-gray-800">{{ session('status') }}</p>
        @endif
        @if (session('error'))
            <p class="mb-4 border border-red-200 bg-white px-4 py-3 text-red-700">{{ session('error') }}</p>
        @endif

        <section class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-500">Всего файлов</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['total_files'] }}</p>
            </div>
            <div class="border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-500">Изображения</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['image_count'] }}</p>
            </div>
            <div class="border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-500">PDF</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['pdf_count'] }}</p>
            </div>
            <div class="border border-gray-200 bg-white p-4">
                <p class="text-sm text-gray-500">Общий размер</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['formatted_size'] }}</p>
            </div>
        </section>

        <form method="GET" action="{{ route('media.library') }}" class="mb-6 flex flex-wrap items-end gap-3">
            <label class="w-full sm:max-w-sm">
                <span class="mb-1 block text-sm text-gray-600">Поиск</span>
                <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Имя или описание">
            </label>
            <label class="w-full sm:w-48">
                <span class="mb-1 block text-sm text-gray-600">Тип</span>
                <select name="type" class="form-control">
                    <option value="">Все</option>
                    <option value="image" @selected(request('type') === 'image')>Изображения</option>
                    <option value="pdf" @selected(request('type') === 'pdf')>PDF</option>
                </select>
            </label>
            <button type="submit" class="btn">Найти</button>
            @if (request()->filled('search') || request()->filled('type'))
                <a href="{{ route('media.library') }}" class="btn-outline">Сбросить</a>
            @endif
        </form>

        @if ($media->isNotEmpty())
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($media as $file)
                    <article class="min-w-0 border border-gray-200 bg-white">
                        @if ($file->isImage() && $file->exists())
                            <img src="{{ $file->url }}" alt="{{ $file->alt_text ?: $file->original_name }}" class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-40 items-center justify-center bg-gray-100 text-gray-400">
                                <i class="material-icons text-4xl">{{ $file->mime_type === 'application/pdf' ? 'picture_as_pdf' : 'insert_drive_file' }}</i>
                            </div>
                        @endif
                        <div class="p-4">
                            <h2 class="mb-1 truncate text-sm font-medium text-gray-900" title="{{ $file->original_name }}">{{ $file->original_name }}</h2>
                            <p class="text-xs text-gray-500">{{ $file->formatted_size }} · {{ $file->created_at->format('d.m.Y H:i') }}</p>
                            <div class="mt-3 flex items-center gap-2">
                                <button type="button" class="inline-flex h-9 w-9 items-center justify-center border border-gray-200 text-gray-700 hover:border-cyan-500 hover:text-cyan-600" data-copy-url="{{ $file->url }}" title="Копировать адрес">
                                    <i class="material-icons text-base leading-none">link</i>
                                </button>
                                @if ($file->isImage())
                                    <button type="button" class="inline-flex h-9 w-9 items-center justify-center border border-gray-200 text-gray-700 hover:border-cyan-500 hover:text-cyan-600" data-copy-html data-copy-url="{{ $file->url }}" data-copy-alt="{{ $file->alt_text }}" title="Копировать HTML">
                                        <i class="material-icons text-base leading-none">code</i>
                                    </button>
                                @endif
                                <a href="{{ $file->url }}" target="_blank" rel="noopener" class="inline-flex h-9 w-9 items-center justify-center border border-gray-200 text-gray-700 hover:border-cyan-500 hover:text-cyan-600" title="Открыть файл">
                                    <i class="material-icons text-base leading-none">open_in_new</i>
                                </a>
                                <form method="POST" action="{{ route('media.destroy', $file) }}" class="ml-auto" data-delete-form>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-9 w-9 items-center justify-center border border-gray-200 text-gray-700 hover:border-red-500 hover:text-red-600" title="Удалить">
                                        <i class="material-icons text-base leading-none">delete</i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6">{{ $media->links() }}</div>
        @else
            <div class="border border-gray-200 bg-white px-6 py-12 text-center">
                <i class="material-icons text-5xl text-gray-300">folder_open</i>
                <h2 class="mt-3 text-lg text-gray-900">Файлы не найдены</h2>
                <p class="text-gray-500">
                    @if (request()->filled('search') || request()->filled('type'))
                        Измените параметры поиска.
                    @else
                        Загрузите первый файл.
                    @endif
                </p>
                <button type="button" class="btn mt-4 gap-2" data-open-upload>
                    <i class="material-icons text-base leading-none">upload</i>
                    Загрузить файл
                </button>
            </div>
        @endif
    </main>

    <div id="uploadModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-md border border-gray-200 bg-white" data-modal-panel>
            <div class="border-b border-gray-200 px-4 py-3">
                <h2 class="mb-0 text-lg text-gray-900">Загрузка файла</h2>
            </div>
            <form id="uploadForm" class="p-4" enctype="multipart/form-data">
                @csrf
                <label class="mb-2 block">
                    <span class="mb-1 block text-sm text-gray-600">Файл</span>
                    <input type="file" name="file" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf" class="form-control" required>
                </label>
                <p class="mb-4 border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">Если файл больше 10 МБ, он не загружается.</p>
                <label class="mb-4 block">
                    <span class="mb-1 block text-sm text-gray-600">Подпись для изображения</span>
                    <input type="text" name="alt_text" maxlength="255" class="form-control">
                </label>
                <label class="mb-4 block">
                    <span class="mb-1 block text-sm text-gray-600">Описание</span>
                    <textarea name="description" rows="3" maxlength="1000" class="form-control"></textarea>
                </label>
                <p id="uploadError" class="mb-4 hidden text-sm text-red-700"></p>
                <p class="mb-4 text-sm text-gray-500">JPEG, PNG, WebP, GIF или PDF.</p>
                <div class="flex justify-end gap-2">
                    <button type="button" class="btn-outline" data-close-upload>Отмена</button>
                    <button type="submit" class="btn">Загрузить</button>
                </div>
            </form>
        </div>
    </div>

    <p id="pageNotice" class="fixed right-4 bottom-4 hidden border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800" aria-live="polite"></p>

    <script>
        const uploadModal = document.getElementById('uploadModal');
        const uploadForm = document.getElementById('uploadForm');
        const uploadError = document.getElementById('uploadError');
        const uploadInput = uploadForm.querySelector('input[name="file"]');
        const pageNotice = document.getElementById('pageNotice');
        const maxUploadBytes = 10 * 1024 * 1024;
        const sizeMessage = 'Файл больше 10 МБ и не загружается.';

        function showUploadError(message) {
            uploadError.textContent = message;
            uploadError.classList.remove('hidden');
        }

        function clearUploadError() {
            uploadError.textContent = '';
            uploadError.classList.add('hidden');
        }

        function openUpload() {
            clearUploadError();
            uploadModal.classList.remove('hidden');
            uploadModal.classList.add('flex');
        }

        function closeUpload() {
            uploadModal.classList.add('hidden');
            uploadModal.classList.remove('flex');
            uploadForm.reset();
        }

        function showNotice(message) {
            pageNotice.textContent = message;
            pageNotice.classList.remove('hidden');
            window.setTimeout(() => pageNotice.classList.add('hidden'), 2500);
        }

        function escapeAttr(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('"', '&quot;')
                .replaceAll('<', '&lt;');
        }

        document.querySelectorAll('[data-open-upload]').forEach((button) => {
            button.addEventListener('click', openUpload);
        });

        document.querySelector('[data-close-upload]').addEventListener('click', closeUpload);

        uploadModal.addEventListener('click', (event) => {
            if (event.target === uploadModal) {
                closeUpload();
            }
        });

        document.querySelectorAll('[data-copy-url]').forEach((button) => {
            if (button.hasAttribute('data-copy-html')) {
                return;
            }

            button.addEventListener('click', async () => {
                await navigator.clipboard.writeText(button.dataset.copyUrl);
                showNotice('Адрес скопирован');
            });
        });

        document.querySelectorAll('[data-copy-html]').forEach((button) => {
            button.addEventListener('click', async () => {
                const html = '<img src="' + escapeAttr(button.dataset.copyUrl) + '" alt="' + escapeAttr(button.dataset.copyAlt) + '">';
                await navigator.clipboard.writeText(html);
                showNotice('HTML скопирован');
            });
        });

        document.querySelectorAll('[data-delete-form]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!window.confirm('Удалить этот файл?')) {
                    event.preventDefault();
                }
            });
        });

        uploadInput.addEventListener('change', () => {
            const file = uploadInput.files[0];

            if (file && file.size > maxUploadBytes) {
                showUploadError(sizeMessage);
                return;
            }

            clearUploadError();
        });

        uploadForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearUploadError();

            const file = uploadInput.files[0];

            if (file && file.size > maxUploadBytes) {
                showUploadError(sizeMessage);
                return;
            }

            const response = await fetch('{{ route('media.upload') }}', {
                method: 'POST',
                body: new FormData(uploadForm),
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json().catch(() => ({}));

            if (response.ok && data.success) {
                window.location.reload();
                return;
            }

            if (response.status === 413) {
                showUploadError(data.message || sizeMessage);
                return;
            }

            showUploadError(data.errors?.file?.[0] || data.message || 'Не удалось загрузить файл');
        });
    </script>
</body>
</html>
