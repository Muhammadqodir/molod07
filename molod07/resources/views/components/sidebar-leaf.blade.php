@props(['label' => '', 'route' => 'main'])

@php
    $active = request()->routeIs([$route, $route . ".*"]);
@endphp

<li>
  <a href="{{ route($route) }}"
     class="block px-3 py-3 rounded-xl text-gray-700
     {{ $active ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-gray-50' }}">
    {{ $label }}
  </a>
</li>
