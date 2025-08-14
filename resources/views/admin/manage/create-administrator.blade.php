@extends('layouts.sidebar-layout')

@section('title', 'Создать админимстратора')

@section('content')

    <form action="{{ route('admin.manage.administrators.create.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h1 class="text-3xl">Создать админимстратора</h1>

        <x-profile-photo-upload name="pic" />

        <hr class="mt-4">
        <!-- Личные данные -->
        <div class="mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">Личные данные</label>
            <div class="flex mt-3 flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-input label="Имя" name="name" placeholder="Укажите имя" />
                </div>
                <div class="flex-1">
                    <x-input label="Фамилия" name="l_name" placeholder="Укажите фамилию" />
                </div>
                <div class="flex-1">
                    <x-input label="Отчество" name="f_name" placeholder="Укажите отчество" :help="'не обязательно к заполнению'" />
                </div>
            </div>

            <div class="mt-0">
                <x-multi-choice name="permissions" :options="$permissions" :value="old('permissions', '[]')" :multiple="true" title="Привелегии"
                    hint="Выберите привелегии для управления блоками" />
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4 mt-4">
                <div class="flex-1">
                    <x-input type="tel" label="Номер телефона" name="phone" placeholder="Укажите номер телефона" />
                </div>
                <div class="flex-1">
                    <x-input type="email" label="E-mail" name="email" placeholder="Укажите e-mail" />
                </div>
                <div class="flex-1">
                    <x-input type="password" label="Пароль" name="password" placeholder="Придумайте пароль" />
                </div>
            </div>
        </div>

        <x-button class="mt-7" type="submit">Создать</x-button>

    </form>

@endsection
