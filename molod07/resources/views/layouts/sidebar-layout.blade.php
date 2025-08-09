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

    {{-- Навигация --}}
    @include('inc.navigation')

    {{-- Контент --}}
    <main class="pt-[80px] min-h-[calc(100vh-200px)]">

        <section class="bg-accentbg mt-[-80px] pt-[80px]">
            <div class="flex gap-5 max-w-screen-xl mx-auto px-6 py-6">

                @switch(Auth::user()->role)
                    @case('admin')
                        @include('admin.inc.sidebar')
                    @break

                    @case('youth')
                        @include('youth.inc.sidebar')
                    @break

                    @case('partner')
                        @include('admin.inc.sidebar')
                    @break
                @endswitch

                <div class="w-full space-y-6">
                    <!-- Profile section -->
                    <div class="flex bg-white p-8 rounded-2xl shadow-sm flex-col items-left">
                        @yield('content')
                    </div>
                </div>


                @if ($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="fixed z-[9999999999] top-[80px] right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                        <strong>Ошибка!</strong>
                        <ul class="text-sm mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="fixed z-[9999999999] top-[80px] right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                        <strong>Успех!</strong>
                        <div class="text-sm mt-1">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
            </div>
        </section>
        @include('inc.alert')
    </main>

    {{-- Футер --}}
    @include('inc.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
        integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
    @stack('scripts')
    @vite('resources/js/app.js')
    @livewireScripts
</body>

</html>
