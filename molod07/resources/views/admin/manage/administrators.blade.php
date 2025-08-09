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

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left font-medium px-4 py-3">Данные о пользователе</th>
                    <th class="text-left font-medium px-4 py-3">ID</th>
                    <th class="text-left font-medium px-4 py-3">Привелегии</th>
                    <th class="text-left font-medium px-4 py-3">Действие</th>
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
                            <div class="flex flex-wrap gap-2">
                                @foreach ($item->adminsProfile->getPermissions() as $permission)
                                    <span
                                        class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded-md">
                                        <span class="text-sm">{{ $permission }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-2 py-4 align-top">
                            <div class="flex">
                                <x-nav-icon>
                                    <x-lucide-pencil class="w-5 h-5" />
                                </x-nav-icon>
                                <x-nav-icon>
                                    <x-lucide-ban class="w-5 h-5" />
                                </x-nav-icon>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

@endsection
