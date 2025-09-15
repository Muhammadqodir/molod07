@props([
    // Основные
    'avatar'   => null,
    'author'   => 'Имя Фамилия',
    'time'     => '10 дней назад',
    'body'     => '',
    'commentId' => null,
    // Действия
    'likes'    => 0,
    'dislikes' => 0,
    'likeUrl'  => '#',
    'dislikeUrl' => '#',
    'replyUrl' => '#',      // если null — не показываем «Ответить»
    // Вложенный комментарий
    'isReply'  => false,
    // Для работы с формами
    'commentableType' => '',
    'commentableId' => '',
])

<article class="{{ $isReply ? 'relative pl-12' : '' }} mb-4" @if($isReply) style="padding-left:50px;" @endif>
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
                @if($replyUrl && !$isReply)
                    <button
                        onclick="toggleReplyForm({{ $commentId ?? 'null' }})"
                        class="text-[15px] text-primary hover:underline font-medium transition-colors">
                        Ответить
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-6 text-gray-500">
                <button
                    class="comment-like-button inline-flex items-center gap-2 hover:text-green-600 transition-colors"
                    data-comment-id="{{ $commentId ?? '' }}"
                    data-type="like">
                    <x-lucide-thumbs-up class="w-5 h-5" />
                    <span class="text-[15px] like-count">{{ $likes }}</span>
                </button>
                <button
                    class="comment-dislike-button inline-flex items-center gap-2 hover:text-red-600 transition-colors"
                    data-comment-id="{{ $commentId ?? '' }}"
                    data-type="dislike">
                    <x-lucide-thumbs-down class="w-5 h-5" />
                    <span class="text-[15px] dislike-count">{{ $dislikes }}</span>
                </button>
            </div>
        </footer> --}}

        {{-- Форма ответа (скрыта по умолчанию) --}}
        @if(!$isReply)
            <div id="reply-form-{{ $commentId ?? 0 }}" class="reply-form mt-4 hidden">
                <x-comment-form
                    :commentable-type="$commentableType ?? ''"
                    :commentable-id="$commentableId ?? ''"
                    :parent-id="$commentId ?? null"
                    placeholder="Введите ответ на комментарий"
                    class="reply-comment-form" />
            </div>
        @endif
    </div>
</article>
