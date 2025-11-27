@extends('layouts.sidebar-layout')

@section('title', 'Мой QR-код')

@section('content')
    <div class="max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Мой QR-код</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <p class="text-gray-600 mb-6">
                    Покажите этот QR-код организатору мероприятия для подтверждения участия
                </p>

                <div class="flex justify-center mb-6">
                    <div class="bg-white p-4 rounded-lg border-2 border-gray-200">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate('molod07:' . Auth::id()) !!}
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-500 mb-1">ID участника:</p>
                    <p class="text-2xl font-mono font-bold text-gray-800">molod07:{{ Auth::id() }}</p>
                </div>

                <div class="text-sm text-gray-500">
                    <p>Этот QR-код является стабильным и не меняется.</p>
                    <p>Вы можете сохранить его скриншот для использования в будущем.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
