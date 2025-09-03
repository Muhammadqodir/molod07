@props(['image', 'category' => '', 'title' => '', 'description' => '', 'date' => '', 'link' => '#'])

<a href="{{ $link }}" class="block">
    <div class="bg-white rounded-2xl shadow-sm p-3 w-full cursor-pointer hover:shadow-lg transition">
        <div class="relative rounded-xl overflow-hidden mb-3">
            <img src="{{ $image }}" alt="" class="w-full h-[180px] object-cover">
            <button class="absolute top-2 right-2 bg-white p-2 rounded-full shadow">
                <x-lucide-share-2 class="w-4 h-4 text-gray-600" />
            </button>
        </div>

        @if($category)
        <div class="flex flex-wrap gap-2 mb-2">
            <span class="bg-[#E8E6FF] text-sm px-3 py-1 rounded-[10px]">{{ $category }}</span>
        </div>
        @endif

        <p class="text-[15px] font-medium text-gray-800 leading-tight mb-2 line-clamp-2">{{ $title }}</p>

        @if($description)
        <p class="text-sm text-gray-600 leading-tight mb-3 line-clamp-2">{{ $description }}</p>
        @endif

        <div class="flex items-center gap-2 text-gray-500">
            <x-lucide-calendar class="w-5 h-5" /> {{ $date }}
        </div>
    </div>
</a>
