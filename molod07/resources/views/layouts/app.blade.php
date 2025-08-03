<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield('title', 'Page') | МОЛОД07</title>
  <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
  {{-- Google Fonts: Nunito --}}
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

  @vite('resources/css/app.css')
  @livewireStyles
</head>

<body class="font-sans text-gray-800 bg-white">

  {{-- Навигация --}}
  @include('inc.navigation')

  {{-- Контент --}}
  <main class="pt-[80px] min-h-[calc(100vh-200px)]">

    @yield('content')

  </main>

  {{-- Футер --}}
  @include('inc.footer')

  @vite('resources/js/app.js')
  @livewireScripts
</body>

</html>