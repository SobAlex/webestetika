# Схема главной страницы

## Общая архитектура

```
Route (/)
  ↓
HomeController::index()
  ↓
Сервисы (8 сервисов)
  ↓
Модели (8 моделей)
  ↓
View (welcome.blade.php)
  ↓
Частичные представления (partials)
```

---

## 1. Роутинг

**Файл:** `routes/web.php`

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

**Маршрут:** `GET /`
**Имя маршрута:** `home`
**Контроллер:** `HomeController@index`

---

## 2. Контроллер

**Файл:** `app/Http/Controllers/HomeController.php`

### Зависимости (Dependency Injection)

Контроллер получает 8 сервисов через конструктор:

1. `HeroService` - управление Hero-секциями
2. `ServiceService` - управление услугами
3. `WhyUsService` - управление блоками "Почему мы"
4. `CaseService` - управление кейсами
5. `BlogService` - управление статьями блога
6. `FaqService` - управление FAQ
7. `ReviewService` - управление отзывами
8. `ContactService` - управление контактной информацией

### Метод `index()`

Собирает данные через сервисы и передает в представление:

```php
$activeHeroes = $this->heroService->getActiveHeroSections();
$featuredServices = $this->serviceService->getFeaturedServices();
$whyUsBlocks = $this->whyUsService->getActiveWhyUsBlocks();
$transformlatestCases = $this->caseService->getLatestCasesTransformedForHomepage();
$latestArticles = $this->blogService->getLatestPostsForHomepage();
$homepageFaqs = $this->faqService->getHomepageFaqs();
$randomReviews = $this->reviewService->getRandomReviewsForHomepage();
$contactInfo = $this->contactService->getContactInfo();
$activeCaseCategories = $this->caseService->getActiveCategories();
$activeBlogCategories = $this->blogService->getActiveCategories();
```

---

## 3. Сервисы и их методы

### 3.1 HeroService
**Файл:** `app/Services/HeroService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getActiveHeroSections()` | Получает активные Hero-секции | `Collection<HeroSection>` |

**Использует модель:** `HeroSection`

---

### 3.2 ServiceService
**Файл:** `app/Services/ServiceService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getFeaturedServices()` | Получает услуги для главной (до 6 шт) | `Collection<Service>` |

**Использует модель:** `Service`
**Условия:** `published()`, `showOnHomepage()`, `ordered()`, `take(6)`

---

### 3.3 WhyUsService
**Файл:** `app/Services/WhyUsService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getActiveWhyUsBlocks()` | Получает активные блоки "Почему мы" | `Collection<WhyUs>` |

**Использует модель:** `WhyUs`

---

### 3.4 CaseService
**Файл:** `app/Services/CaseService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getLatestCasesTransformedForHomepage($limit = 4)` | Получает последние кейсы, трансформированные для шаблона | `array` |
| `getActiveCategories()` | Получает активные категории индустрий | `SupportCollection` |

**Использует модель:** `ProjectCase`, `IndustryCategory`
**Трансформация:** Преобразует модели в массив с ключами: `id`, `title`, `client`, `industry`, `industry_name`, `period`, `image`, `image_url`, `description`, `description_clean`, `content`, `results`, `has_valid_category`

---

### 3.5 BlogService
**Файл:** `app/Services/BlogService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getLatestPostsForHomepage($limit = 4)` | Получает последние статьи для главной | `Collection<Blog>` |
| `getActiveCategories()` | Получает активные категории блога | `SupportCollection` |

**Использует модель:** `Blog`, `BlogCategory`
**Условия:** `published()`, `with('blogCategory')`, `whereHas('blogCategory', is_active)`, `ordered()`, `limit(4)`

---

### 3.6 FaqService
**Файл:** `app/Services/FaqService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getHomepageFaqs()` | Получает FAQ для главной страницы | `Collection<Faq>` |

**Использует модель:** `Faq`
**Условия:** `visibleOnHomepage()`, `ordered()`

---

### 3.7 ReviewService
**Файл:** `app/Services/ReviewService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getRandomReviewsForHomepage($limit = 4)` | Получает случайные отзывы | `array` |

**Особенность:** Отзывы хранятся в виде статического массива внутри сервиса (не в БД)
**Структура отзыва:** `id`, `name`, `company`, `position`, `avatar`, `rating`, `text`, `date`, `industry`

---

### 3.8 ContactService
**Файл:** `app/Services/ContactService.php`

| Метод | Описание | Возвращает |
|-------|----------|------------|
| `getContactInfo()` | Получает контактную информацию | `array` |

**Использует модель:** `Contact`
**Возвращает:** `['address', 'phone', 'email', 'working_hours', 'social']`

---

## 4. Модели и их связи

### 4.1 HeroSection
**Файл:** `app/Models/HeroSection.php`
**Таблица:** `hero_sections`

**Поля:**
- `title` - заголовок
- `description` - описание
- `image` - изображение
- `button_text` - текст кнопки
- `is_active` - активен ли
- `sort_order` - порядок сортировки

**Scopes:**
- `active()` - только активные
- `ordered()` - сортировка по `sort_order`, затем по `id`

**Accessors:**
- `image_url` - URL изображения

---

### 4.2 Service
**Файл:** `app/Models/Service.php`
**Таблица:** `services`

**Traits:** `HasPublishing`, `HasSlug`

**Поля:**
- `title`, `slug`, `description`, `content`
- `icon`, `color`, `image`
- `price_from`, `price_type`
- `features` (array)
- `is_published`, `is_featured`, `show_on_homepage`
- `sort_order`
- Множество полей для связанного контента

**Scopes:**
- `published()` - только опубликованные (из `HasPublishing`)
- `showOnHomepage()` - показывать на главной
- `ordered()` - сортировка

**Accessors:**
- `formatted_price` - отформатированная цена

---

### 4.3 WhyUs
**Файл:** `app/Models/WhyUs.php`
**Таблица:** `why_us`

**Поля:**
- `title`, `description`
- `icon`, `color`
- `is_active`, `sort_order`

**Scopes:**
- `active()` - только активные
- `ordered()` - сортировка

---

### 4.4 ProjectCase
**Файл:** `app/Models/ProjectCase.php`
**Таблица:** `cases`

**Traits:** `HasPublishing`, `HasImage`

**Relations:**
- `industryCategory()` - `BelongsTo<IndustryCategory>`
- `user()` - `BelongsTo<User>`

**Поля:**
- `case_id`, `title`, `client`
- `industry_category_id`
- `period`, `image`, `description`, `content`
- `result_1` - `result_6` (старые поля)
- `results` (array, новое поле)
- `metrics` (array, новое поле)
- Множество полей `*_before` и `*_after` для метрик
- `is_published`, `sort_order`

**Accessors:**
- `image_url` - URL изображения
- `results_array` - массив результатов
- `available_metrics` - доступные метрики
- `description_clean` - описание без HTML

**Route Key:** `case_id`

---

### 4.5 Blog
**Файл:** `app/Models/Blog.php`
**Таблица:** `blogs`

**Traits:** `HasPublishing`, `HasImage`

**Relations:**
- `blogCategory()` - `BelongsTo<BlogCategory>`
- `user()` - `BelongsTo<User>`

**Поля:**
- `title`, `slug`, `excerpt`, `content`
- `image`, `category_id`
- `is_published`, `published_at`, `sort_order`
- `user_id`

**Accessors:**
- `image_url` - URL изображения
- `category_name` - название категории
- `formatted_published_at` - отформатированная дата
- `excerpt` - краткое описание (без HTML)
- `url` - URL статьи
- `reading_time` - время чтения

**Route Key:** `slug`

---

### 4.6 Faq
**Файл:** `app/Models/Faq.php`
**Таблица:** `faqs`

**Traits:** `HasPublishing`

**Поля:**
- `question`, `answer`
- `is_active`, `show_on_homepage`, `show_on_services`
- `sort_order`

**Scopes:**
- `visibleOnHomepage()` - видимые на главной
- `visibleOnServices()` - видимые на страницах услуг

---

### 4.7 Contact
**Файл:** `app/Models/Contact.php`
**Таблица:** `contacts`

**Особенность:** Singleton-паттерн (одна активная запись)

**Поля:**
- `email`, `phone`, `address`, `working_hours`
- `social_telegram`, `social_whatsapp`, `social_vk`, `social_instagram`, `social_youtube`
- `is_active`

**Методы:**
- `getInstance()` - получить единственный экземпляр
- `getContactData()` - получить данные для фронтенда

---

### 4.8 IndustryCategory
**Файл:** `app/Models/IndustryCategory.php`
**Таблица:** `industry_categories`

**Используется в:** `CaseService`

**Поля:**
- `name`, `slug`, `description`
- `icon`, `color`
- `is_active`, `sort_order`

---

### 4.9 BlogCategory
**Файл:** `app/Models/BlogCategory.php`
**Таблица:** `blog_categories`

**Используется в:** `BlogService`, `Blog` модель

**Поля:**
- `name`, `slug`, `description`
- `icon`, `color`
- `is_active`, `sort_order`

---

## 5. Представления (Views)

### 5.1 Главный шаблон
**Файл:** `resources/views/layouts/app.blade.php`

**Структура:**
- `@include('partials.header')` - шапка
- `@yield('content')` - контент
- `@include('partials.footer')` - подвал
- `@include('partials.modals')` - модальные окна

---

### 5.2 Главная страница
**Файл:** `resources/views/welcome.blade.php`

**Секции (в порядке отображения):**

#### 5.2.1 Hero Section (`#hero`)
- Если есть активные Hero: проверяет количество
  - 1 шт → `@include('partials.hero-single')`
  - >1 шт → `@include('partials.hero-slider')`
- Если нет → `@include('partials.hero-fallback')`

**Переменные:** `$activeHeroes`

---

#### 5.2.2 Services Section (`#services`)
- Заголовок: "Услуги"
- Сетка услуг (grid, адаптивная)
- Первая услуга занимает 2 колонки на больших экранах
- Кнопка "Заказать" для каждой услуги (модальное окно)
- Ссылка "Все услуги →"

**Переменные:** `$featuredServices`

---

#### 5.2.3 Why Us Section (`#why`)
- Заголовок: "Почему мы"
- Сетка блоков (grid, адаптивная)
- Если нет блоков → fallback с 4 статичными блоками

**Переменные:** `$whyUsBlocks`

---

#### 5.2.4 Cases Section (`#cases`)
- Заголовок: "Наши кейсы"
- Описание
- Сетка кейсов (grid, адаптивная)
- Каждый кейс: изображение, категория, заголовок, клиент, период, описание, результаты
- Ссылка "Все кейсы →"

**Переменные:** `$transformlatestCases` (массив)

---

#### 5.2.5 Blog Section (`#blog`)
- Заголовок: "Последние статьи"
- Описание
- Сетка статей (grid, адаптивная)
- Каждая статья: изображение/иконка, категория, дата, заголовок, краткое описание, время чтения
- Ссылка "Все статьи →"

**Переменные:** `$latestArticles`

---

#### 5.2.6 FAQ Section (`#faq`)
- `@include('partials.faq-section')`

**Переменные:** `$homepageFaqs`

---

#### 5.2.7 Reviews Section (`#reviews`)
- Заголовок: "Отзывы наших клиентов"
- Описание
- Сетка отзывов (grid, адаптивная)
- Каждый отзыв: рейтинг (звезды), текст, аватар, имя, должность, компания

**Переменные:** `$randomReviews` (массив)

---

#### 5.2.8 Contact Form Section (`#contact-form`)
- Две колонки:
  - **Левая:** Контактная информация (адрес, телефон, email)
  - **Правая:** Форма обратной связи

**Форма:**
- `action="{{ route('contact.submit') }}"`
- Поля: `name`, `email`, `phone`, `message`
- Валидация на фронтенде и бэкенде

**Переменные:** `$contactInfo`

---

### 5.3 Частичные представления (Partials)

#### 5.3.1 Hero Partials
- `partials/hero-single.blade.php` - одиночный Hero блок
- `partials/hero-slider.blade.php` - слайдер Hero блоков (с JS)
- `partials/hero-fallback.blade.php` - статичный контент по умолчанию

**Форма в Hero:**
- `action="{{ route('contact.hero') }}"`
- Поля: `name`, `phone`
- Обработчик: `ContactController::submitHero()`

---

#### 5.3.2 FAQ Partial
- `partials/faq-section.blade.php`
- Поддерживает два варианта: `$servicesFaqs` или `$homepageFaqs`
- Использует HTML `<details>` для аккордеона

---

## 6. Обработка форм

### 6.1 Hero Form
**Маршрут:** `POST /contact/hero`
**Контроллер:** `ContactController::submitHero()`
**Request:** `HeroRequest`
**Job:** `SendContactEmail`

**Поля:**
- `name` (required)
- `phone` (required)

---

### 6.2 Contact Form
**Маршрут:** `POST /contact`
**Контроллер:** `ContactController::submitContact()`
**Request:** `ContactRequest`
**Job:** `SendContactEmail`

**Поля:**
- `name` (required)
- `email` (required)
- `phone` (optional)
- `message` (required)

---

### 6.3 Service Order Form
**Маршрут:** `POST /service/order`
**Контроллер:** `ContactController::submitServiceOrder()`
**Request:** `ServiceOrderRequest`
**Job:** `SendServiceOrderEmail`

**Поля:**
- `service_name` (required)
- `name` (required)
- `email` (required)
- `phone` (required)
- `message` (optional)
- `attachment` (optional, file)

**Особенность:** Загружает файл в `storage/app/public/service-orders/`

---

## 7. Поток данных

```
1. Пользователь заходит на главную (GET /)
   ↓
2. Route вызывает HomeController::index()
   ↓
3. HomeController запрашивает данные через 8 сервисов:
   - HeroService → HeroSection (active, ordered)
   - ServiceService → Service (published, showOnHomepage, ordered, limit 6)
   - WhyUsService → WhyUs (active, ordered)
   - CaseService → ProjectCase (published, with category, ordered, limit 4) → трансформация в массив
   - BlogService → Blog (published, with category, active category, ordered, limit 4)
   - FaqService → Faq (visibleOnHomepage, ordered)
   - ReviewService → статичный массив (shuffle, limit 4)
   - ContactService → Contact (getInstance, getContactData)
   ↓
4. Данные передаются в view('welcome', compact(...))
   ↓
5. Blade шаблон рендерит секции:
   - Hero (с условной логикой: single/slider/fallback)
   - Services (grid с услугами)
   - Why Us (grid с блоками или fallback)
   - Cases (grid с кейсами)
   - Blog (grid со статьями)
   - FAQ (аккордеон)
   - Reviews (grid с отзывами)
   - Contact Form (форма + контакты)
   ↓
6. Пользователь видит готовую страницу
```

---

## 8. Особенности реализации

### 8.1 Трансформация данных
- **CaseService:** Преобразует модели `ProjectCase` в массивы для шаблона
- **ReviewService:** Хранит отзывы в статичном массиве (не в БД)

### 8.2 Условная логика в шаблонах
- Hero: проверка количества (1, >1, 0)
- Why Us: fallback если нет блоков
- Services/Cases/Blog/Reviews: проверка на пустоту

### 8.3 Очереди (Queues)
- Все формы отправляют email через Jobs:
  - `SendContactEmail`
  - `SendServiceOrderEmail`

### 8.4 Traits
- `HasPublishing` - для моделей с публикацией
- `HasImage` - для моделей с изображениями
- `HasSlug` - для моделей со slug

### 8.5 Scopes
Все модели используют scopes для фильтрации:
- `active()` / `published()` - активные/опубликованные
- `ordered()` - сортировка
- Специфичные scopes: `showOnHomepage()`, `visibleOnHomepage()`

---

## 9. Зависимости между компонентами

```
HomeController
  ├── HeroService → HeroSection
  ├── ServiceService → Service
  ├── WhyUsService → WhyUs
  ├── CaseService → ProjectCase → IndustryCategory
  ├── BlogService → Blog → BlogCategory
  ├── FaqService → Faq
  ├── ReviewService → (статичные данные)
  └── ContactService → Contact
```

---

## 10. Файловая структура

```
app/
├── Http/
│   └── Controllers/
│       ├── HomeController.php
│       └── ContactController.php
├── Services/
│   ├── HeroService.php
│   ├── ServiceService.php
│   ├── WhyUsService.php
│   ├── CaseService.php
│   ├── BlogService.php
│   ├── FaqService.php
│   ├── ReviewService.php
│   └── ContactService.php
└── Models/
    ├── HeroSection.php
    ├── Service.php
    ├── WhyUs.php
    ├── ProjectCase.php
    ├── Blog.php
    ├── Faq.php
    ├── Contact.php
    ├── IndustryCategory.php
    └── BlogCategory.php

resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    ├── welcome.blade.php
    └── partials/
        ├── hero-single.blade.php
        ├── hero-slider.blade.php
        ├── hero-fallback.blade.php
        └── faq-section.blade.php

routes/
└── web.php
```

---

## 11. Резюме

**Главная страница состоит из:**

1. **8 секций контента:**
   - Hero (1-N блоков или fallback)
   - Services (до 6 услуг)
   - Why Us (N блоков или fallback)
   - Cases (4 последних кейса)
   - Blog (4 последние статьи)
   - FAQ (N вопросов)
   - Reviews (4 случайных отзыва)
   - Contact Form (форма + контакты)

2. **8 сервисов** для получения данных

3. **8+ моделей** для хранения данных

4. **3 формы** для обратной связи

5. **Условная логика** для обработки пустых данных

6. **Адаптивный дизайн** (Tailwind CSS)

7. **Очереди** для отправки email
