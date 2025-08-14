<section class="bg-accentbg py-16">
  <div class="max-w-screen-xl mx-auto px-6">
    {{-- Заголовок --}}
    <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-10">
      О платформе
    </h2>

    {{-- Контент: фото + карточки --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

      {{-- Левая часть: фотографии --}}
      <div
        class="bg-[#4D88EF] rounded-2xl relative overflow-hidden md:col-span-6 cursor-pointer hover:bg-[#3570c9] transition"
        style="background-image: url('/images/ornament-0.png'); background-repeat: no-repeat; background-size: 100%;">
        <img src="/images/pair-users.png" alt="pair-users" class="w-full z-10">
      </div>

      {{-- Карточка: Обучение --}}
      <div
        class="bg-[#fff] rounded-2xl p-6 relative overflow-hidden md:col-span-3 flex items-end cursor-pointer hover:bg-gray-100 transition"
        style="background-image: url('/images/ornament-1.png'); background-repeat: no-repeat; background-size: 100%;">

        <x-lucide-arrow-up-right class="absolute top-4 right-4 w-5 h-5 text-gray-500" />
        <div class="z-10 mt-10">
          <h3 class="text-xl font-semibold mb-3">Обучение</h3>
          <p class="text-gray-700 leading-relaxed">
            Изучайте курсы, проходите тесты и слушайте подкасты по интересующим вас направлениям и применяйте новые
            знания
            в жизни
          </p>
        </div>
      </div>

      {{-- Карточка: Реализация --}}
      <div
        class="bg-white rounded-2xl p-6 relative overflow-hidden md:col-span-3 flex items-end cursor-pointer hover:bg-gray-100 transition">
        <x-lucide-arrow-up-right class="absolute top-4 right-4 w-5 h-5 text-gray-500" />
        <div class="z-10 mt-10">
          <h3 class="text-xl font-semibold mb-3">Реализация</h3>
          <p class="text-gray-700 leading-relaxed">
            Принимайте участие в мероприятиях, находите работу мечты, предлагайте свои инициативы и влияйте на будущее
            вашего региона
          </p>
        </div>
      </div>

    </div>
  </div>
</section>