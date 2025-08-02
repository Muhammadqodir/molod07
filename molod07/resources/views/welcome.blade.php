<html>
<!-- resources/views/welcome.blade.php -->
<head>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body>
    <h1 class="text-2xl font-bold text-center mt-10">Привет, Laravel + Tailwind!</h1>

    <x-button type="submit" class="w-full">test</x-button>

    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>