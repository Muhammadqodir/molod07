@extends('layouts.sidebar-layout')

@section('title', 'Комментарии')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Комментарии</h1>
        @if(request()->routeIs('admin.comments.requests'))
            <div class="text-sm text-gray-600">
                Ожидают модерации
            </div>
        @elseif(request()->routeIs('admin.comments.rejected'))
            <div class="text-sm text-gray-600">
                Отклоненные комментарии
            </div>
        @else
            <div class="text-sm text-gray-600">
                Одобренные комментарии
            </div>
        @endif
    </div>

    <!-- Status Filter Tabs -->
    {{-- <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.comments.index') }}"
                   class="@if(request()->routeIs('admin.comments.index')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 border-b-2 font-medium text-sm">
                    Одобренные
                    @if(isset($approvedCount))
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $approvedCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.comments.requests') }}"
                   class="@if(request()->routeIs('admin.comments.requests')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 border-b-2 font-medium text-sm">
                    На модерации
                    @if(isset($pendingCount))
                        <span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2.5 rounded-full text-xs">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.comments.rejected') }}"
                   class="@if(request()->routeIs('admin.comments.rejected')) border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-2 px-1 border-b-2 font-medium text-sm">
                    Отклоненные
                    @if(isset($rejectedCount))
                        <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">{{ $rejectedCount }}</span>
                    @endif
                </a>
            </nav>
        </div>
    </div> --}}

    <form method="GET" action="{{ route(Route::currentRouteName()) }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по содержимому комментария или имени пользователя" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($comments->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Комментарий</th>
                        <th class="text-left font-medium px-4 py-3">Пользователь</th>
                        <th class="text-left font-medium px-4 py-3">Объект</th>
                        <th class="text-left font-medium px-4 py-3">Дата</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($comments as $comment)
                        @php
                            /** @var \App\Models\Comment $comment */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="text-gray-800 font-medium line-clamp-2">
                                    {{ Str::limit($comment->content, 100) }}
                                </div>
                                @if($comment->parent_id)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Ответ на комментарий
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <x-lucide-user class="h-4 w-4 text-gray-600" />
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $comment->user->getFirstName() ?? 'Неизвестно' }}</div>
                                        <div class="text-xs text-gray-500">{{ $comment->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ class_basename($comment->commentable_type) }}
                                    </div>
                                    @if($comment->commentable)
                                        <div class="text-xs text-gray-500">
                                            {{ Str::limit($comment->commentable->title ?? $comment->commentable->name ?? 'ID: ' . $comment->commentable_id, 30) }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ $comment->created_at->format('d.m.Y') }}
                                    <br>
                                    <span class="text-xs">{{ $comment->created_at->format('H:i') }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <!-- Preview Button -->
                                    <a href="{{ route('admin.comments.preview', $comment->id) }}"
                                       title="Просмотр" target="_blank"
                                       class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <x-lucide-eye class="h-4 w-4" />
                                    </a>

                                    @if(request()->routeIs('admin.comments.requests'))
                                        <!-- Approve Button -->
                                        <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Одобрить"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                <x-lucide-check class="h-4 w-4" />
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <form method="POST" action="{{ route('admin.comments.reject', $comment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Отклонить"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                                <x-lucide-x class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif

                                    @if(request()->routeIs('admin.comments.rejected'))
                                        <!-- Approve Button for rejected comments -->
                                        <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Одобрить"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                <x-lucide-check class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('admin.comments.delete', $comment->id) }}" class="inline"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить этот комментарий?')">
                                        @csrf
                                        <button type="submit" title="Удалить"
                                                class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <x-lucide-trash-2 class="h-4 w-4" />
                                        </button>
                                    </form>

                                    <!-- Block User Button -->
                                    @if($comment->user && $comment->user->role !== 'admin')
                                        <form method="POST" action="{{ route('admin.comments.block', $comment->id) }}" class="inline"
                                              onsubmit="return confirm('Вы уверены, что хотите заблокировать этого пользователя?')">
                                            @csrf
                                            <button type="submit" title="Заблокировать пользователя"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <x-lucide-shield-ban class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($comments->hasPages())
            <div class="mt-6">
                {{ $comments->links() }}
            </div>
        @endif
    @endif
@endsection
