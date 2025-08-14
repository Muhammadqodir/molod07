<div class="bg-white m-auto rounded-2xl shadow-xl flex w-full max-w-4xl overflow-hidden">
    <!-- Left Side (image) -->
    <div class="hidden md:block w-1/2 relative bg-[#e5efff]">
        <img src="{{ asset('images/partner_reg.png') }}" alt="Register Illustration" class="inset-0 w-full object-cover" />
    </div>

    <!-- Right Side (form) -->
    <div class="w-full md:w-1/2 p-8 md:p-12">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('main') }}" class="text-sm text-gray-500 hover:text-gray-700">Назад</a>
            <span class="text-sm text-primary">Регистрация для партера</span>
        </div>

        <h2 class="text-3xl font-semibold text-gray-800 mb-2">Регистрация</h2>
        <p class="text-sm text-gray-500 mb-6">Уже есть аккаунт? <a href="{{ route('login') }}"
                class="text-[#1d4ed8] hover:underline">Войти</a>
        </p>

        <form method="POST" action="{{ route('partner.reg.post') }}">
            @csrf
            <x-step-form :steps="3" submit="Зарегистрироваться">
                <div x-show="step === 1">
                    <x-input name="org_name" type="text" label="Название организации"
                        placeholder="Укажите название организации" />
                    <x-input name="person_name" type="text" label="Имя руководителя"
                        placeholder="Введите имя руководителя" />
                    <x-input name="person_lname" type="text" label="Фамилия руководителя"
                        placeholder="Введите фамилию руководителя" />
                </div>

                <div x-show="step === 2">
                    <x-input name="address" type="text" label="Населенный пункт"
                        placeholder="Введите населенный пункт" />
                    <x-input name="email" type="email" label="E-mail" placeholder="Укажите e-mail" />
                    <x-input name="phone" type="tel" label="Телефон" placeholder="+7(999)999-99-99" />
                </div>

                <div x-show="step === 3">
                    <x-input name="password" type="password" label="Пароль" placeholder="Придумайте пароль"
                        help="Пароль должен содержать и латиницу, и цифры" />
                    <x-input name="password_confirmation" type="password" label="Подтверждение пароля"
                        placeholder="Повторите пароль" />
                </div>
            </x-step-form>
        </form>

    </div>
</div>

@if ($errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
        class="fixed top-6 right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <strong>Ошибка!</strong>
        <ul class="text-sm mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
