@props(['image', 'category' => '', 'title' => '', 'description' => '', 'length' => '', 'episodeNumbers' => '', 'link' => '#'])

<a href="{{ $link }}" class="block">
    <div class="bg-white rounded-2xl shadow-sm p-3 w-full cursor-pointer hover:shadow-lg transition">
        <div class="relative rounded-xl overflow-hidden mb-3">
            <img src="{{ $image }}" alt="" class="w-full h-[180px] object-cover">
            <button class="absolute top-2 right-2 bg-white p-2 rounded-full shadow">
                <x-lucide-share-2 class="w-4 h-4 text-gray-600" />
            </button>
            <!-- Play button overlay -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white/90 p-4 rounded-full shadow-lg">
                    <x-lucide-play class="w-6 h-6 text-primary fill-current" />
                </div>
            </div>
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

        <div class="flex items-center justify-between text-gray-500 text-sm">
            <div class="flex items-center gap-2">
                <x-lucide-clock class="w-4 h-4" />
                <span>{{ $length }}</span>
            </div>
            @if($episodeNumbers)
            <div class="flex items-center gap-2">
                <x-lucide-headphones class="w-4 h-4" />
                <span>{{ $episodeNumbers }} эп.</span>
            </div>
            @endif
        </div>
    </div>
</a>
