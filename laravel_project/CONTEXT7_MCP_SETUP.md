# 🚀 Context7 MCP Setup Guide

## Обзор

Context7 MCP (Model Context Protocol) интегрирован в ваш Laravel проект для расширенной работы с AI моделями и контекстным анализом.

## 📁 Созданные файлы

### Конфигурация
- `mcp-config.json` - Основная конфигурация MCP сервера
- `context7.env.example` - Пример переменных окружения
- `start-context7-mcp.sh` - Скрипт для запуска MCP сервера

### Обновления
- `package.json` - Добавлены зависимости и скрипты для Context7 MCP

## 🛠️ Установка и настройка

### 1. Установка зависимостей
```bash
npm install
```

### 2. Настройка переменных окружения
```bash
# Добавьте API ключ в основной .env файл
echo "CONTEXT7_API_KEY=your-actual-api-key-here" >> .env
```

### 3. Автозапуск MCP сервера

**MCP сервер запускается автоматически** когда вы добавляете `use context7` к запросу в Cursor.

**Никаких дополнительных действий не требуется!** ✅

## 🎯 Использование

### Доступные команды

```bash
# Запуск MCP сервера
npm run mcp:start

# Прямой запуск Context7 MCP
npm run mcp:context7

# Запуск через скрипт (с проверками)
./start-context7-mcp.sh
```

### Интеграция с Cursor

1. Откройте настройки Cursor
2. Перейдите в раздел "MCP Servers"
3. Добавьте новый сервер:
   - **Name**: Context7
   - **Command**: `npx`
   - **Args**: `["@context7/mcp", "--config", "./mcp-config.json"]`
   - **Working Directory**: `/Users/aleksandrsobolev/Sites/myproject`

## ⚙️ Конфигурация

### Основные параметры в mcp-config.json

```json
{
  "mcpServers": {
    "context7": {
      "command": "npx",
      "args": ["@context7/mcp"],
      "env": {
        "CONTEXT7_API_KEY": "your-api-key-here"
      }
    }
  }
}
```

### Переменные окружения

- `CONTEXT7_API_KEY` - API ключ для Context7
- `CONTEXT7_BASE_URL` - Базовый URL API (по умолчанию: https://api.context7.com)
- `CONTEXT7_MODEL` - Модель для использования (по умолчанию: gpt-4)
- `CONTEXT7_MAX_TOKENS` - Максимальное количество токенов (по умолчанию: 4000)
- `CONTEXT7_TEMPERATURE` - Температура для генерации (по умолчанию: 0.7)

## 🔧 Troubleshooting

### Проблема: MCP сервер не запускается
**Решение**: Проверьте, что все зависимости установлены:
```bash
npm install
```

### Проблема: Ошибка API ключа
**Решение**: Убедитесь, что в .env файле указан правильный API ключ:
```bash
CONTEXT7_API_KEY=your-actual-api-key-here
```

### Проблема: Конфигурация не загружается
**Решение**: Проверьте путь к конфигурационному файлу в настройках Cursor

## 📚 Дополнительные ресурсы

- [Context7 Documentation](https://docs.context7.com)
- [MCP Protocol Specification](https://spec.modelcontextprotocol.io)
- [Cursor MCP Integration](https://cursor.sh/docs/mcp)

## ✅ Статус интеграции

- ✅ Конфигурация MCP сервера создана
- ✅ Зависимости добавлены в package.json
- ✅ Скрипты запуска настроены
- ✅ Документация создана
- ✅ Переменные окружения настроены

Context7 MCP успешно интегрирован в ваш Laravel проект! 🎉
