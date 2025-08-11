@extends('layouts.sidebar-layout')

@section('title', 'Личные данные')

@section('content')

    <form action="{{ route('admin.profile.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h1 class="text-3xl">Личные данные</h1>

        <x-profile-photo-upload name="pic" />

        <hr class="mt-4">
        <!-- Личные данные -->
        <div class="space-y-4 mt-5">
            <label class="text-md mt-5 text-gray-800 font-medium">Личные данные</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-0">
                <x-input label="Имя" name="name" placeholder="Укажите имя"
                    value="{{ Auth::user()->adminsProfile->name }}" />
                <x-input label="Фамилия" name="l_name" placeholder="Укажите фамилию"
                    value="{{ Auth::user()->adminsProfile->l_name }}" />
                <x-input label="Отчество" name="f_name" placeholder="Укажите отчество" :help="'не обязательно к заполнению'"
                    value="{{ Auth::user()->adminsProfile->f_name }}" />

                <x-input label="Номер телефона" name="phone" placeholder="Укажите номер телефона"
                    value="{{ Auth::user()->adminsProfile->phone }}" />
                <x-input type="email" :disabled="true" label="E-mail" name="email" placeholder="Укажите e-mail"
                    value="{{ Auth::user()->email }}" />
            </div>
        </div>

        <x-button class="mt-7" type="submit">Сохранить</x-button>

    </form>

@endsection
