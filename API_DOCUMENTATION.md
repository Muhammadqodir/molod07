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
