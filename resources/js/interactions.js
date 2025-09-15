// Обработка комментариев, лайков и просмотров
document.addEventListener('DOMContentLoaded', function() {

    // Функция для показа уведомлений
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-24 right-6 z-50 max-w-sm w-full bg-white border border-gray-200 rounded-xl shadow-lg p-4 transition-all duration-300 transform translate-x-full`;

        const iconMap = {
            'success': '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            'error': '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            'warning': '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            'info': '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    ${iconMap[type]}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Анимация появления
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Автоматическое удаление через 5 секунд
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
    // Отслеживание просмотров
    function trackView(viewableType, viewableId) {
        fetch('/api/views/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                viewable_type: viewableType,
                viewable_id: viewableId
            })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновляем счетчик просмотров на странице
                const viewsCountElement = document.querySelector('.views-count');
                if (viewsCountElement) {
                    viewsCountElement.textContent = data.views_count;
                }
            }
        }).catch(error => console.error('Ошибка при отслеживании просмотра:', error));
    }

    // Обработка лайков
    function toggleLike(likeableType, likeableId, type) {
        if (!document.querySelector('meta[name="user-authenticated"]')) {
            showAuthModal('auth-modal');
            return;
        }

        fetch('/api/likes/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                likeable_type: likeableType,
                likeable_id: likeableId,
                type: type
            })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновляем счетчики лайков
                const likesButton = document.querySelector('.likes-button');
                const dislikesButton = document.querySelector('.dislikes-button');

                if (likesButton) {
                    const likesCount = likesButton.querySelector('.likes-count');
                    if (likesCount) likesCount.textContent = data.likes_count;

                    // Обновляем состояние кнопки лайка
                    if (data.user_liked) {
                        likesButton.classList.add('active');
                    } else {
                        likesButton.classList.remove('active');
                    }
                }

                if (dislikesButton) {
                    const dislikesCount = dislikesButton.querySelector('.dislikes-count');
                    if (dislikesCount) dislikesCount.textContent = data.dislikes_count;

                    // Обновляем состояние кнопки дизлайка
                    if (data.user_disliked) {
                        dislikesButton.classList.add('active');
                    } else {
                        dislikesButton.classList.remove('active');
                    }
                }
            }
        }).catch(error => {
            console.error('Ошибка при обработке лайка:', error);
            showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
        });
    }

    // Обработка комментариев
    function submitComment(form) {
        if (!document.querySelector('meta[name="user-authenticated"]')) {
            showAuthModal('auth-modal');
            return;
        }

        const formData = new FormData(form);
        const content = formData.get('content');
        const commentableType = form.dataset.commentableType;
        const commentableId = form.dataset.commentableId;
        const parentId = form.dataset.parentId;

        if (!content.trim()) {
            showNotification('Пожалуйста, введите текст комментария', 'warning');
            return;
        }

        fetch('/api/comments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                content: content,
                commentable_type: commentableType,
                commentable_id: commentableId,
                parent_id: parentId
            })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                // Очищаем форму
                form.reset();

                // Если это форма ответа, скрываем её
                if (form.classList.contains('reply-comment-form')) {
                    const replyContainer = form.closest('.reply-form');
                    if (replyContainer) {
                        replyContainer.classList.add('hidden');
                    }
                }

                // Добавляем новый комментарий в список
                loadComments(commentableType, commentableId);

                // Обновляем счетчик комментариев
                updateCommentsCount();

                showNotification('Комментарий успешно добавлен', 'success');
            } else {
                showNotification(data.message || 'Произошла ошибка при добавлении комментария', 'error');
            }
        }).catch(error => {
            console.error('Ошибка при добавлении комментария:', error);
            showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
        });
    }

    // Загрузка комментариев
    function loadComments(commentableType, commentableId) {
        fetch(`/api/comments?commentable_type=${commentableType}&commentable_id=${commentableId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const commentsContainer = document.querySelector('.comments-container');
            if (commentsContainer && data.success) {
                commentsContainer.innerHTML = data.html;
            }
        }).catch(error => console.error('Ошибка при загрузке комментариев:', error));
    }

    // Рендер комментария
    function renderComment(comment) {
        const userName = comment.user ? `${comment.user.name || ''} ${comment.user.l_name || ''}`.trim() : 'Аноним';
        const createdAt = new Date(comment.created_at).toLocaleDateString('ru-RU');

        let repliesHtml = '';
        if (comment.replies && comment.replies.length > 0) {
            comment.replies.forEach(reply => {
                const replyUserName = reply.user ? `${reply.user.name || ''} ${reply.user.l_name || ''}`.trim() : 'Аноним';
                const replyCreatedAt = new Date(reply.created_at).toLocaleDateString('ru-RU');

                repliesHtml += `
                    <div class="ml-8 mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-medium text-sm text-primary">${replyUserName}</span>
                            <span class="text-xs text-gray-500">${replyCreatedAt}</span>
                        </div>
                        <p class="text-sm text-gray-700">${reply.content}</p>
                    </div>
                `;
            });
        }

        return `
            <div class="comment-item mb-4 p-3 bg-white rounded-lg border border-gray-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="font-medium text-sm text-primary">${userName}</span>
                    <span class="text-xs text-gray-500">${createdAt}</span>
                </div>
                <p class="text-sm text-gray-700 mb-2">${comment.content}</p>
                ${repliesHtml}
            </div>
        `;
    }

    // Обновление счетчика комментариев
    function updateCommentsCount() {
        const commentsCountElement = document.querySelector('.comments-count');
        if (commentsCountElement) {
            const currentCount = parseInt(commentsCountElement.textContent) || 0;
            commentsCountElement.textContent = currentCount + 1;
        }
    }

    // Инициализация обработчиков событий

    // Обработчики для форм комментариев
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('comment-form') || e.target.classList.contains('reply-comment-form')) {
            e.preventDefault();
            submitComment(e.target);
        }
    });    // Обработчики для кнопок лайков
    document.addEventListener('click', function(e) {
        if (e.target.closest('.likes-button')) {
            e.preventDefault();
            const button = e.target.closest('.likes-button');
            const likeableType = button.dataset.likeableType;
            const likeableId = button.dataset.likeableId;
            toggleLike(likeableType, likeableId, 'like');
        }

        if (e.target.closest('.dislikes-button')) {
            e.preventDefault();
            const button = e.target.closest('.dislikes-button');
            const likeableType = button.dataset.likeableType;
            const likeableId = button.dataset.likeableId;
            toggleLike(likeableType, likeableId, 'dislike');
        }

        // Лайки для комментариев
        if (e.target.closest('.comment-like-button')) {
            e.preventDefault();
            const button = e.target.closest('.comment-like-button');
            const commentId = button.dataset.commentId;
            const type = button.dataset.type;
            toggleCommentLike(commentId, type);
        }

        if (e.target.closest('.comment-dislike-button')) {
            e.preventDefault();
            const button = e.target.closest('.comment-dislike-button');
            const commentId = button.dataset.commentId;
            const type = button.dataset.type;
            toggleCommentLike(commentId, type);
        }
    });

    // Автоматическое отслеживание просмотров
    const viewTracker = document.querySelector('[data-track-view]');
    if (viewTracker) {
        const viewableType = viewTracker.dataset.viewableType;
        const viewableId = viewTracker.dataset.viewableId;

        // Отслеживаем просмотр через 3 секунды после загрузки страницы
        setTimeout(() => {
            trackView(viewableType, viewableId);
        }, 3000);
    }

    // Загрузка существующих комментариев при загрузке страницы
    const commentsLoader = document.querySelector('[data-load-comments]');
    if (commentsLoader) {
        const commentableType = commentsLoader.dataset.commentableType;
        const commentableId = commentsLoader.dataset.commentableId;
        loadComments(commentableType, commentableId);
    }
});
