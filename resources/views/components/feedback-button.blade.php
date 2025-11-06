<!-- Floating Feedback Button -->
<div id="feedback-widget" class="fixed z-[9999999999]" style="bottom: 50px; right: 50px;">
    <!-- Floating Button -->
    <button id="feedback-button"
            class="bg-primary hover:bg-primary text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>

    <!-- Modal -->
    <div id="feedback-modal"
         class="fixed inset-0 z-[99999] flex items-center justify-center bg-black bg-opacity-50 p-4 hidden opacity-0 transition-opacity duration-300">

        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto transform transition-transform duration-300 scale-95">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Обратная связь</h3>
                <button id="close-modal" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="feedback-form" class="p-6 space-y-4">
                <!-- Guest fields (shown only for non-authenticated users) -->
                @guest
                <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Пожалуйста, укажите ваши контактные данные:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="feedback-guest-name" class="block text-sm font-medium text-gray-700 mb-2">
                                Ваше имя <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="feedback-guest-name"
                                name="guest_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Введите ваше имя"
                                required>
                        </div>
                        <div>
                            <label for="feedback-guest-email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                id="feedback-guest-email"
                                name="guest_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Введите ваш email"
                                required>
                        </div>
                    </div>
                </div>
                @endguest

                <!-- Subject -->
                <div>
                    <label for="feedback-subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Тема обращения <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="feedback-subject"
                        name="subject"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Кратко опишите суть проблемы"
                        required>
                </div>

                <!-- Message -->
                <div>
                    <label for="feedback-message" class="block text-sm font-medium text-gray-700 mb-2">
                        Сообщение <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="feedback-message"
                        name="message"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                        placeholder="Подробно опишите проблему или предложение..."
                        required></textarea>
                </div>

                <!-- Screenshot -->
                <div>
                    <label for="feedback-screenshot" class="block text-sm font-medium text-gray-700 mb-2">
                        Скриншот (необязательно)
                    </label>
                    <input
                        type="file"
                        id="feedback-screenshot"
                        name="screenshot"
                        accept="image/jpeg,image/png,image/jpg"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-primary file:text-white hover:file:bg-primary">
                    <p class="text-xs text-gray-500 mt-1">Поддерживаются форматы: JPEG, PNG, JPG (макс. 5MB)</p>
                </div>

                <!-- Preview -->
                <div id="image-preview" class="mt-4 hidden">
                    <img id="preview-image" alt="Preview" class="max-w-full h-32 object-cover rounded-lg border">
                    <button type="button" id="remove-file" class="text-red-500 text-sm mt-2 hover:text-red-700">
                        Удалить файл
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button
                        type="button"
                        id="cancel-feedback"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Отмена
                    </button>
                    <button
                        type="submit"
                        id="submit-feedback"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center space-x-2">
                        <span id="submit-text">Отправить</span>
                        <span id="loading-spinner" class="hidden">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackButton = document.getElementById('feedback-button');
    const feedbackModal = document.getElementById('feedback-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelButton = document.getElementById('cancel-feedback');
    const feedbackForm = document.getElementById('feedback-form');
    const fileInput = document.getElementById('feedback-screenshot');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    const removeFileButton = document.getElementById('remove-file');
    const submitButton = document.getElementById('submit-feedback');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');

    let isSubmitting = false;

    // Show modal
    function showModal() {
        feedbackModal.classList.remove('hidden');
        setTimeout(() => {
            feedbackModal.classList.remove('opacity-0');
            feedbackModal.querySelector('.bg-white').classList.remove('scale-95');
            feedbackModal.querySelector('.bg-white').classList.add('scale-100');
        }, 10);
    }

    // Hide modal
    function hideModal() {
        feedbackModal.classList.add('opacity-0');
        feedbackModal.querySelector('.bg-white').classList.remove('scale-100');
        feedbackModal.querySelector('.bg-white').classList.add('scale-95');
        setTimeout(() => {
            feedbackModal.classList.add('hidden');
        }, 300);

        // Reset form
        feedbackForm.reset();
        imagePreview.classList.add('hidden');
    }

    // Event listeners
    feedbackButton.addEventListener('click', showModal);
    closeModal.addEventListener('click', hideModal);
    cancelButton.addEventListener('click', hideModal);

    // Close modal when clicking outside
    feedbackModal.addEventListener('click', function(e) {
        if (e.target === feedbackModal) {
            hideModal();
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove file
    removeFileButton.addEventListener('click', function() {
        fileInput.value = '';
        imagePreview.classList.add('hidden');
    });

    // Form submission
    feedbackForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (isSubmitting) return;

        isSubmitting = true;
        submitButton.disabled = true;
        submitText.textContent = 'Отправка...';
        loadingSpinner.classList.remove('hidden');

        const formData = new FormData();
        formData.append('subject', document.getElementById('feedback-subject').value);
        formData.append('message', document.getElementById('feedback-message').value);

        // Add guest fields if they exist (for non-authenticated users)
        const guestName = document.getElementById('feedback-guest-name');
        const guestEmail = document.getElementById('feedback-guest-email');
        if (guestName && guestEmail) {
            formData.append('guest_name', guestName.value);
            formData.append('guest_email', guestEmail.value);
        }

        if (fileInput.files[0]) {
            formData.append('screenshot', fileInput.files[0]);
        }

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }

        try {
            const response = await fetch('{{ route("feedback.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const data = await response.json();

            if (data.success) {
                hideModal();
                showNotification(data.message, 'success');
            } else {
                showNotification('Произошла ошибка при отправке сообщения', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Произошла ошибка при отправке сообщения', 'error');
        } finally {
            isSubmitting = false;
            submitButton.disabled = false;
            submitText.textContent = 'Отправить';
            loadingSpinner.classList.add('hidden');
        }
    });

    // Show notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-[100px] right-6 p-4 rounded-lg shadow-lg z-[99999] transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove after 5 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
});
</script>
@endpush
