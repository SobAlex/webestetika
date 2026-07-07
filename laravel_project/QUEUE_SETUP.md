# 🚀 Настройка очереди для отправки email

## ✅ Что уже сделано:

1. **Созданы Job классы:**
   - `SendServiceOrderEmail` - для заказов услуг
   - `SendContactEmail` - для контактных форм

2. **Обновлен ContactController:**
   - Все формы теперь используют очереди
   - Пользователь получает мгновенный отклик

3. **Настроена база данных:**
   - Таблицы `jobs` и `failed_jobs` уже созданы
   - Используется `database` драйвер очереди

## 🔧 Запуск обработчика очереди:

### ⚠️ ВАЖНО: Без запущенного обработчика письма НЕ ОТПРАВЛЯЮТСЯ!

### Быстрый запуск (используйте готовый скрипт):
```bash
./start-queue.sh
```

### Для разработки (ручной запуск):
```bash
php artisan queue:work --verbose
```

### Для production (автоматический перезапуск):
```bash
php artisan queue:work --daemon --tries=3 --timeout=120
```

### Обработка только одной задачи:
```bash
php artisan queue:work --once
```

### Проверка задач в очереди:
```bash
php artisan tinker --execute="echo 'Задач в очереди: ' . \Illuminate\Support\Facades\DB::table('jobs')->count();"
```

## 📊 Мониторинг очереди:

### Просмотр задач в очереди:
```bash
php artisan queue:monitor database
```

### Просмотр неудачных задач:
```bash
php artisan queue:failed
```

### Повторный запуск неудачных задач:
```bash
php artisan queue:retry all
```

### Очистка неудачных задач:
```bash
php artisan queue:flush
```

## ⚙️ Настройка для production:

### 1. Supervisor (рекомендуется)
Создайте файл `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/laravel-worker.log
stopwaitsecs=3600
```

Затем:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 2. Systemd Service
Создайте файл `/etc/systemd/system/laravel-queue.service`:

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
ExecReload=/bin/kill -HUP $MAINPID
KillMode=mixed
KillSignal=SIGTERM
TimeoutStopSec=5

[Install]
WantedBy=multi-user.target
```

Затем:
```bash
sudo systemctl daemon-reload
sudo systemctl enable laravel-queue
sudo systemctl start laravel-queue
```

## 🔄 Преимущества внедренной системы:

### ✅ Для пользователей:
- **Мгновенный отклик** - форма отвечает сразу
- **Надежность** - письма не теряются даже при сбоях
- **Лучший UX** - нет "зависания" форм

### ✅ Для системы:
- **Масштабируемость** - можно обрабатывать много заявок
- **Отказоустойчивость** - повторные попытки при сбоях
- **Мониторинг** - логи и статистика обработки
- **Производительность** - не блокирует основные запросы

## 📧 Настройка SMTP (опционально):

Для реальной отправки писем обновите `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Your Site Name"
```

## 🐛 Отладка:

### Просмотр логов:
```bash
tail -f storage/logs/laravel.log
```

### Тестирование отправки:
```bash
php artisan tinker
>>> App\Jobs\SendContactEmail::dispatch(['subject' => 'Test', 'name' => 'Test User']);
```

## 🎯 Результат:

Теперь все формы на сайте работают **мгновенно**:
- Пользователь сразу видит "Заявка отправлена!"
- Письма отправляются в фоне
- Система устойчива к сбоям
- Легко масштабируется
