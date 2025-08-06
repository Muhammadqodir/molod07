<div class="bg-white m-auto rounded-2xl shadow-xl flex w-full max-w-4xl overflow-hidden">
    <!-- Left Side (image) -->
    <div class="hidden md:block w-1/2 relative bg-[#e5efff]">
        <img src="/images/usr-reg.png" alt="Register Illustration" class="inset-0 w-full object-cover" />
    </div>

    <!-- Right Side (form) -->
    <div class="w-full md:w-1/2 p-8 md:p-12">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('main') }}" class="text-sm text-gray-500 hover:text-gray-700">Назад</a>
            <span class="text-sm text-primary">Вход в платформу</a>
        </div>

        <h2 class="text-3xl font-semibold text-gray-800 mb-2">Вход</h2>
        <p class="text-sm text-gray-500 mb-6">Нет аккаунта? <a href="{{ route('youth.reg') }}"
                class="text-primary hover:underline">Зарегистрироваться</a>
        </p>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <x-input name="email" type="text" label="E-mail или ID" placeholder="Укажите e-mail или ID" />
            <x-input name="password" type="password" label="Пароль" placeholder="Введите пароль" />
            <div class="flex flex-row items-center justify-between gap-2 mt-5">
                <a href="{{ route('youth.reg') }}" class="text-primary text-sm hover:underline">Забыли пароль?</a>
                <x-checkbox label="Запомнить меня" name="remember" />
            </div>
            <x-button type="submit" class="w-full mt-5">Войти</x-button>
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
