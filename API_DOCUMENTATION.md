# API для партнёрского приложения

## Описание
API для сканирования QR-кодов пользователей и списания баллов партнёрами.

## Базовый URL
`http://your-domain.com/api/partner`

## Аутентификация
API использует Bearer Token аутентификацию. Токен должен быть передан в заголовке Authorization:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Эндпоинты

### 1. Авторизация партнёра
**POST** `/login`

**Тело запроса:**
```json
{
    "email": "partner@example.com",
    "password": "password"
}
```

**Ответ (успех):**
```json
{
    "success": true,
    "message": "Успешная авторизация",
    "data": {
        "user": {
            "id": 1,
            "email": "partner@example.com",
            "name": "Название партнёра",
            "profile": {...}
        },
        "token": "abc123...",
        "expires_at": "2025-10-15 16:40:27"
    }
}
```

### 2. Получение информации о пользователе по ID (из QR-кода)
**GET** `/user/{user_id}`

**Заголовки:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Ответ (успех):**
```json
{
    "success": true,
    "message": "Информация о пользователе получена",
    "data": {
        "user": {
            "id": 5,
            "user_id": "000005",
            "name": "Иван Иванов",
            "email": "ivan@example.com",
            "profile": {...},
            "points_balance": 150,
            "total_earned": 200,
            "total_spent": 50
        }
    }
}
```

### 3. Списание баллов
**POST** `/deduct-points`

**Заголовки:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Тело запроса:**
```json
{
    "user_id": 5,
    "points": 50,
    "description": "Покупка товара X"
}
```

**Ответ (успех):**
```json
{
    "success": true,
    "message": "Баллы успешно списаны",
    "data": {
        "transaction_id": 123,
        "user": {
            "id": 5,
            "name": "Иван Иванов",
            "previous_balance": 150,
            "deducted_points": 50,
            "new_balance": 100
        },
        "partner": {
            "id": 1,
            "name": "Название партнёра"
        },
        "description": "Покупка товара X",
        "created_at": "2025-09-15 16:45:00"
    }
}
```

### 4. История транзакций партнёра
**GET** `/transactions?page=1&per_page=20&date_from=2025-09-01&date_to=2025-09-30`

**Заголовки:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Ответ (успех):**
```json
{
    "success": true,
    "message": "История транзакций получена",
    "data": {
        "transactions": [...],
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 20,
            "total": 95
        }
    }
}
```

### 5. Выход из системы
**POST** `/logout`

**Заголовки:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Ответ (успех):**
```json
{
    "success": true,
    "message": "Успешный выход из системы"
}
```

## Ошибки

### Ошибки валидации (422)
```json
{
    "success": false,
    "message": "Ошибка валидации",
    "errors": {
        "email": ["Поле email обязательно для заполнения"]
    }
}
```

### Неавторизованный доступ (401)
```json
{
    "success": false,
    "message": "Токен авторизации не предоставлен"
}
```

### Недостаточно баллов (400)
```json
{
    "success": false,
    "message": "Недостаточно баллов для списания",
    "data": {
        "current_balance": 30,
        "requested_points": 50
    }
}
```

## Примеры использования

### Процесс работы с API:
1. Партнёр авторизуется через `/login` и получает токен
2. Сканирует QR-код пользователя (получает user_id)
3. Запрашивает информацию о пользователе через `/user/{user_id}`
4. Показывает баланс баллов пользователя
5. При необходимости списывает баллы через `/deduct-points`
6. Может просмотреть историю своих транзакций через `/transactions`

---

# Публичное API

## Описание
Публичное API для получения данных о событиях, новостях, грантах и вакансиях. Не требует аутентификации.

## Базовый URL
`http://your-domain.com/api/public`

## Эндпоинты

### События (Events)

#### 1. Получение списка событий
**GET** `/events`

**Параметры запроса:**
- `per_page` (optional, integer, max 100, default 15) - количество элементов на странице
- `page` (optional, integer, default 1) - номер страницы
- `search` (optional, string) - поиск по заголовку, описанию
- `category` (optional, string) - фильтр по категории
- `type` (optional, string) - фильтр по типу события
- `settlement` (optional, string) - фильтр по населенному пункту

**Пример запроса:**
```
GET /api/public/events?per_page=10&search=молодежь&category=образование
```

**Ответ (успех):**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Молодежный форум",
            "short_description": "Краткое описание события",
            "description": "Полное описание события",
            "category": "образование",
            "type": "форум",
            "cover": "path/to/image.jpg",
            "address": "г. Москва, ул. Тверская, 1",
            "settlement": "Москва",
            "start": "2025-11-15",
            "end": "2025-11-17",
            "supervisor_name": "Иван",
            "supervisor_l_name": "Иванов",
            "supervisor_phone": "+79001234567",
            "supervisor_email": "supervisor@example.com",
            "web": "https://example.com",
            "telegram": "@example",
            "vk": "https://vk.com/example",
            "roles": ["участник", "волонтер"],
            "created_at": "2025-10-15T10:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "Организатор"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 45
    }
}
```

#### 2. Получение события по ID
**GET** `/events/{id}`

**Ответ (успех):**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Молодежный форум",
        "short_description": "Краткое описание события",
        "description": "Полное описание события",
        "category": "образование",
        "type": "форум",
        "cover": "path/to/image.jpg",
        "address": "г. Москва, ул. Тверская, 1",
        "settlement": "Москва",
        "start": "2025-11-15",
        "end": "2025-11-17",
        "supervisor_name": "Иван",
        "supervisor_l_name": "Иванов",
        "supervisor_phone": "+79001234567",
        "supervisor_email": "supervisor@example.com",
        "docs": ["document1.pdf", "document2.pdf"],
        "images": ["image1.jpg", "image2.jpg"],
        "videos": ["video1.mp4"],
        "web": "https://example.com",
        "telegram": "@example",
        "vk": "https://vk.com/example",
        "roles": ["участник", "волонтер"],
        "created_at": "2025-10-15T10:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "Организатор"
        }
    }
}
```

### Новости (News)

#### 1. Получение списка новостей
**GET** `/news`

**Параметры запроса:**
- `per_page` (optional, integer, max 100, default 15) - количество элементов на странице
- `page` (optional, integer, default 1) - номер страницы
- `search` (optional, string) - поиск по заголовку, описанию
- `category` (optional, string) - фильтр по категории

**Пример запроса:**
```
GET /api/public/news?per_page=5&category=спорт
```

**Ответ (успех):**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Спортивные достижения молодежи",
            "short_description": "Краткое описание новости",
            "description": "Полный текст новости",
            "cover": "path/to/news-image.jpg",
            "category": "спорт",
            "created_at": "2025-10-15T10:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "Автор новости"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 5,
        "total": 12
    }
}
```

#### 2. Получение новости по ID
**GET** `/news/{id}`

**Ответ (успех):**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Спортивные достижения молодежи",
        "short_description": "Краткое описание новости",
        "description": "Полный текст новости с подробной информацией",
        "cover": "path/to/news-image.jpg",
        "category": "спорт",
        "created_at": "2025-10-15T10:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "Автор новости"
        }
    }
}
```

### Гранты (Grants)

#### 1. Получение списка грантов
**GET** `/grants`

**Параметры запроса:**
- `per_page` (optional, integer, max 100, default 15) - количество элементов на странице
- `page` (optional, integer, default 1) - номер страницы
- `search` (optional, string) - поиск по заголовку, описанию
- `category` (optional, string) - фильтр по категории
- `settlement` (optional, string) - фильтр по населенному пункту

**Пример запроса:**
```
GET /api/public/grants?per_page=8&category=стартапы
```

**Ответ (успех):**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Грант для молодых предпринимателей",
            "short_description": "Поддержка молодежных стартапов",
            "description": "Подробное описание гранта и условий участия",
            "category": "стартапы",
            "cover": "path/to/grant-image.jpg",
            "address": "г. Москва",
            "settlement": "Москва",
            "deadline": "2025-12-31",
            "conditions": "Условия получения гранта",
            "requirements": "Требования к участникам",
            "reward": "1000000",
            "docs": ["application_form.pdf"],
            "web": "https://grant-website.com",
            "telegram": "@grant_channel",
            "vk": "https://vk.com/grant_group",
            "created_at": "2025-10-15T10:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "Организация-грантодатель"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 2,
        "per_page": 8,
        "total": 15
    }
}
```

#### 2. Получение гранта по ID
**GET** `/grants/{id}`

**Ответ (успех):**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Грант для молодых предпринимателей",
        "short_description": "Поддержка молодежных стартапов",
        "description": "Подробное описание гранта, его целей и задач...",
        "category": "стартапы",
        "cover": "path/to/grant-image.jpg",
        "address": "г. Москва, ул. Деловая, 10",
        "settlement": "Москва",
        "deadline": "2025-12-31",
        "conditions": "Подробные условия получения гранта",
        "requirements": "Требования к участникам и проектам",
        "reward": "1000000",
        "docs": ["application_form.pdf", "requirements.pdf"],
        "web": "https://grant-website.com",
        "telegram": "@grant_channel",
        "vk": "https://vk.com/grant_group",
        "created_at": "2025-10-15T10:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "Организация-грантодатель"
        }
    }
}
```

### Вакансии (Vacancies)

#### 1. Получение списка вакансий
**GET** `/vacancies`

**Параметры запроса:**
- `per_page` (optional, integer, max 100, default 15) - количество элементов на странице
- `page` (optional, integer, default 1) - номер страницы
- `search` (optional, string) - поиск по заголовку, описанию, названию организации
- `category` (optional, string) - фильтр по категории
- `type` (optional, string) - фильтр по типу занятости
- `experience` (optional, string) - фильтр по требуемому опыту
- `salary_from` (optional, integer) - минимальная зарплата
- `salary_to` (optional, integer) - максимальная зарплата

**Пример запроса:**
```
GET /api/public/vacancies?per_page=5&category=IT&salary_from=50000
```

**Ответ (успех):**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Junior Frontend Developer",
            "description": "Разработка пользовательских интерфейсов",
            "category": "IT",
            "salary_from": 60000,
            "salary_to": 100000,
            "salary_negotiable": false,
            "type": "полная занятость",
            "experience": "без опыта",
            "org_name": "ТехКомпания",
            "org_phone": "+79001234567",
            "org_email": "hr@techcompany.com",
            "org_address": "г. Москва, ул. IT-парк, 5",
            "created_at": "2025-10-15T10:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "HR Менеджер"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 4,
        "per_page": 5,
        "total": 18
    }
}
```

#### 2. Получение вакансии по ID
**GET** `/vacancies/{id}`

**Ответ (успех):**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Junior Frontend Developer",
        "description": "Мы ищем начинающего фронтенд-разработчика для работы над интересными проектами. Требования: знание HTML, CSS, JavaScript...",
        "category": "IT",
        "salary_from": 60000,
        "salary_to": 100000,
        "salary_negotiable": false,
        "type": "полная занятость",
        "experience": "без опыта",
        "org_name": "ТехКомпания ООО",
        "org_phone": "+79001234567",
        "org_email": "hr@techcompany.com",
        "org_address": "г. Москва, ул. IT-парк, 5, офис 301",
        "created_at": "2025-10-15T10:00:00.000000Z",
        "user": {
            "id": 1,
            "name": "HR Менеджер"
        }
    }
}
```

## Ошибки публичного API

### Ресурс не найден (404)
```json
{
    "status": "error",
    "message": "Event not found"
}
```

```json
{
    "status": "error",
    "message": "News not found"
}
```

```json
{
    "status": "error",
    "message": "Grant not found"
}
```

```json
{
    "status": "error",
    "message": "Vacancy not found"
}
```

### Валидация параметров (422)
```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": {
        "per_page": ["The per page must be an integer."]
    }
}
```

## Особенности публичного API

1. **Без аутентификации**: Все эндпоинты доступны без токенов
2. **Только одобренный контент**: Возвращаются только записи со статусом `approved`
3. **Пагинация**: Все списки поддерживают пагинацию с максимумом 100 элементов на странице
4. **Фильтрация и поиск**: Поддержка поиска по тексту и фильтрации по категориям
5. **Сортировка**: По умолчанию сортировка по дате создания (новые первые)
6. **Связанные данные**: Включает информацию об авторе/создателе
