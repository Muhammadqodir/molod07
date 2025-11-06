@extends('layouts.sidebar-layout')

@section('title', 'Просмотр обращения')

@section('content')
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.feedback.index') }}"
           class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
            <x-lucide-arrow-left class="h-5 w-5" />
        </a>
        <h1 class="text-3xl">Просмотр обращения</h1>
        <div class="ml-auto">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $feedback->status_color }}">
                {{ $feedback->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subject and Message -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $feedback->subject }}</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $feedback->message }}</p>
                </div>
            </div>

            <!-- Screenshot -->
            @if($feedback->screenshot)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Прикрепленный скриншот</h3>
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $feedback->screenshot) }}"
                             alt="Скриншот"
                             class="max-w-full h-auto rounded-lg border border-gray-200 cursor-pointer"
                             onclick="openImageModal(this.src)">
                        <p class="text-sm text-gray-500 mt-2">Нажмите на изображение для увеличения</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Информация об отправителе</h3>
                @if($feedback->user)
                    <div class="flex items-center gap-3 mb-4">
                        <x-profile-pic :user="$feedback->user" class="flex-none h-[60px] w-[60px] text-sm" />
                        <div>
                            <div class="font-medium text-gray-800">{{ $feedback->user->getFullName() }}</div>
                            <div class="text-gray-500 text-sm">{{ $feedback->user->email }}</div>
                            <div class="text-blue-600 text-xs">Зарегистрированный пользователь</div>
                            <div class="text-gray-500 text-xs">ID: {{ $feedback->user->getUserId() }}</div>
                        </div>
                    </div>
                @elseif($feedback->guest_name)
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-none h-[60px] w-[60px] bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">{{ $feedback->guest_name }}</div>
                            <div class="text-gray-500 text-sm">{{ $feedback->guest_email }}</div>
                            <div class="text-orange-600 text-xs">Гость</div>
                        </div>
                    </div>
                @else
                    <div class="text-gray-500 text-sm">Информация об отправителе недоступна</div>
                @endif

                @if($feedback->user)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Роль:</span>
                            <span class="font-medium">{{ ucfirst($feedback->user->role) }}</span>
                        </div>
                        @if($feedback->user->phone)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Телефон:</span>
                                <span class="font-medium">{{ $feedback->user->phone }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Date Info -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Дата и время</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Создано:</span>
                        <span class="font-medium">{{ $feedback->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($feedback->updated_at != $feedback->created_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Обновлено:</span>
                            <span class="font-medium">{{ $feedback->updated_at->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Прошло:</span>
                        <span class="font-medium">{{ $feedback->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Management -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Управление статусом</h3>
                <form method="POST" action="{{ route('admin.feedback.update-status', $feedback) }}">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Статус</label>
                            <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="new" {{ $feedback->status === 'new' ? 'selected' : '' }}>Новое</option>
                                <option value="in_progress" {{ $feedback->status === 'in_progress' ? 'selected' : '' }}>В работе</option>
                                <option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>Решено</option>
                                <option value="closed" {{ $feedback->status === 'closed' ? 'selected' : '' }}>Закрыто</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                            Обновить статус
                        </button>
                    </div>
                </form>
            </div>

            <!-- Actions -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Действия</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.feedback.destroy', $feedback) }}"
                          onsubmit="return confirm('Вы уверены, что хотите удалить это обращение? Это действие нельзя отменить.')"
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Удалить обращение
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black bg-opacity-80" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-[90vh] p-4">
            <img id="modal-image" src="" alt="Enlarged screenshot" class="max-w-full max-h-full object-contain">
        </div>
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors">
            <x-lucide-x class="w-8 h-8" />
        </button>
    </div>

@endsection

@push('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modal-image').src = src;
    document.getElementById('image-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('image-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
