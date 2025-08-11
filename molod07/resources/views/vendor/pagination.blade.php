@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center space-x-1 select-none">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span
                class="flex items-center justify-center w-10 h-10 rounded-full text-gray-400 cursor-not-allowed">
                <x-lucide-chevron-left class="w-6 h-6" />
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="flex items-center justify-center w-10 h-10 rounded-full bg-white hover:bg-gray-50 text-gray-700">
                <x-lucide-chevron-left class="w-6 h-6" />
            </a>
        @endif

        {{-- Numbers / separators --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="flex items-center justify-center w-10 h-10 rounded-full text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-blue-600 font-medium">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-transparent hover:bg-gray-50 text-gray-700">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-50 text-gray-700">
                <x-lucide-chevron-right class="w-6 h-6" />
            </a>
        @else
            <span
                class="flex items-center justify-center w-10 h-10 rounded-full text-gray-400 cursor-not-allowed">
                <x-lucide-chevron-right class="w-6 h-6" />
            </span>
        @endif
    </nav>
@endif
