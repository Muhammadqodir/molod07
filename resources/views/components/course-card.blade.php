@props(['image', 'category' => '', 'title' => '', 'description' => '', 'length' => '', 'modules' => 0, 'link' => '#'])

<a href="{{ $link }}" class="block">
    <div class="bg-white rounded-2xl shadow-sm p-3 w-full cursor-pointer hover:shadow-lg transition">
        <div class="relative rounded-xl overflow-hidden mb-3">
            <img src="{{ $image }}" alt="" class="w-full h-[180px] object-cover">
            <button class="absolute top-2 right-2 bg-white p-2 rounded-full shadow">
                <x-lucide-share-2 class="w-4 h-4 text-gray-600" />
            </button>
        </div>

        <div class="flex flex-wrap gap-2 mb-2">
            @if($category)
                <span class="bg-blue-50 text-blue-700 text-sm px-3 py-1 rounded-[10px]">{{ $category }}</span>
            @endif
        </div>

        <div class="flex flex-wrap gap-2 mb-2">
            @if($length)
                <div class="flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-[10px]">
                    <x-lucide-clock class="w-4 h-4" />
                    <span class="text-sm">{{ $length }}</span>
                </div>
            @endif
            @if($modules > 0)
                <div class="flex items-center gap-1 px-3 py-1 bg-purple-50 text-purple-700 rounded-[10px]">
                    <x-lucide-layers class="w-4 h-4" />
                    <span class="text-sm">{{ $modules }} модул{{ $modules > 1 ? 'ей' : 'ь' }}</span>
                </div>
            @endif
        </div>

        <p class="text-[15px] font-medium text-gray-800 leading-tight mb-2 line-clamp-2">{{ $title }}</p>

        @if($description)
            <p class="text-sm text-gray-600 leading-tight line-clamp-2">{{ $description }}</p>
        @endif
    </div>
</a>
