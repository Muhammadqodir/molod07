@extends('layouts.sidebar-layout')

@section('title', 'Личные данные')

@section('content')

    <form action="{{ route('partner.profile.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h1 class="text-3xl">Личные данные</h1>

        <x-profile-photo-upload name="pic" />

        <hr class="mt-4">
        <!-- Личные данные -->
        <div class="space-y-4 mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">Личные данные</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-0">
                <x-input label="Название организации" name="org_name" placeholder="Укажите название организации"
                    value="{{ Auth::user()->partnersProfile->org_name }}" />
                <x-input label="Имя руководителя" name="person_name" placeholder="Укажите имя руководителя"
                    value="{{ Auth::user()->partnersProfile->person_name }}" />
                <x-input label="Фамилия руководителя" name="person_lname" placeholder="Укажите фамилию руководителя"
                    value="{{ Auth::user()->partnersProfile->person_lname }}" />
                <x-input label="Отчество руководителя" name="person_fname" placeholder="Укажите отчество руководителя"
                    value="{{ Auth::user()->partnersProfile->person_fname }}" :help="'не обязательно к заполнению'" />
            </div>
        </div>

        <hr class="mt-3">
        <!-- Контактные данные -->
        <div class="space-y-4 mt-5">
            <label class="text-md text-gray-800 font-medium">Контактные данные</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-0">
                <x-input label="Номер телефона" name="phone" placeholder="Укажите номер телефона"
                    value="{{ Auth::user()->partnersProfile->phone }}" />
                <x-input type="email" :disabled="true" label="E-mail" name="email" placeholder="Укажите e-mail"
                    value="{{ Auth::user()->email }}" />

                <x-input label="Населенный пункт" name="org_address" placeholder="Укажите населенный пункт"
                    value="{{ Auth::user()->partnersProfile->org_address }}" />

                <x-input label="Веб-сайт" name="web" placeholder="https://example.com" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->partnersProfile->web }}" />

                <x-input label="Telegram" name="telegram" placeholder="@" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->partnersProfile->telegram }}" />

                <x-input label="Vkontakte" name="vk" placeholder="id" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->partnersProfile->vk }}" />
            </div>
        </div>


        <hr class="mt-3">
        <!-- Об организации -->
        <div class="space-y-4 mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">О организации</label>
            <div class="grid grid-cols-1">
                <x-rich-text-area label="Описание" name="about"
                    value="{{ old('about', Auth::user()->partnersProfile->about ?? '') }}" placeholder="Напишите о себе"
                    :allow-images="true" />
            </div>
        </div>

        <x-button class="mt-7" type="submit">Сохранить</x-button>

    </form>

@endsection
