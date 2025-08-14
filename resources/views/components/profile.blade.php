@auth
    <div x-data="{ open: false }" class="relative">
        <!-- Trigger Button (Avatar) -->
        <button @click="open = !open" class="flex items-center gap-1 justify-center h-10 gap-0 overflow-hidden">
            <x-profile-pic :user="Auth::user()" class="flex-none w-[40px] h-[40px] text-xl"/>

            <x-lucide-chevron-down class="w-4 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
        </button>

        <!-- Dropdown -->
        <div x-show="open" @click.outside="open = false" x-transition
            class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg p-4 z-50">
            <!-- Header -->
            <div class="flex items-center space-x-3 mb-4">
                <x-profile-pic :user="Auth::user()"  class="flex-none w-[70px] h-[70px]"/>
                <div class="text-sm font-medium text-gray-800 leading-tight">{{ Auth::user()->getFullName() }}</div>
            </div>

            <!-- Actions -->
            <ul class="space-y-3 text-sm text-gray-700">
                <a href="{{ route('profile') }}">
                    <li class="flex items-center space-x-2 cursor-pointer hover:text-primary">
                        <x-lucide-settings class="w-5" />
                        <span>Настройки</span>
                    </li>
                </a>
                <li class="flex items-center space-x-2 cursor-pointer hover:text-primary">
                    <x-lucide-life-buoy class="w-5" />
                    <span>Обратиться в поддержку</span>
                </li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <li onclick="this.closest('form').submit()"
                        class="flex items-center space-x-2 cursor-pointer hover:text-primary">
                        <x-lucide-log-out class="w-5" />
                        <span>Выйти</span>
                    </li>
                </form>
            </ul>
        </div>
    </div>
@else
    <a href="{{ route('youth.reg') }}">
        <x-nav-icon>
            <x-lucide-user-plus class="h-5 w-5" />
        </x-nav-icon>
    </a>
@endauth
