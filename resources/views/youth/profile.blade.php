@extends('layouts.sidebar-layout')

@section('title', 'Личные данные')

@section('content')

    <form action="{{ route('youth.profile.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h1 class="text-3xl">Личные данные</h1>

        <x-profile-photo-upload name="pic" />

        <hr class="mt-4">
        <!-- Личные данные -->
        <div class="space-y-4 mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">Личные данные</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-0">
                <x-input label="Имя" name="name" placeholder="Укажите имя"
                    value="{{ Auth::user()->youthProfile->name }}" />
                <x-input label="Фамилия" name="l_name" placeholder="Укажите фамилию"
                    value="{{ Auth::user()->youthProfile->l_name }}" />
                <x-input label="Отчество" name="f_name" placeholder="Укажите отчество" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->youthProfile->f_name }}" />

                <x-input type="date" label="Дата рождения" name="bday"
                    value="{{ Auth::user()->youthProfile->bday }}" />
                <x-select name="sex" label="Пол" placeholder="Виберите пол" :options="['male' => 'Мужской', 'female' => 'Женский']"
                    value="{{ Auth::user()->youthProfile->sex }}" />
            </div>
        </div>

        <hr class="mt-3">
        <!-- Контактные данные -->
        <div class="space-y-4 mt-5">
            <label class="text-md text-gray-800 font-medium">Контактные данные</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-0">
                <x-input label="Номер телефона" name="phone" placeholder="Укажите номер телефона"
                    value="{{ Auth::user()->youthProfile->phone }}" />
                <x-input type="email" :disabled="true" label="E-mail" name="email" placeholder="Укажите e-mail"
                    value="{{ Auth::user()->email }}" />
                <x-input label="Telegram" name="telegram" placeholder="@" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->youthProfile->telegram }}" />

                <x-input label="Vkontakte" name="vk" placeholder="id" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->youthProfile->vk }}" />
            </div>
        </div>

        <hr class="mt-3">
        <!-- Региональные данные -->
        <div class="space-y-4 mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">Региональные данные</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-0">
                <x-input label="Место проживания" placeholder="Укажите место проживания" name="address"
                    value="{{ Auth::user()->youthProfile->address }}" />
                <x-input label="Гражданство" placeholder="Укажите гражданство" name="citizenship" :help="'не обязательно к заполнению'"
                    value="{{ old('citizenship') }}" />
            </div>
        </div>

        <hr class="mt-3">
        <!-- О себе -->
        <div class="space-y-4 mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">О себе</label>
            <div class="grid grid-cols-1">
                <x-rich-text-area label="Описание" name="about"
                    value="{{ old('about', Auth::user()->youthProfile->about ?? '') }}" placeholder="Напишите о себе"
                    :allow-images="true" />
            </div>
        </div>

        <x-button class="mt-7" type="submit">Сохранить</x-button>

    </form>

    <!-- Delete Profile Button -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <form action="{{ route('youth.profile.delete') }}" method="post" id="deleteProfileForm">
            @csrf
            @method('DELETE')
            <x-button type="button"
                      variant="outline"
                      onclick="showDeleteDialog()"
                      class="!border-red-600 !text-red-600 hover:!bg-red-50 focus:!ring-red-500"
                      style="border-color: #DC2626 !important; color: #DC2626 !important;">
                Удалить профиль
            </x-button>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Удаление профиля</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Вы уверены, что хотите удалить свой профиль? Все ваши данные, включая участие в мероприятиях, комментарии и лайки будут безвозвратно удалены.
                    </p>
                    <p class="text-sm text-red-600 font-medium mt-2">
                        Это действие нельзя отменить!
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="confirmDelete"
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors duration-200">
                            Да, удалить профиль
                        </button>
                        <button id="cancelDelete"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteDialog() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideDeleteDialog() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Event listeners
        document.getElementById('confirmDelete').addEventListener('click', function() {
            document.getElementById('deleteProfileForm').submit();
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            hideDeleteDialog();
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteDialog();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                hideDeleteDialog();
            }
        });
    </script>

@endsection
