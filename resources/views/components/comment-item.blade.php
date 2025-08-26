@props([
    // Основные
    'avatar'   => null,
    'author'   => 'Имя Фамилия',
    'time'     => '10 дней назад',
    'body'     => '',
    // Действия
    'likes'    => 1,
    'dislikes' => 0,
    'likeUrl'  => '#',
    'dislikeUrl' => '#',
    'replyUrl' => '#',      // если null — не показываем «Ответить»
    // Вложенный комментарий
    'isReply'  => false,
])

<article class="{{ $isReply ? 'relative pl-12' : '' }}" @if($isReply) style="padding-left:50px;" @endif>
    {{-- скобка слева для вложенного коммента --}}
    @if($isReply)
        <div class="absolute left-0 top-8 h-[calc(100%-2rem)] w-4">
            <x-lucide-corner-down-right class="w-8 h-8 text-gray-300" stroke-width="1" />
        </div>
    @endif

    <div class="rounded-2xl bg-white shadow {{ $isReply ? 'px-4 py-4' : 'px-4 py-4' }}">
        {{-- Шапка --}}
        <header class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <img
                    src="{{ $avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($author).'&background=E5EDFF&color=1F3AA9' }}"
                    alt="{{ $author }}"
                    class="h-10 w-10 rounded-xl object-cover"
                >
                <div class="leading-tight">
                    @if($isReply)
                        <div class="text-xs text-gray-500">Ответ</div>
                    @endif
                    <div class="text-[15px] font-medium text-gray-900">{{ $author }}</div>
                </div>
            </div>

            <div class="text-sm text-gray-500">{{ $time }}</div>
        </header>

        {{-- Текст --}}
        <div class="mt-3 text-[15px] leading-6 text-gray-800">
            {!! nl2br(e($body)) !!}
        </div>

        {{-- Низ: слева «Ответить», справа лайки/дизлайки --}}
        {{-- <footer class="mt-4 flex items-center justify-between">
            <div>
                @if($replyUrl)
                    <a href="{{ $replyUrl }}"
                       class="text-[15px] text-primary hover:underline">
                        Ответить
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-6 text-gray-500">
                <a href="{{ $likeUrl }}" class="inline-flex items-center gap-2 hover:text-primary transition-colors">
                    <x-lucide-thumbs-up class="w-5 h-5" />
                    <span class="text-[15px]">{{ $likes }}</span>
                </a>
                <a href="{{ $dislikeUrl }}" class="inline-flex items-center gap-2 hover:text-primary transition-colors">
                    <x-lucide-thumbs-down class="w-5 h-5" />
                    <span class="text-[15px]">{{ $dislikes }}</span>
                </a>
            </div>
        </footer> --}}
    </div>
</article>
