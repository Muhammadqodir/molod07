<section class="bg-gray-50 py-16 px-4">
    <!-- Right: Contacts Info -->
    <div class="max-w-screen-xl mx-auto space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800">Контакты</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm flex gap-2">
                <div class="p-4 h-[52px] bg-primary/10 rounded-xl">
                    <x-lucide-phone class="w-5 h-5 text-primary mb-2" />
                </div>
                <div class="flex justify-center flex-col">
                    <div class="font-medium">Телефон</div>
                    <div class="text-sm text-gray-500">+7 (8662) 49-60-33</div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm flex gap-2">
                <div class="p-4 h-[52px] bg-primary/10 rounded-xl">
                    <x-lucide-mail class="w-5 h-5 text-primary mb-2" />
                </div>
                <div class="flex justify-center flex-col">
                    <div class="font-medium">Почта</div>
                    <div class="text-sm text-gray-500">mmckbr@mail.ru</div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm flex gap-2">
                <div class="p-4 h-[52px] bg-primary/10 rounded-xl">
                    <x-lucide-map-pin class="w-5 h-5 text-primary mb-2" />
                </div>
                <div class="flex justify-center flex-col">
                    <div class="font-medium">Адрес</div>
                    <div class="text-sm text-gray-500">КБР, г. Нальчик, пр-т Кулиева, 12</div>
                </div>
            </div>
        </div>
        <div style="position:relative;overflow:hidden;" class="rounded-xl shadow mt-6">

            <!-- Карта с точками молодежных центров -->
            <div class="rounded-xl shadow" style="height:500px;">
                <div id="youth-centers-map" style="width:100%; height:100%;"></div>
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
</section>
