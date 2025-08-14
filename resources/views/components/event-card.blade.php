@props([
  'image',
  'tags' => [],
  'points' => 0,
  'title' => '',
  'location' => '',
  'date' => '',
])

<div class="bg-white rounded-2xl shadow p-3 w-full">
  <div class="relative rounded-xl overflow-hidden mb-3">
    <img src="{{ $image }}" alt="" class="w-full h-[180px] object-cover">
    <button class="absolute top-2 right-2 bg-white p-2 rounded-full shadow">
      <x-lucide-share-2 class="w-4 h-4 text-gray-600" />
    </button>
  </div>

  <div class="flex flex-wrap gap-2 mb-2">
    @foreach ($tags as $tag)
    <span class="bg-[#E8E6FF] text-sm px-3 py-1 rounded-[10px]">{{ $tag }}</span>
  @endforeach
  </div>

  <div class="flex flex-wrap gap-2 mb-2">
    <div class="flex items-center gap-1 px-3 py-1 bg-[#ECFFB5] rounded-[10px]">
      <x-lucide-coins class="w-4 h-4 text-green-600" />
      <span class="text-sm">{{ $points }}</span>
    </div>
  </div>

  <p class="text-[15px] font-medium text-gray-800 leading-tight mb-2 line-clamp-2">{{ $title }}</p>

  <div class="flex items-center gap-2 mt-5 text-gray-500">
    <x-lucide-map-pin class="w-5 h-5" /> {{ $location }}
  </div>
  <div class="flex items-center gap-2 text-gray-500">
    <x-lucide-calendar class="w-5 h-5" /> {{ $date }}
  </div>
</div>