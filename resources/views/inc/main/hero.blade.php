<section class="bg-white">
  
  {{-- <div class="text-center px-4 max-w-4xl mx-auto relative mt-20">
    <h1 class="text-3xl md:text-[60px] font-light leading-tight text-gray-800">
      ПЛАТФОРМА <span class="inline-block align-middle">🎓</span>,
      ГДЕ СОБРАНЫ ВСЕ ТВОИ
    </h1>

    <div
      class="mt-1 inline-block px-[12px] py-[12px] bg-[#5887F4] text-white text-3xl md:text-[60px] rounded-xl tracking-wide">
      ВОЗМОЖНОСТИ
    </div>
    
    <div class="absolute left-38 top-23 hidden md:block">
      <img src="/images/text-decor.svg" class="w-8 h-8" alt="">
    </div>
  </div> --}}

  <img src="/images/title.png" class="max-h-[250px] m-auto md:mt-[50px] md:mb-[80px] mt-[90px] mb-[100px]" alt="">

  {{-- Горизонтальный скролл --}}
  <img src="/images/hero-decor.png" class="absolute top-[350px] right-[30px] md:top-[415px] md:right-[100px] z-[9] w-[60px] md:w-[90px]">

  <img src="/images/hero-decor-1.png" class="absolute top-[350px] left-[30px] md:top-[400px] md:left-[100px] z-[9] w-[120px] md:w-[200px]">

  <div x-data="scrollGallery()" x-init="start()" class="relative w-full overflow-hidden mt-20 mb-20">
    <div x-ref="container" class="flex gap-6 overflow-x-scroll slider-scroll-hide">

      @foreach (['1.png', '2.png', '3.png', '4.png', '5.png', '6.png', '7.png', '8.png', '9.png'] as $photo)
      <div class="min-w-[300px] h-[220px] rounded-xl overflow-hidden shadow">
      <img src="/images/hero-scroll/{{ $photo }}" alt="" class="w-full h-full object-cover">
      </div>
    @endforeach

    </div>
  </div>
</section>