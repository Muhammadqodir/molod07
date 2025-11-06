@extends('layouts.sidebar-layout')

@section('title', 'Обратная связь')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Обратная связь</h1>
        <div class="text-sm text-gray-600">
            Всего: {{ $feedback->total() }}
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.feedback.index') }}" class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <x-search-input name="q" placeholder="Поиск по теме или сообщению" :value="old('q', request('q'))" />
            </div>

            <!-- Status Filter -->
            <div class="sm:w-48">
                <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        onchange="this.form.submit()">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Все статусы</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Новые</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>В работе</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Решено</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Закрыто</option>
                </select>
            </div>
        </div>
    </form>

    @if ($feedback->isEmpty())
        <x-empty title="Обращений пока нет." />
    @else
        <!-- Bulk Actions -->
        <form id="bulk-form" method="POST" action="{{ route('admin.feedback.bulk-action') }}" class="mb-4">
            @csrf
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="select-all" class="text-sm text-gray-700">Выбрать все</label>
                </div>
                <select name="action" class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                    <option value="">Выберите действие</option>
                    <option value="mark_resolved">Отметить как решенные</option>
                    <option value="mark_closed">Отметить как закрытые</option>
                    <option value="delete">Удалить</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 disabled:opacity-50">
                    Применить
                </button>
                <span id="selected-count" class="text-sm text-gray-600"></span>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3 w-8">
                            <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                        </th>
                        <th class="text-left font-medium px-4 py-3">Пользователь</th>
                        <th class="text-left font-medium px-4 py-3">Тема</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Дата</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedback as $item)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <input type="checkbox" name="feedback_ids[]" value="{{ $item->id }}"
                                       class="rounded border-gray-300 text-primary focus:ring-primary feedback-checkbox">
                            </td>
                            <td class="px-4 py-4">
                                @if($item->user)
                                    <div class="flex items-center gap-3">
                                        <x-profile-pic :user="$item->user" class="flex-none h-[40px] w-[40px] text-xs" />
                                        <div>
                                            <div class="text-gray-800 font-medium">{{ $item->user->getFullName() }}</div>
                                            <div class="text-gray-500 text-xs">{{ $item->user->email }}</div>
                                            <div class="text-blue-600 text-xs">Зарегистрированный</div>
                                        </div>
                                    </div>
                                @elseif($item->guest_name)
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none h-[40px] w-[40px] bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xs">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-gray-800 font-medium">{{ $item->guest_name }}</div>
                                            <div class="text-gray-500 text-xs">{{ $item->guest_email }}</div>
                                            <div class="text-orange-600 text-xs">Гость</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500">Пользователь удален</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="max-w-xs">
                                    <div class="font-medium text-gray-800 truncate">{{ $item->subject }}</div>
                                    <div class="text-gray-500 text-xs truncate mt-1">{{ Str::limit($item->message, 50) }}</div>
                                    @if($item->screenshot)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                                <x-lucide-image class="w-3 h-3" />
                                                Скриншот
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status_color }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-gray-600">
                                <div>{{ $item->created_at->format('d.m.Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.feedback.show', $item) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.feedback.destroy', $item) }}"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить это обращение?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Показано {{ $feedback->firstItem() }}–{{ $feedback->lastItem() }} из {{ $feedback->total() }}
            </div>
            {{ $feedback->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
        </div>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const feedbackCheckboxes = document.querySelectorAll('.feedback-checkbox');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    // Handle select all
    selectAllCheckbox.addEventListener('change', function() {
        feedbackCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Handle individual checkboxes
    feedbackCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateSelectedCount();
        });
    });

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < feedbackCheckboxes.length;
        selectAllCheckbox.checked = checkedBoxes.length === feedbackCheckboxes.length;
    }

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
        const count = checkedBoxes.length;
        selectedCount.textContent = count > 0 ? `Выбрано: ${count}` : '';
    }

    // Handle bulk form submission
    bulkForm.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
        const action = document.querySelector('select[name="action"]').value;

        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Выберите хотя бы одно обращение');
            return;
        }

        if (!action) {
            e.preventDefault();
            alert('Выберите действие');
            return;
        }

        if (action === 'delete') {
            if (!confirm(`Вы уверены, что хотите удалить ${checkedBoxes.length} обращений?`)) {
                e.preventDefault();
                return;
            }
        }
    });
});
</script>
@endpush
