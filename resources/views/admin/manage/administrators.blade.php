@extends('layouts.sidebar-layout')

@section('title', 'Администраторы')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Администраторы</h1>
        <a href="{{ route('admin.manage.administrators.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.manage.administrators') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по имени или email" :value="old('q', request('q'))" />
        </div>
    </form>



    @if ($admins->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Данные о пользователе</th>
                        <th class="text-left font-medium px-4 py-3">ID</th>
                        <th class="text-left font-medium w-full px-4 py-3">Привелегии</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($admins as $item)
                        @php
                            /** @var \App\Models\User $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <x-profile-pic :user="$item" class="flex-none h-[50px] w-[50px] text-sm" />
                                    <div>
                                        <div class="text-gray-800 font-medium">{{ $item->getFullName() }}</div>
                                        <div class="text-gray-500 text-sm">{{ $item->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- ID --}}
                            <td class="px-4 py-4 text-gray-700">
                                {{ $item->getUserId() }}
                            </td>

                            {{-- Actions (tags) --}}
                            <td class="px-4 py-4">
                                <x-badges-clamped :items="$item->adminsProfile->getPermissions()" :limit="3" :title="$item->getFullName()" />
                            </td>
                            <td class="px-2 py-4">
                                @if($item->id == Auth::user()->id)
                                    <div class="text-gray-500">Это вы</div>
                                @else
                                <div class="flex items-center justify-center">

                                    <form method="POST" action="{{ $item->is_blocked ? route('admin.manage.administrators.unblock') : route('admin.manage.administrators.block') }}"
                                        onsubmit="return confirm('Вы уверены, что хотите {{ $item->is_blocked ? 'разблокировать' : 'заблокировать' }} этого администратора?');">
                                        @csrf
                                        <input name="id" value="{{ $item->id }}" hidden>
                                        <button type="submit" class="p-0 m-0 bg-transparent border-0">
                                            @if($item->is_blocked)
                                                <x-nav-icon>
                                                    <x-lucide-lock class="w-5 h-5" />
                                                </x-nav-icon>
                                            @else
                                                <x-nav-icon>
                                                    <x-lucide-unlock class="w-5 h-5" />
                                                </x-nav-icon>
                                            @endif
                                        </button>
                                    </form>

                                    <button type="button" onclick="openPasswordModal({{ $item->id }}, '{{ $item->getFullName() }}')" class="p-0 m-0 bg-transparent border-0">
                                        <x-nav-icon>
                                            <x-lucide-key-round class="w-5 h-5" />
                                        </x-nav-icon>
                                    </button>

                                    <form method="POST" action="{{ route('admin.manage.youth.remove') }}"
                                        onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
                                        @csrf
                                        <input name="id" value="{{ $item->id }}" hidden>
                                        <button type="submit" class="p-0 m-0 bg-transparent border-0">
                                            <x-nav-icon>
                                                <x-lucide-trash-2 class="w-5 h-5" />
                                            </x-nav-icon>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif


    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-500">
            Показано {{ $admins->firstItem() }}–{{ $admins->lastItem() }} из {{ $admins->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $admins->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

    <!-- Password Reset Modal -->
    <div id="passwordModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="z-index: 99999999999;">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closePasswordModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative mx-auto p-6 border w-96 shadow-lg rounded-xl bg-white">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                        <x-lucide-key-round class="h-6 w-6 text-blue-600" />
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Сброс пароля</h3>
                    <p class="text-sm text-gray-500 mt-2" id="modalUserName"></p>

                    <form method="POST" action="{{ route('admin.manage.administrators.reset-password') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="id" id="modalUserId">

                        <div class="text-left">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Новый пароль
                            </label>
                            <input type="text" name="password" id="new_password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Введите новый пароль">
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-6">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                                Сбросить пароль
                            </button>
                            <button type="button" onclick="closePasswordModal()"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPasswordModal(userId, userName) {
            document.getElementById('modalUserId').value = userId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('new_password').value = '';
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('passwordModal').classList.contains('hidden')) {
                closePasswordModal();
            }
        });
    </script>

@endsection
