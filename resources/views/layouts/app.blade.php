<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-authenticated" content="true">
    @endauth
    <title> @yield('title', 'Page') | МОЛОД07</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    {{-- Google Fonts: Nunito --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/interactions.css') }}">
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="font-sans text-gray-800 bg-white">

    @php
        $userAgent = request()->header('User-Agent', '');
        $isNativeApp = str_contains($userAgent, 'Molod07-NativeApp/1.0 (Mobile; Flutter)');
    @endphp

    {{-- Навигация --}}
    @include('inc.navigation')

    {{-- Контент --}}
    <main class="pt-[80px] min-h-[calc(100vh-200px)]">

        @yield('content')

    </main>


    {{-- Футер --}}
    @if (!$isNativeApp)
        @include('inc.footer')
    @endif

    {{-- Модальное окно для авторизации --}}
    <x-auth-modal />

    {{-- Кнопка обратной связи (для всех пользователей) --}}
    <x-feedback-button />

    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
        integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/interactions.js') }}"></script>
    @stack('scripts')
    @vite('resources/js/app.js')
    @livewireScripts
</body>

</html>
