@extends('layouts.sidebar-layout')

@section('title', 'Просмотр комментария')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <x-lucide-arrow-left class="h-5 w-5 mr-2" />
                Назад
            </a>
            <h1 class="text-3xl">Просмотр комментария</h1>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <!-- User Info -->
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                <x-lucide-user class="h-6 w-6 text-gray-600" />
            </div>
            <div>
                <h3 class="font-medium text-gray-900">{{ $comment->user->name ?? 'Неизвестный пользователь' }}</h3>
                <p class="text-sm text-gray-500">{{ $comment->user->email ?? 'Email не указан' }}</p>
                <p class="text-xs text-gray-400">{{ $comment->created_at->format('d.m.Y в H:i') }}</p>
            </div>
        </div>

        <!-- Comment Content -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-900 mb-2">Содержимое комментария:</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $comment->content }}</p>
            </div>
        </div>

        <!-- Parent Comment (if reply) -->
        @if($comment->parent)
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-2">Ответ на комментарий:</h4>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-2 mb-2">
                        <x-lucide-user class="h-4 w-4 text-blue-600" />
                        <span class="text-sm font-medium text-blue-800">{{ $comment->parent->user->name ?? 'Неизвестный пользователь' }}</span>
                        <span class="text-xs text-blue-600">{{ $comment->parent->created_at->format('d.m.Y в H:i') }}</span>
                    </div>
                    <p class="text-blue-800">{{ Str::limit($comment->parent->content, 200) }}</p>
                </div>
            </div>
        @endif

        <!-- Related Object Info -->
        @if($comment->commentable)
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-2">Объект комментирования:</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ class_basename($comment->commentable_type) }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $comment->commentable->title ?? $comment->commentable->name ?? 'ID: ' . $comment->commentable_id }}
                            </p>
                        </div>
                        @if(method_exists($comment->commentable, 'getRouteKey'))
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">
                                Перейти к объекту
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Replies -->
        @if($comment->replies->count() > 0)
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-2">Ответы ({{ $comment->replies->count() }}):</h4>
                <div class="space-y-3">
                    @foreach($comment->replies as $reply)
                        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-gray-300">
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-user class="h-4 w-4 text-gray-600" />
                                <span class="text-sm font-medium text-gray-800">{{ $reply->user->name ?? 'Неизвестный пользователь' }}</span>
                                <span class="text-xs text-gray-500">{{ $reply->created_at->format('d.m.Y в H:i') }}</span>
                            </div>
                            <p class="text-gray-700">{{ $reply->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Status Info -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-900 mb-2">Информация о статусе:</h4>
            <div class="flex items-center gap-4">
                @if(isset($comment->status))
                    @if($comment->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                            <x-lucide-check class="h-4 w-4 mr-1" />
                            Одобрен
                        </span>
                    @elseif($comment->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                            <x-lucide-clock class="h-4 w-4 mr-1" />
                            На модерации
                        </span>
                    @elseif($comment->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                            <x-lucide-x class="h-4 w-4 mr-1" />
                            Отклонен
                        </span>
                    @endif
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                        Статус не определен
                    </span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
            @if(!isset($comment->status) || $comment->status !== 'approved')
                <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-lucide-check class="h-4 w-4 mr-2" />
                        Одобрить
                    </button>
                </form>
            @endif

            @if(!isset($comment->status) || $comment->status !== 'rejected')
                <form method="POST" action="{{ route('admin.comments.reject', $comment->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-lucide-x class="h-4 w-4 mr-2" />
                        Отклонить
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.comments.delete', $comment->id) }}" class="inline"
                  onsubmit="return confirm('Вы уверены, что хотите удалить этот комментарий? Это действие необратимо.')">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <x-lucide-trash-2 class="h-4 w-4 mr-2" />
                    Удалить
                </button>
            </form>

            @if($comment->user && $comment->user->role !== 'admin')
                <form method="POST" action="{{ route('admin.comments.block', $comment->id) }}" class="inline"
                      onsubmit="return confirm('Вы уверены, что хотите заблокировать пользователя {{ $comment->user->name }}?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <x-lucide-shield-ban class="h-4 w-4 mr-2" />
                        Заблокировать пользователя
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
