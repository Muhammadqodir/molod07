# Книжная полка (Book Shelf) - Документация

## Обзор
Функция "Книжная полка" добавлена в раздел "Образование" для управления и отображения каталога книг.

## Структура базы данных

### Таблица `books`
```sql
- id (primary key)
- user_id (foreign key → users)
- cover (string, nullable) - путь к обложке книги
- title (string) - название книги
- author (string) - автор книги
- description (text, nullable) - краткое описание/аннотация
- link (string, nullable) - ссылка на электронную версию
- status (string, default: 'pending') - статус: pending/approved/archived
- admin_id (foreign key → users, nullable) - администратор, одобривший книгу
- timestamps (created_at, updated_at)
```

## Созданные файлы

### 1. Модель
- **`app/Models/Book.php`**
  - Использует трейт `HasInteractions` для лайков, просмотров, комментариев
  - Связи с таблицей users (создатель и администратор)

### 2. Миграция
- **`database/migrations/2025_11_27_154612_create_books_table.php`**

### 3. Контроллеры

#### Административный контроллер
- **`app/Http/Controllers/Admin/ManageBooksController.php`**
  - `show()` - список книг с фильтрацией и поиском
  - `create()` - форма создания
  - `store()` - сохранение новой книги
  - `edit()` - форма редактирования
  - `update()` - обновление книги
  - `approve()` - одобрение книги
  - `reject()` - отклонение книги
  - `archive()` - архивирование книги
  - `destroy()` - удаление книги

#### Публичный контроллер
- **`app/Http/Controllers/PagesController.php`**
  - Добавлен метод `booksList()` для отображения книг на сайте

### 4. Представления (Views)

#### Административные страницы
- **`resources/views/admin/books/list.blade.php`** - список книг с таблицей
- **`resources/views/admin/books/create.blade.php`** - форма добавления книги
- **`resources/views/admin/books/edit.blade.php`** - форма редактирования книги

#### Публичная страница
- **`resources/views/pages/books.blade.php`** - каталог книг для пользователей
  - Отображение в виде сетки с обложками
  - Поиск по названию, автору, описанию
  - Кнопка "Читать" при наведении на обложку
  - Пагинация

## Маршруты (Routes)

### Публичные маршруты
```php
Route::get('/books', [PagesController::class, 'booksList'])->name("books.list");
```

### Административные маршруты
```php
// Список книг
Route::get('admin/books/list', [ManageBooksController::class, 'show'])->name('admin.books.index');
Route::get('admin/books/requests', [ManageBooksController::class, 'show'])->name('admin.books.requests');
Route::get('admin/books/archive', [ManageBooksController::class, 'show'])->name('admin.books.archive');

// CRUD операции
Route::get('admin/books/create', [ManageBooksController::class, 'create'])->name('admin.books.create');
Route::post('admin/books/create', [ManageBooksController::class, 'store'])->name('admin.books.store');
Route::get('admin/books/edit/{id}', [ManageBooksController::class, 'edit'])->name('admin.books.edit');
Route::put('admin/books/update/{id}', [ManageBooksController::class, 'update'])->name('admin.books.update');

// Действия
Route::post('admin/books/approve/{id}', [ManageBooksController::class, 'approve'])->name('admin.books.approve');
Route::post('admin/books/reject/{id}', [ManageBooksController::class, 'reject'])->name('admin.books.reject');
Route::post('admin/books/action/archive/{id}', [ManageBooksController::class, 'archive'])->name('admin.books.action.archive');
Route::delete('admin/books/{id}', [ManageBooksController::class, 'destroy'])->name('admin.books.destroy');
```

## Функциональность

### Для администратора
1. **Управление книгами**:
   - Добавление новых книг через админ-панель
   - Редактирование существующих книг
   - Удаление книг
   - Модерация заявок (если пользователи смогут добавлять книги)

2. **Статусы книг**:
   - `pending` - ожидает модерации
   - `approved` - одобрена, отображается на сайте
   - `archived` - в архиве

3. **Поиск и фильтрация**:
   - Поиск по названию, автору, описанию
   - Фильтрация по статусу (активные/заявки/архив)

### Для пользователей
1. **Каталог книг**:
   - Визуальный каталог с обложками книг
   - Сетка из 6 колонок (адаптивная)
   - Информация: обложка, название, автор, описание

2. **Интерактивность**:
   - При наведении на обложку появляется кнопка "Читать"
   - Клик по кнопке открывает электронную версию книги

3. **Поиск**:
   - Поиск по названию, автору или описанию
   - Мгновенный поиск при отправке формы

## Доступ

### Административная панель
- URL: `/admin/books/list`
- Меню: Боковая панель → "Книжная полка"
- Подразделы: Активные / Заявки / Архив

### Публичная страница
- URL: `/books`
- Доступна всем пользователям

## Использование

### Добавление книги (администратор)
1. Перейти в админ-панель → "Книжная полка"
2. Нажать кнопку "Добавить"
3. Заполнить форму:
   - Загрузить обложку (опционально)
   - Название книги*
   - Автор*
   - Краткое описание/аннотация
   - Ссылка на электронную версию
4. Нажать "Добавить книгу"

### Редактирование книги
1. В списке книг нажать иконку редактирования
2. Изменить необходимые поля
3. Нажать "Обновить книгу"

### Удаление книги
1. В списке книг нажать иконку удаления
2. Подтвердить действие

## Валидация

### При создании/редактировании:
- `title` - обязательное поле, максимум 255 символов
- `author` - обязательное поле, максимум 255 символов
- `description` - опционально, текстовое поле
- `link` - опционально, должна быть валидным URL
- `cover` - опционально, изображение (jpeg, png, jpg, gif), максимум 2 МБ

## Интеграции

### Трейт HasInteractions
Книги поддерживают:
- Лайки
- Просмотры
- Комментарии

(Функционал можно активировать по необходимости)

## Файловое хранилище
- Обложки сохраняются в: `storage/uploads/books/covers/`
- Публичный путь: `storage/uploads/books/covers/`

## Миграция базы данных
```bash
php artisan migrate
```

## Будущие улучшения (опционально)
1. Категории книг
2. Рейтинги и отзывы
3. Избранное для пользователей
4. История чтения
5. Рекомендации на основе предпочтений
6. Экспорт каталога в PDF
7. Возможность скачивания книг
8. Интеграция с внешними библиотеками
