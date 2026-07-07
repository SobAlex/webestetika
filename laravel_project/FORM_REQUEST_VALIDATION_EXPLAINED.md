# 🔍 Как работает автоматическая валидация FormRequest в Laravel

## ❓ Вопрос
Как контроллер понимает, что нужно использовать `HeroRequest` для валидации данных формы, если в методе контроллера только указан тип параметра `HeroRequest $request`?

## ✅ Ответ: Магия Laravel происходит автоматически!

### 1️⃣ **Dependency Injection (Внедрение зависимостей)**

Когда Laravel видит в сигнатуре метода контроллера тип `HeroRequest`:

```php
public function submitHero(HeroRequest $request)
{
    // ...
}
```

Laravel автоматически:
- Распознает, что `HeroRequest` наследуется от `FormRequest`
- Создает экземпляр `HeroRequest` через Service Container
- Передает в него все данные из HTTP-запроса (POST данные формы)

### 2️⃣ **Автоматическая валидация через Middleware**

**Важно:** Валидация происходит **ДО** того, как метод контроллера будет вызван!

Laravel использует встроенный middleware, который автоматически:
1. Вызывает метод `authorize()` - проверяет права доступа
2. Вызывает метод `rules()` - получает правила валидации
3. Валидирует данные по этим правилам
4. **Если валидация НЕ прошла:**
   - Автоматически возвращает ответ с ошибками (422 статус)
   - Перенаправляет обратно с ошибками (для веб-запросов)
   - Метод контроллера **НЕ ВЫЗЫВАЕТСЯ**
5. **Если валидация прошла:**
   - Продолжает выполнение и вызывает метод контроллера
   - В метод передается уже валидированный `HeroRequest`

### 3️⃣ **Последовательность выполнения**

```
1. Пользователь отправляет форму POST /contact/hero
   ↓
2. Laravel роутинг находит метод: ContactController@submitHero
   ↓
3. Laravel видит тип параметра: HeroRequest $request
   ↓
4. Laravel создает экземпляр HeroRequest через Service Container
   ↓
5. Middleware автоматически вызывает:
   - HeroRequest->authorize() → проверяет права (возвращает true)
   - HeroRequest->rules() → получает правила валидации
   ↓
6. Laravel валидирует данные формы по правилам:
   - name: required|string|max:255
   - phone: required|string|regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/
   ↓
7. ЕСЛИ ВАЛИДАЦИЯ НЕ ПРОШЛА:
   → Возвращает ошибки (422 или редирект назад с ошибками)
   → Метод submitHero() НЕ ВЫЗЫВАЕТСЯ
   ↓
8. ЕСЛИ ВАЛИДАЦИЯ ПРОШЛА:
   → Вызывает метод submitHero(HeroRequest $request)
   → В $request уже валидированные данные
   → Вы можете безопасно использовать $request->validated()
```

### 4️⃣ **Где находится логика автоматической валидации?**

Логика находится в базовом классе `Illuminate\Foundation\Http\FormRequest`:

```php
// Laravel Framework (vendor/laravel/framework/src/Illuminate/Foundation/Http/FormRequest.php)

class FormRequest extends Request
{
    // Автоматически вызывается middleware перед контроллером
    public function validateResolved()
    {
        $this->prepareForValidation();

        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        if ($instance->fails()) {
            $this->failedValidation($instance);
        }
    }
}
```

### 5️⃣ **Что происходит при ошибке валидации?**

Если валидация не прошла, Laravel:

**Для веб-запросов (HTML формы):**
- Автоматически делает `redirect()->back()`
- Добавляет ошибки в сессию
- Ошибки доступны через `$errors` в Blade шаблонах
- Использует `errorBag` из FormRequest (в вашем случае `'hero'`)

**Для API-запросов (JSON):**
- Возвращает JSON ответ со статусом 422
- Включает массив ошибок валидации

### 6️⃣ **Практический пример из вашего кода**

```php
// routes/web.php
Route::post('/contact/hero', [ContactController::class, 'submitHero'])
    ->name('contact.hero');

// app/Http/Controllers/ContactController.php
public function submitHero(HeroRequest $request)  // ← Здесь магия!
{
    // К этому моменту данные УЖЕ валидированы!
    // Если бы валидация не прошла, этот метод НЕ ВЫЗОВЕТСЯ

    $validated = $request->validated(); // Безопасно получаем валидированные данные

    // Отправляем email...
}

// app/Http/Requests/HeroRequest.php
class HeroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Разрешаем всем
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
        ];
    }

    protected $errorBag = 'hero'; // Имя для группы ошибок
}
```

### 7️⃣ **Почему это работает без явного вызова?**

Laravel использует **Service Container** и **автоматическое разрешение зависимостей**:

1. Когда Laravel видит тип `HeroRequest` в параметре метода
2. Он проверяет, может ли Service Container создать этот класс
3. `HeroRequest` наследуется от `FormRequest`
4. `FormRequest` регистрирует middleware для автоматической валидации
5. Middleware выполняется **перед** вызовом метода контроллера

### 8️⃣ **Преимущества этого подхода**

✅ **Автоматическая валидация** - не нужно вызывать `$request->validate()` вручную
✅ **Чистый код** - валидация отделена от бизнес-логики
✅ **Безопасность** - метод контроллера вызывается только с валидированными данными
✅ **Переиспользование** - один FormRequest можно использовать в разных местах
✅ **Тестируемость** - легко тестировать валидацию отдельно

### 9️⃣ **Что если нужно валидировать вручную?**

Если вы хотите валидировать вручную (не рекомендуется), используйте обычный `Request`:

```php
// ❌ Не рекомендуется
public function submitHero(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
    ]);
    // ...
}

// ✅ Рекомендуется (ваш текущий подход)
public function submitHero(HeroRequest $request)
{
    $validated = $request->validated(); // Уже валидировано!
    // ...
}
```

## 📚 Итог

**Вы не видите явного вызова валидации, потому что Laravel делает это автоматически через:**
1. **Dependency Injection** - автоматическое создание `HeroRequest`
2. **Middleware** - автоматическая валидация перед вызовом метода
3. **Service Container** - управление жизненным циклом объектов

Это одна из "магических" возможностей Laravel, которая делает код чище и безопаснее! ✨






