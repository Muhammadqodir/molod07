<aside class="w-full max-w-xs space-y-6 hidden md:block">
    <!-- Profile section -->
    <div class="flex bg-white p-4 rounded-2xl shadow-sm flex-col items-left text-center space-y-2">
        <x-profile-pic :user="Auth::user()" class="w-20 h-20" />
        <div class="text-base font-semibold flex items-center gap-1">
            {{ Auth::user()->getFullName() ?? 'Имя и Фамилия' }}
            <x-lucide-badge-check class="text-blue-500 w-4 h-4" />
        </div>
        <div class="text-sm text-gray-500">
            <div class="flex justify-between items-center">
                ID
                <div class="flex ml-2">
                    {{ Auth::user()->getUserId() }}
                    <button type="button" class="ml-2 text-gray-400 hover:text-blue-500 focus:outline-none"
                        onclick="copyUserId('{{ Auth::user()->getUserId() }}')" title="Скопировать ID">
                        <x-lucide-copy class="w-4 h-4" />
                    </button>
                </div>

            </div>

            @switch(Auth::user()->role)
                @case('admin')
                    <div></div>
                @break

                @case('youth')
                    <div class="flex justify-between">Баллы <span class="ml-2">100</span></div>
                @break

                @case('partner')
                    <div class="flex justify-between mt-1">Мероприятия <span class="ml-2">0</span></div>
                    <div class="flex justify-between mt-1">Вакансии <span class="ml-2">0</span></div>
                @break
            @endswitch
        </div>
    </div>

    <!-- Menu -->
    <ul class="space-y-1 bg-white p-4 rounded-2xl shadow-sm text-sm">
        {{ $slot }}

        <li>
            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a onclick="this.closest('form').submit()"
                    class="flex items-center space-x-2 px-3 py-3 cursor-pointer rounded-xl hover:bg-gray-50 text-gray-700 }}">
                    <x-dynamic-component :component="'lucide-log-out'" class="w-5 h-5" />
                    <span>Выйти</span>
                </a>
            </form>
        </li>

    </ul>
</aside>

@push('scripts')
    <script>
        function copyUserId(id) {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: 'ID Скопирован'
            }));
            navigator.clipboard.writeText(id);
        }
    </script>
@endpush
