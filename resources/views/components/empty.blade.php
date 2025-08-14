@props(['title' => 'Здесь пока нечего отображать.'])

<div class="flex flex-col items-center justify-center py-12">
    <img src="{{ asset('images/empty.png') }}" alt="Empty" class="w-20 h-20 mb-4">
    <p class="text-gray-500">{{ $title }}</p>
</div>
