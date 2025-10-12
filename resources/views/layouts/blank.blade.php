<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title', 'Page') | МОЛОД07</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    {{-- Google Fonts: Nunito --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    @stack('styles')
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="font-sans text-gray-800 bg-white">

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
        integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/interactions.js') }}"></script>
    @stack('scripts')
    @vite('resources/js/app.js')
    @livewireScripts
</body>

</html>
