@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-6xl mx-auto px-6">

            <!-- Заголовок -->
            <h1 class="text-3xl font-bold mb-8">Контакты</h1>

            <!-- Основные контакты -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2 mt-4">
                <!-- Главный офис -->
                <div class="bg-white rounded-xl shadow p-6 space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-primary/10 rounded-lg">
                            <x-lucide-map-pin class="w-6 h-6 text-primary" />
                        </div>
                        <h2 class="text-lg font-semibold">Главный офис</h2>
                    </div>
                    <div class="text-gray-600 text-sm">
                        <p><span class="font-medium">Адрес</span><br>Нальчик, Большой Трёхсвятительский переулок, дом 2/1
                            строение 2</p>
                        <p class="mt-3"><span class="font-medium">Режим работы</span><br>
                            Пн - Пт : с 9:00 до 18:00<br>
                            Сб - Вс : выходной
                        </p>
                    </div>
                </div>

                <!-- Справочная служба -->
                <div class="bg-white rounded-xl shadow p-6 space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-primary/10 rounded-lg">
                            <x-lucide-phone class="w-6 h-6  text-primary" />
                        </div>
                        <h2 class="text-lg font-semibold">Справочная служба</h2>
                    </div>
                    <div class="text-gray-600 text-sm">
                        <p><span class="font-medium">Телефон</span><br>
                            01. 8 (800) 000 00 00<br>
                            02. 8 (800) 000 00 00
                        </p>
                        <p class="mt-3"><span class="font-medium">Факс</span><br>+7 499 000 00 00</p>
                    </div>
                </div>
            </div>

            <div style="position:relative;overflow:hidden;" class="rounded-xl shadow mt-6">
                <iframe
                    src="https://yandex.uz/map-widget/v1/?ll=43.612058%2C43.484329&mode=search&ol=geo&ouri=ymapsbm1%3A%2F%2Fgeo%3Fdata%3DCgg1MzExOTY0MxJa0KDQvtGB0YHQuNGPLCDQmtCw0LHQsNGA0LTQuNC90L4t0JHQsNC70LrQsNGA0YHQutCw0Y8g0KDQtdGB0L_Rg9Cx0LvQuNC60LAsINCd0LDQu9GM0YfQuNC6IgoNpm0uQhXo8C1C&z=15.74"
                    width="100%" class="rounded-xl shadow" height="400" frameborder="1" allowfullscreen="true"
                    style="position:relative;"></iframe>
            </div>

            <!-- Соцсети -->
            <div class="mb-6 mt-6">
                <h2 class="text-2xl font-bold mb-6">
                    Подписывайтесь на наши соцсети, следите за новостями и мероприятиями
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#"
                        class="flex justify-between items-center bg-white rounded-xl shadow p-5 hover:shadow-md transition">
                        <span class="font-medium">Канал в Telegram</span>
                        <div class="rounded-xl bg-primary/10 p-3">
                            <img src="{{ asset('images/telega.svg') }}" alt="Telegram">
                        </div>
                    </a>
                    <a href="#"
                        class="flex justify-between items-center bg-white rounded-xl shadow p-5 hover:shadow-md transition">
                        <span class="font-medium">Группа в Vkontakte</span>
                        <div class="rounded-xl bg-primary/10 p-3">
                            <img src="{{ asset('images/vk.svg') }}" alt="Vkontakte">
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    @include('inc.alert')
@endsection
