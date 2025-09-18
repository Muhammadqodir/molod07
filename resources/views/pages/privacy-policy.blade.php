@extends('layouts.app')

@section('title', 'Политика конфиденциальности')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Политика конфиденциальности</h1>

        <div class="prose max-w-none">
            <p class="text-gray-600 mb-6">Последнее обновление: {{ date('d.m.Y') }}</p>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Общие положения</h2>
                <p class="text-gray-700 mb-4">
                    Настоящая Политика конфиденциальности определяет порядок обработки персональных данных
                    пользователей платформы молодежной политики. Мы серьезно относимся к защите ваших
                    персональных данных и обязуемся обеспечивать их безопасность.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Какие данные мы собираем</h2>
                <p class="text-gray-700 mb-4">Мы можем собирать следующие типы персональных данных:</p>
                <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                    <li>Имя, фамилия и контактная информация</li>
                    <li>Адрес электронной почты</li>
                    <li>Номер телефона</li>
                    <li>Информация о профиле (дата рождения, образование, интересы)</li>
                    <li>Данные об активности на платформе</li>
                    <li>Техническая информация (IP-адрес, браузер, операционная система)</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Цели обработки данных</h2>
                <p class="text-gray-700 mb-4">Мы используем ваши персональные данные для:</p>
                <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                    <li>Предоставления услуг платформы</li>
                    <li>Создания и управления учетной записью</li>
                    <li>Обеспечения участия в мероприятиях и программах</li>
                    <li>Отправки уведомлений и новостей</li>
                    <li>Улучшения качества услуг</li>
                    <li>Обеспечения безопасности платформы</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Передача данных третьим лицам</h2>
                <p class="text-gray-700 mb-4">
                    Мы не продаем, не обмениваем и не передаем ваши персональные данные третьим лицам
                    без вашего согласия, за исключением случаев:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                    <li>Требований законодательства</li>
                    <li>Необходимости предоставления услуг (партнеры-организаторы мероприятий)</li>
                    <li>Защиты наших прав и безопасности</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Безопасность данных</h2>
                <p class="text-gray-700 mb-4">
                    Мы применяем технические и организационные меры для защиты ваших персональных данных
                    от несанкционированного доступа, изменения, раскрытия или уничтожения.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Ваши права</h2>
                <p class="text-gray-700 mb-4">Вы имеете право:</p>
                <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                    <li>Получать информацию об обработке ваших данных</li>
                    <li>Требовать исправления неточных данных</li>
                    <li>Требовать удаления ваших данных</li>
                    <li>Ограничивать обработку данных</li>
                    <li>Отзывать согласие на обработку</li>
                    <li>Подавать жалобы в надзорные органы</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Cookies</h2>
                <p class="text-gray-700 mb-4">
                    Мы используем файлы cookie для улучшения функциональности сайта, анализа
                    посещаемости и персонализации контента. Вы можете управлять настройками
                    cookie в своем браузере.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Срок хранения данных</h2>
                <p class="text-gray-700 mb-4">
                    Мы храним ваши персональные данные только в течение времени, необходимого
                    для достижения целей обработки или в соответствии с требованиями законодательства.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Изменения в политике</h2>
                <p class="text-gray-700 mb-4">
                    Мы можем обновлять данную Политику конфиденциальности. Все изменения будут
                    опубликованы на этой странице с указанием даты последнего обновления.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Контактная информация</h2>
                <p class="text-gray-700 mb-4">
                    Если у вас есть вопросы о данной Политике конфиденциальности или обработке
                    ваших персональных данных, свяжитесь с нами:
                </p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700">Email: privacy@example.com</p>
                    <p class="text-gray-700">Телефон: +7 (xxx) xxx-xx-xx</p>
                    <p class="text-gray-700">Адрес: г. Москва, ул. Примерная, д. 1</p>
                </div>
            </section>
        </div>
    </div>
</div>

@include("inc.alert")
@endsection
