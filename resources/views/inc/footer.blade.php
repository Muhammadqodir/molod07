<footer class="bg-white border-t border-gray-100 text-gray-700 text-sm">
  <div class="max-w-screen-xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-10">
    {{-- Левая колонка --}}
    <div class="space-y-4">
      <x-logo />
      <p class="text-base">Приложение Минмолодёжи КБР</p>
      <div class="flex gap-4">
        <img src="/images/google-play.png" alt="Google Play" class="h-10">
        <img src="/images/app-store.png" alt="App Store" class="h-10">
      </div>
    </div>

    {{-- Центральная колонка --}}
    <div>
      <h3 class="font-semibold mb-4">Разделы сайта</h3>
      <div class="grid grid-cols-2 gap-y-2">
        <a href="#" class="hover:text-primary">Главная</a>
        <a href="#" class="hover:text-primary">Вакансии</a>
        <a href="#" class="hover:text-primary">Лента активности</a>
        <a href="#" class="hover:text-primary">Документы</a>
        <a href="#" class="hover:text-primary">Организаторы</a>
        <a href="#" class="hover:text-primary">О нас</a>
        <a href="#" class="hover:text-primary">Образование</a>
        <a href="#" class="hover:text-primary">Контакты</a>
      </div>
    </div>

    {{-- Правая колонка --}}
    <div class="space-y-4">
      <h3 class="font-semibold">Контакты</h3>
      <p>
        Пн–Пт: 10:00 – 18:00; Сб, Вс: Выходной<br>
        ООО «Название», УНП 111111111<br>
        220030, г. Нальчик, ул. Название, 5 (пом. 77)
      </p>
      <div class="flex flex-row gap-5">
        <div>
          <p class="font-medium">Социальные сети</p>
          <div class="flex gap-3">
            <x-icon-button icon="vk" />
            <x-icon-button icon="telega" />
          </div>
        </div>
        <div>
          <p class="font-medium">Техподдержка</p>
          <div class="flex gap-3">
            <x-icon-button icon="mail" />
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Нижняя линия --}}
  <div class="border-t border-gray-200 mt-6">
    <div
      class="max-w-screen-xl mx-auto px-6 py-4 flex flex-col md:flex-row justify-between text-gray-500 text-xs gap-2">
      <p>
        База данных о мероприятиях. Использование сайта означает согласие с
        <a href="#" class="text-primary hover:underline">Пользовательским соглашением</a>
        и
        <a href="#" class="text-primary hover:underline">Политикой конфиденциальности</a>.
      </p>
      <p class="text-right">Разработка AL-Focus Group</p>
    </div>
  </div>
</footer>