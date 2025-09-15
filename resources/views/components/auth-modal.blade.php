@props([
    'id' => 'auth-modal',
    'title' => 'Необходима авторизация',
    'message' => 'Для выполнения этого действия необходимо войти в систему',
    'loginUrl' => route('login'),
    'registerUrl' => route('youth.reg')
])

<div id="{{ $id }}" class="fixed inset-0 z-[9999999999999] hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6 relative">
        <!-- Кнопка закрытия -->
        <button
            class="absolute top-4 right-4 p-2 hover:bg-gray-100 rounded-xl transition-colors"
            onclick="closeAuthModal('{{ $id }}')">
            <x-lucide-x class="w-5 h-5 text-gray-500" />
        </button>

        <!-- Иконка -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <x-lucide-lock class="w-8 h-8 text-blue-600" />
            </div>
        </div>

        <!-- Заголовок -->
        <h3 class="text-xl font-semibold text-center text-gray-800 mb-2">
            {{ $title }}
        </h3>

        <!-- Сообщение -->
        <p class="text-gray-600 text-center mb-6">
            {{ $message }}
        </p>

        <!-- Кнопки действий -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ $loginUrl }}"
               class="flex-1 bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-xl text-center transition-colors">
                Войти
            </a>
            <a href="{{ $registerUrl }}"
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl text-center transition-colors">
                Регистрация
            </a>
        </div>

        <!-- Закрыть -->
        <button
            onclick="closeAuthModal('{{ $id }}')"
            class="w-full mt-3 text-gray-500 hover:text-gray-700 text-sm font-medium py-2 transition-colors">
            Закрыть
        </button>
    </div>
</div>

<script>
function showAuthModal(modalId = '{{ $id }}') {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeAuthModal(modalId = '{{ $id }}') {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Закрытие по клику на фон
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('{{ $id }}');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAuthModal('{{ $id }}');
            }
        });
    }
});
</script>
