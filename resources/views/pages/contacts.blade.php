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

                <!-- Карта с точками молодежных центров -->
                <div class="rounded-xl shadow" style="height:500px;">
                    <div id="youth-centers-map" style="width:100%; height:100%;"></div>
                </div>
            </div>

            <!-- Соцсети -->
            <div class="mb-6 mt-6">
                <h2 class="text-2xl font-bold mb-6">
                    Подписывайтесь на наши соцсети, следите за новостями и мероприятиями
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="https://t.me/minmolkbr" target="_blank"
                        class="flex justify-between items-center bg-white rounded-xl shadow p-5 hover:shadow-md transition">
                        <span class="font-medium">Канал в Telegram</span>
                        <div class="rounded-xl bg-primary/10 p-3">
                            <img src="{{ asset('images/telega.svg') }}" alt="Telegram">
                        </div>
                    </a>
                    <a href="https://vk.com/minmol07" target="_blank"
                        class="flex justify-between items-center bg-white rounded-xl shadow p-5 hover:shadow-md transition">
                        <span class="font-medium">Группа в Vkontakte</span>
                        <div class="rounded-xl bg-primary/10 p-3">
                            <img src="{{ asset('images/vk.svg') }}" alt="Vkontakte">
                        </div>
                    </a>
                </div>
            </div>

            <!-- Молодежные центры -->
            <h2 class="text-2xl font-bold mb-6 mt-10">Молодежные центры КБР</h2>
            <div class="flex flex-col space-y-6 mb-8">
                <!-- МЦ: Министерство по делам молодежи КБР -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Министерство по делам молодежи КБР</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Министр:</span> Люев Азамат Хасейнович</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span></div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> пр. Кулиева, 12</div>
                    </div>
                </div>
                <!-- МЦ: Многофункциональный молодежный центр КБР -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Многофункциональный молодежный центр КБР</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Дзагаштов Азамат Мусарбиевич</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 (8662) 49-60-33</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> пр. Кулиева, 12</div>
                    </div>
                </div>
                <!-- МЦ: Молодежный центр Черекского района -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Молодежный центр Черекского района</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Мокаев Ислам Кемалович</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 918 728-55-55</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> ул. Зукаева 5</div>
                    </div>
                </div>
                <!-- МЦ: Молодежный центр Урванского района -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Молодежный центр Урванского района</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Хостов Темирлан Мухадинович</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 964 039-03-09</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> ул. Кабардинская 115</div>
                    </div>
                </div>
                <!-- МЦ: Молодежный центр Чегемского района -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Молодежный центр Чегемского района</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Хапова Фатима Аслановна</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 928 690-97-50</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> ул. Баксанское шоссе,3</div>
                    </div>
                </div>
                <!-- МЦ: Молодежный центр Лескенского района -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Молодежный центр Лескенского района</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Бижоев Азамат Анзорович</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 (965) 499-21-11</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> ул Хамгокова, 27</div>
                    </div>
                </div>
                <!-- МЦ: Молодежный центр Майского района -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Молодежный центр Майского района</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Урусова Екатерина Олеговна</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 988 929-36-08</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> ул Энгельса, 72</div>
                    </div>
                </div>
                <!-- МЦ: Молодежный центр Эльбрусского района -->
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <x-lucide-building class="w-7 h-7 text-primary" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Молодежный центр Эльбрусского района</h3>
                        </div>
                        <button class="text-primary font-semibold hover:underline">Подробнее</button>
                    </div>
                    <div x-show="open" class="mt-4 text-gray-700 text-base space-y-2">
                        <div class="flex items-center"><x-lucide-user class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Директор:</span> Этезов Аслан Рашидович</div>
                        <div class="flex items-center"><x-lucide-phone class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Телефон:</span> +7 (938) 079-05-75</div>
                        <div class="flex items-center"><x-lucide-map-pin class="w-5 h-5 mr-2 text-gray-400" /><span
                                class="font-medium">Адрес:</span> ул. Энеева, 7</div>
                    </div>
                </div>
            </div>

            <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
            <script>
                ymaps.ready(function() {
                    var map = new ymaps.Map('youth-centers-map', {
                        center: [43.484329, 43.612058], // Нальчик
                        zoom: 10,
                        controls: [] // убираем все элементы управления
                    });
                    var centers = [{
                            coords: [43.475283, 43.582404],
                            name: 'Министерство по делам молодежи КБР',
                            address: 'пр. Кулиева, 12',
                            info: 'Министр – Люев Азамат Хасейнович'
                        },
                        {
                            coords: [43.475283, 43.582404],
                            name: 'Многофункциональный молодежный центр КБР',
                            address: 'пр. Кулиева, 12',
                            info: 'Директор - Дзагаштов Азамат Мусарбиевич'
                        },
                        {
                            coords: [43.314542, 43.606443],
                            name: 'Молодежный центр Черекского района',
                            address: 'ул. Зукаева 5',
                            info: 'Директор - Мокаев Ислам Кемалович'
                        },
                        {
                            coords: [43.555950, 43.854009],
                            name: 'Молодежный центр Урванского района',
                            address: 'ул. Кабардинская 115',
                            info: 'Директор - Хостов Темирлан Мухадинович'
                        },
                        {
                            coords: [43.572542, 43.586716],
                            name: 'Молодежный центр Чегемского района',
                            address: 'ул. Баксанское шоссе,3',
                            info: 'Директор - Хапова Фатима Аслановна'
                        },
                        {
                            coords: [43.356842, 43.941074],
                            name: 'Молодежный центр Лескенского района',
                            address: 'ул Хамгокова, 27',
                            info: 'Директор - Бижоев Азамат Анзорович'
                        },
                        {
                            coords: [43.629207, 44.051100],
                            name: 'Молодежный центр Майского района',
                            address: 'ул Энгельса, 72',
                            info: 'Директор - Урусова Екатерина Олеговна'
                        },
                        {
                            coords: [43.386613, 42.917399],
                            name: 'Молодежный центр Эльбрусского района',
                            address: 'ул. Энеева, 7',
                            info: 'Директор - Этезов Аслан Рашидович'
                        }
                    ];
                    centers.forEach(function(center) {
                        var placemark = new ymaps.Placemark(center.coords, {
                            balloonContent: `<strong>${center.name}</strong><br>${center.info}<br>${center.address}`
                        }, {
                            preset: 'islands#blueIcon'
                        });
                        map.geoObjects.add(placemark);
                    });
                });
            </script>

        </div>
    </div>

    @include('inc.alert')
@endsection
