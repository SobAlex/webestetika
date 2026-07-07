# Медиа библиотека - Руководство по использованию

## Обзор

Медиа библиотека позволяет загружать, управлять и использовать файлы (изображения, PDF, текстовые файлы) в вашем Laravel приложении. Система интегрирована с Filament админ панелью и предоставляет удобный интерфейс для управления медиа файлами.

## Возможности

- ✅ Загрузка изображений, PDF и текстовых файлов
- ✅ Автоматическое создание уникальных имен файлов
- ✅ Предпросмотр изображений и файлов
- ✅ Копирование URL и HTML кода
- ✅ Поиск и фильтрация файлов
- ✅ SEO-оптимизация (alt текст, описания)
- ✅ Статистика использования
- ✅ API для программного доступа

## Доступ к медиа библиотеке

### 1. Админ панель Filament
- URL: `http://localhost:8000/admin/media`
- Полнофункциональный интерфейс для управления файлами
- Загрузка, редактирование, удаление файлов
- Просмотр статистики

### 2. Веб-интерфейс медиа библиотеки
- URL: `http://localhost:8000/admin/media`
- Упрощенный интерфейс для быстрого доступа
- Поиск и фильтрация
- Копирование URL и HTML кода

### 3. Демонстрация использования
- URL: `http://localhost:8000/demo/media`
- Примеры использования в HTML, CSS, PHP и JavaScript
- Галерея загруженных изображений

## Загрузка файлов

### Через админ панель
1. Перейдите в раздел "Медиа библиотека"
2. Нажмите "Загрузить файл"
3. Выберите файл (поддерживаются: изображения, PDF, текстовые файлы)
4. Заполните alt текст и описание (опционально)
5. Нажмите "Сохранить"

### Через API
```php
// Загрузка файла через API
$response = Http::attach('file', $file)
    ->post('/api/media', [
        'alt_text' => 'Описание изображения',
        'description' => 'Дополнительное описание'
    ]);
```

## Использование файлов в коде

### HTML
```html
<!-- Простое изображение -->
<img src="{{ $media->url }}" alt="{{ $media->alt_text }}">

<!-- С CSS классами -->
<img src="{{ $media->url }}"
     alt="{{ $media->alt_text }}"
     class="w-full h-auto rounded-lg">

<!-- Фоновое изображение -->
<div style="background-image: url('{{ $media->url }}');"></div>
```

### PHP (Laravel Blade)
```php
// В контроллере
$images = Media::where('mime_type', 'like', 'image/%')->get();

// В Blade шаблоне
@foreach($images as $image)
    <img src="{{ $image->url }}" alt="{{ $image->alt_text }}">
@endforeach

// С проверкой существования
@if($image->exists())
    <img src="{{ $image->url }}" alt="{{ $image->alt_text }}">
@endif
```

### CSS
```css
.hero-section {
    background-image: url('/storage/media/2024/01/image.jpg');
    background-size: cover;
    background-position: center;
}
```

### JavaScript
```javascript
// Динамическое добавление изображения
const img = document.createElement('img');
img.src = '{{ $media->url }}';
img.alt = '{{ $media->alt_text }}';
document.body.appendChild(img);

// Загрузка через fetch
fetch('{{ $media->url }}')
    .then(response => response.blob())
    .then(blob => {
        const url = URL.createObjectURL(blob);
        // Используйте URL
    });
```

## API Endpoints

### Получить все файлы
```http
GET /api/media
```

### Получить файл по ID
```http
GET /api/media/{id}
```

### Загрузить файл
```http
POST /api/media
Content-Type: multipart/form-data

file: [файл]
alt_text: "Описание изображения"
description: "Дополнительное описание"
```

### Обновить информацию о файле
```http
PUT /api/media/{id}
Content-Type: application/json

{
    "alt_text": "Новое описание",
    "description": "Новое дополнительное описание"
}
```

### Удалить файл
```http
DELETE /api/media/{id}
```

### Получить статистику
```http
GET /api/media/stats
```

## Модель Media

### Основные поля
- `filename` - имя файла на сервере
- `original_name` - оригинальное имя файла
- `mime_type` - MIME тип файла
- `size` - размер файла в байтах
- `path` - путь к файлу в storage
- `url` - URL для доступа к файлу
- `alt_text` - альтернативный текст для изображений
- `description` - описание файла

### Полезные методы
```php
$media = Media::find(1);

// Получить URL файла
$url = $media->url;

// Проверить существование файла
$exists = $media->exists();

// Проверить, является ли файл изображением
$isImage = $media->isImage();

// Получить расширение файла
$extension = $media->extension;

// Получить отформатированный размер
$formattedSize = $media->formatted_size;
```

## Сервис MediaService

### Основные методы
```php
use App\Services\MediaService;

$mediaService = app(MediaService::class);

// Загрузить файл
$media = $mediaService->uploadFile($file, $altText, $description);

// Удалить файл
$mediaService->deleteFile($media);

// Получить все изображения
$images = $mediaService->getImages();

// Поиск файлов
$files = $mediaService->searchFiles('поисковый запрос');

// Получить статистику
$stats = $mediaService->getStats();
```

## Настройка

### Конфигурация файлов
Файлы сохраняются в `storage/app/public/media/` с автоматической организацией по датам:
- `media/2024/01/filename.jpg`
- `media/2024/02/filename.pdf`

### Ограничения
- Максимальный размер файла: 10MB
- Поддерживаемые типы: изображения, PDF, текстовые файлы
- Автоматическое создание уникальных имен файлов

### Безопасность
- CSRF защита для всех форм
- Валидация типов файлов
- Проверка размера файлов
- Безопасные имена файлов

## Troubleshooting

### Проблема: Изображения не отображаются
**Решение:** Убедитесь, что создан симлинк для storage:
```bash
php artisan storage:link
```

### Проблема: Ошибка 413 при загрузке
**Решение:** Увеличьте лимит размера файла в PHP:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Проблема: Файлы не сохраняются
**Решение:** Проверьте права доступа к папке storage:
```bash
chmod -R 775 storage
chown -R www-data:www-data storage
```

## Дополнительные возможности

### Интеграция с TinyMCE
```javascript
// Настройка TinyMCE для использования медиа библиотеки
tinymce.init({
    selector: 'textarea',
    plugins: 'image',
    image_upload_url: '/api/media',
    // ... другие настройки
});
```

### Интеграция с CKEditor
```javascript
// Настройка CKEditor
ClassicEditor.create(document.querySelector('#editor'), {
    simpleUpload: {
        uploadUrl: '/api/media',
        // ... другие настройки
    }
});
```

## Заключение

Медиа библиотека предоставляет полный набор инструментов для управления файлами в вашем Laravel приложении. Используйте админ панель для управления файлами, API для программного доступа и веб-интерфейс для быстрого доступа к файлам.

Для получения дополнительной помощи обратитесь к документации Filament или создайте issue в репозитории проекта.
