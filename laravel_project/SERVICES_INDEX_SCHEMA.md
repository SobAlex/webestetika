# Схема страницы списка услуг (/services)

## Общая архитектура

```
Route (GET /services)
  ↓
ServiceController::index()
  ↓
Service Model (Eloquent Query)
  ↓
View (services/index.blade.php)
  ↓
Частичные представления (partials)
```

---

## 1. Роутинг

**Файл:** `routes/web.php`

```php
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{service:slug}', [ServiceController::class, 'show'])->name('show');
});
```

**Маршрут:** `GET /services`
**Имя маршрута:** `services.index`
**Контроллер:** `ServiceController@index`

---

## 2. Контроллер

**Файл:** `app/Http/Controllers/ServiceController.php`

### Метод `index()`

```php
public function index()
{
    $services = Service::published()->ordered()->get();

    return view('services.index', [
        'title' => 'Наши услуги',
        'services' => $services
    ]);
}
```

**Особенности:**
- Не использует сервисы (в отличие от главной страницы)
- Прямой запрос к модели через Eloquent
- Получает все опубликованные услуги, отсортированные по `sort_order`

**Переменные в view:**
- `$title` - "Наши услуги"
- `$services` - `Collection<Service>`

---

## 3. Модель Service

**Файл:** `app/Models/Service.php`

### Traits

1. **HasFactory** - для фабрик и сидеров
2. **HasPublishing** - для публикации
3. **HasSlug** - для автоматической генерации slug

### Scopes (из HasPublishing и собственные)

| Scope | Описание | Условие |
|-------|----------|---------|
| `published()` | Только опубликованные | `where('is_published', true)` |
| `ordered()` | Сортировка | `orderBy('sort_order')->orderBy('title')` |
| `featured()` | Рекомендуемые | `where('is_featured', true)` |
| `showOnHomepage()` | Показывать на главной | `where('show_on_homepage', true)` |

### Основные поля модели

**Базовые:**
- `id` - первичный ключ
- `title` - название услуги
- `slug` - URL-friendly название (уникальное)
- `description` - краткое описание
- `content` - полное описание (HTML)

**Визуальные:**
- `icon` - иконка Material Icons
- `color` - цвет в HEX формате
- `image` - путь к изображению

**Цена:**
- `price_from` - цена от (decimal:2)
- `price_type` - тип цены: `hour`, `month`, `project` или `null`

**Особенности:**
- `features` - массив особенностей (JSON cast в array)

**SEO:**
- `meta_title` - SEO заголовок
- `meta_description` - SEO описание
- `meta_keywords` - SEO ключевые слова

**Статусы:**
- `is_published` - опубликована ли услуга (boolean)
- `is_featured` - рекомендуемая услуга (boolean)
- `show_on_homepage` - показывать на главной (boolean)
- `sort_order` - порядок сортировки (integer)

**Связанный контент:**
- `related_service_1_id`, `related_service_2_id`, `related_service_3_id`
- `related_article_1_id`, `related_article_2_id`, `related_article_3_id`
- `related_case_1_id`, `related_case_2_id`, `related_case_3_id`

### Accessors (вычисляемые свойства)

#### `formatted_price`
**Метод:** `getFormattedPriceAttribute()`

Форматирует цену в зависимости от типа:
- Если `price_from` пусто → "По договоренности"
- Формат: `number_format($price_from, 0, ',', ' ') . ' ₽'`
- Типы:
  - `hour` → "от {цена}/час"
  - `month` → "от {цена}/месяц"
  - `project` → "от {цена}"
  - `default` → "от {цена}"

**Примеры:**
- `50000` + `month` → "от 50 000 ₽/месяц"
- `100000` + `project` → "от 100 000 ₽"
- `null` → "По договоренности"

#### `url`
**Метод:** `getUrlAttribute()`

Возвращает URL страницы услуги:
```php
route('services.show', $this->slug)
```

#### `related_services`
**Метод:** `getRelatedServicesAttribute()`

Получает связанные услуги (до 3 шт):
- Собирает ID из `related_service_1_id`, `related_service_2_id`, `related_service_3_id`
- Фильтрует пустые значения
- Загружает только опубликованные услуги
- Сохраняет порядок из полей

#### `related_articles`
**Метод:** `getRelatedArticlesAttribute()`

Получает связанные статьи блога (до 3 шт):
- Аналогично `related_services`, но для `Blog` модели

#### `related_cases`
**Метод:** `getRelatedCasesAttribute()`

Получает связанные кейсы (до 3 шт):
- Аналогично `related_services`, но для `ProjectCase` модели

### Relations (связи)

Все связи через `BelongsTo`:

**Услуги:**
- `relatedService1()`, `relatedService2()`, `relatedService3()`

**Статьи:**
- `relatedArticle1()`, `relatedArticle2()`, `relatedArticle3()`

**Кейсы:**
- `relatedCase1()`, `relatedCase2()`, `relatedCase3()`

### Route Key

**Метод:** `getRouteKeyName()`

Возвращает `'slug'` - используется для маршрутизации:
```php
Route::get('/{service:slug}', ...)
```

---

## 4. Представление (View)

**Файл:** `resources/views/services/index.blade.php`

### Структура страницы

#### 4.1 Breadcrumbs (Хлебные крошки)
```blade
@include('partials.breadcrumbs', [
    'breadcrumbs' => [['title' => 'Услуги', 'url' => null]],
])
```

**Файл:** `resources/views/partials/breadcrumbs.blade.php`

**Структура:**
- Главная (всегда первая) → ссылка на `route('home')`
- Услуги (текущая страница) → не ссылка, только текст

**Особенности:**
- JSON-LD структурированные данные для SEO
- Schema.org разметка

---

#### 4.2 Hero Section
```blade
<section class="section-bg">
    <div class="text-center mb-12">
        <h1 class="page-title">{{ $title }}</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Профессиональные услуги по продвижению и развитию вашего бизнеса в интернете
        </p>
    </div>
    ...
</section>
```

**Элементы:**
- Заголовок H1: "Наши услуги"
- Подзаголовок (статичный текст)

---

#### 4.3 Services Grid (Сетка услуг)

**Структура:**
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($services as $service)
        <!-- Карточка услуги -->
    @empty
        <!-- Пустое состояние -->
    @endforelse
</div>
```

**Адаптивность:**
- Мобильные: 1 колонка
- Планшеты (md): 2 колонки
- Десктоп (lg): 3 колонки

##### Карточка услуги

**Структура карточки:**

1. **Изображение услуги** (если есть)
   ```blade
   @if($service->image)
       <div class="aspect-video bg-gray-100 overflow-hidden rounded-t-lg">
           <img src="{{ asset('storage/' . $service->image) }}"
                alt="{{ $service->title }}"
                class="w-full h-full object-cover">
       </div>
   @endif
   ```
   - Пропорции: 16:9 (aspect-video)
   - Путь: `storage/{$service->image}`

2. **Контент карточки** (`<div class="p-6">`)

   a. **Иконка и заголовок**
   ```blade
   <div class="flex items-start mb-4">
       @if($service->icon)
           <div class="flex-shrink-0 mr-4">
               <div class="w-12 h-12 rounded-md flex items-center justify-center"
                    style="background-color: {{ $service->color }}20; border: 1px solid {{ $service->color }}40;">
                   <i class="material-icons text-2xl" style="color: {{ $service->color }}">
                       {{ $service->icon }}
                   </i>
               </div>
           </div>
       @endif
       <div class="flex-1">
           <a href="{{ route('services.show', $service->slug) }}" class="group">
               <h3 class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-cyan-500 transition-colors">
                   {{ $service->title }}
               </h3>
           </a>
           @if($service->is_featured)
               <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-md">
                   Рекомендуемая
               </span>
           @endif
       </div>
   </div>
   ```
   - Иконка с цветным фоном (20% прозрачность) и рамкой (40% прозрачность)
   - Заголовок - ссылка на страницу услуги
   - Бейдж "Рекомендуемая" если `is_featured = true`

   b. **Описание**
   ```blade
   <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 120) }}</p>
   ```
   - Ограничено 120 символами

   c. **Особенности (Features)**
   ```blade
   @if($service->features && is_array($service->features) && count($service->features) > 0)
       <ul class="text-sm text-gray-500 mb-4 space-y-1">
           @foreach(array_slice($service->features, 0, 3) as $feature)
               @if(is_string($feature))
                   <li class="flex items-center">
                       <i class="material-icons text-cyan-500 text-sm mr-2">check</i>
                       {{ $feature }}
                   </li>
               @endif
           @endforeach
       </ul>
   @endif
   ```
   - Показывает только первые 3 особенности
   - Каждая с иконкой галочки

   d. **Цена и кнопка "Заказать"**
   ```blade
   <div class="flex items-center justify-between mt-6 pt-4 border-t border-cyan-500">
       <div>
           @if($service->price_from)
               <div class="text-lg font-bold text-cyan-600">{{ $service->formatted_price }}</div>
           @else
               <div class="text-sm text-gray-500">По договоренности</div>
           @endif
       </div>
       <div>
           <button class="btn text-sm px-4 py-2" onclick="openServiceOrderModal('{{ $service->title }}')">
               Заказать
           </button>
       </div>
   </div>
   ```
   - Цена или "По договоренности"
   - Кнопка открывает модальное окно заказа

##### Пустое состояние

```blade
@empty
    <div class="col-span-full text-center py-12">
        <div class="text-gray-400 mb-4">
            <i class="material-icons text-6xl">business_center</i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Услуги временно недоступны</h3>
        <p class="text-gray-500">Мы работаем над добавлением новых услуг</p>
    </div>
@endforelse
```

---

#### 4.4 CTA Section (Призыв к действию)

```blade
@if($services->count() > 0)
    <section class="section-bg">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-8 text-center rounded-md">
            <h2 class="text-3xl font-bold mb-4">Готовы начать продвижение вашего сайта?</h2>
            <p class="text-xl mb-6 opacity-90">Свяжитесь с нами для обсуждения вашего проекта</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                    onclick="openServiceOrderModal('Заказать продвижение')">
                    Заказать продвижение
                </button>
                <button class="bg-white text-cyan-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition"
                    onclick="window.dispatchEvent(new CustomEvent('open-callback'))">
                    Заказать звонок
                </button>
            </div>
        </div>
    </section>
@endif
```

**Условие:** Показывается только если есть услуги

**Элементы:**
- Градиентный фон (cyan-500 → cyan-600)
- Заголовок и описание
- Две кнопки:
  - "Заказать продвижение" → открывает модальное окно заказа услуги
  - "Заказать звонок" → открывает модальное окно обратного звонка (через CustomEvent)

---

## 5. Модальные окна

### 5.1 Service Order Modal (Заказ услуги)

**Файл:** `resources/views/partials/modals.blade.php`

**ID:** `serviceOrderModal`

**Функция открытия:** `openServiceOrderModal(serviceName)`

**Файл JS:** `resources/js/modals.js`

```javascript
function openServiceOrderModal(serviceName) {
    console.log("Opening modal for service:", serviceName);
    document.getElementById("service_display").value = serviceName;
    document.getElementById("service_name_input").value = serviceName;
    document.getElementById("serviceOrderModal").style.display = "flex";
}
```

**Форма:**
- `action="{{ route('service.order') }}"`
- `method="POST"`
- Поля:
  - `service_name` (hidden) - название услуги
  - `name` (required) - имя
  - `email` (required) - email
  - `phone` (required) - телефон
  - `message` (optional) - сообщение
  - `attachment` (optional) - файл

**Обработчик:** `ContactController::submitServiceOrder()`

**Job:** `SendServiceOrderEmail`

---

## 6. Поток данных

```
1. Пользователь заходит на /services
   ↓
2. Route вызывает ServiceController::index()
   ↓
3. Контроллер выполняет запрос:
   Service::published()->ordered()->get()
   ↓
4. Eloquent запрос:
   - Фильтрует: is_published = true
   - Сортирует: sort_order ASC, затем title ASC
   - Получает все записи
   ↓
5. Данные передаются в view('services.index', [
       'title' => 'Наши услуги',
       'services' => $services
   ])
   ↓
6. Blade шаблон рендерит:
   - Breadcrumbs (хлебные крошки)
   - Hero section (заголовок и описание)
   - Services Grid (сетка услуг):
     * Для каждой услуги:
       - Изображение (если есть)
       - Иконка и заголовок
       - Бейдж "Рекомендуемая" (если is_featured)
       - Описание (120 символов)
       - Особенности (первые 3)
       - Цена и кнопка "Заказать"
     * Если услуг нет → пустое состояние
   - CTA Section (если есть услуги)
   ↓
7. Пользователь видит готовую страницу
```

---

## 7. Особенности реализации

### 7.1 Отсутствие сервисного слоя

В отличие от главной страницы, здесь **не используется сервисный слой**:
- Прямой запрос к модели через Eloquent
- Простая логика не требует отдельного сервиса

### 7.2 Сортировка

**Метод:** `ordered()`

```php
public function scopeOrdered($query)
{
    return $query->orderBy('sort_order')->orderBy('title');
}
```

**Порядок:**
1. По `sort_order` (ASC)
2. По `title` (ASC) - если `sort_order` одинаковый

### 7.3 Форматирование цены

**Accessor:** `formatted_price`

Логика форматирования:
- Если цена не указана → "По договоренности"
- Форматирование числа: пробелы как разделители тысяч
- Добавление типа цены (час/месяц/проект)

### 7.4 Особенности (Features)

**Тип:** JSON → Array (cast)

**Ограничения в шаблоне:**
- Показываются только первые 3 особенности
- Только строковые значения (проверка `is_string()`)

### 7.5 Модальное окно заказа

**Интеграция:**
- JavaScript функция `openServiceOrderModal()` определена в `resources/js/modals.js`
- Доступна глобально через `window.openServiceOrderModal`
- Автоматически заполняет поле `service_name` в форме

### 7.6 Адаптивный дизайн

**Grid система:**
- Tailwind CSS responsive classes
- Breakpoints:
  - `sm`: 640px
  - `md`: 768px
  - `lg`: 1024px

---

## 8. Зависимости

```
ServiceController
  └── Service Model
      ├── HasPublishing trait
      │   ├── scopePublished()
      │   ├── scopeOrdered()
      │   └── scopeShowOnHomepage()
      ├── HasSlug trait
      │   └── auto-generate slug
      └── Accessors
          ├── formatted_price
          ├── url
          ├── related_services
          ├── related_articles
          └── related_cases
```

---

## 9. Файловая структура

```
app/
├── Http/
│   └── Controllers/
│       └── ServiceController.php
└── Models/
    └── Service.php

app/Traits/
├── HasPublishing.php
└── HasSlug.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── services/
│   │   └── index.blade.php
│   └── partials/
│       ├── breadcrumbs.blade.php
│       └── modals.blade.php
└── js/
    └── modals.js

routes/
└── web.php
```

---

## 10. Сравнение с главной страницей

| Аспект | Главная страница | Страница услуг |
|--------|------------------|----------------|
| **Контроллер** | HomeController | ServiceController |
| **Сервисы** | 8 сервисов | Нет (прямой запрос) |
| **Данные** | Множество разных типов | Только услуги |
| **Сложность** | Высокая | Низкая |
| **Сортировка** | Разная для каждого типа | Единая для всех услуг |
| **Ограничение** | Лимиты (take(4), take(6)) | Все записи |
| **Трансформация** | CaseService трансформирует данные | Нет трансформации |

---

## 11. Резюме

**Страница списка услуг (`/services`) состоит из:**

1. **Breadcrumbs** - навигационная цепочка
2. **Hero Section** - заголовок и описание страницы
3. **Services Grid** - адаптивная сетка карточек услуг:
   - Изображение (опционально)
   - Иконка и заголовок
   - Бейдж "Рекомендуемая"
   - Описание (120 символов)
   - Особенности (первые 3)
   - Цена и кнопка "Заказать"
4. **Пустое состояние** - если услуг нет
5. **CTA Section** - призыв к действию (если есть услуги)

**Особенности:**
- Простая архитектура без сервисного слоя
- Прямой запрос к модели через Eloquent
- Адаптивный дизайн (Tailwind CSS)
- Интеграция с модальными окнами для заказа
- Автоматическое форматирование цены
- SEO-friendly структура (breadcrumbs с JSON-LD)






