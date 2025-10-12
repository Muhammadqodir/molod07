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
                      onclick="confirmDelete()"
                      class="!border-red-600 !text-red-600 hover:!bg-red-50 focus:!ring-red-500"
                      style="border-color: #DC2626 !important; color: #DC2626 !important;">
                Удалить профиль
            </x-button>
        </form>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Вы уверены, что хотите удалить свой профиль? Это действие нельзя отменить.')) {
                document.getElementById('deleteProfileForm').submit();
            }
        }
    </script>

@endsection
