@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="bg-accentbg mt-[-80px] pt-[80px]">
        <div class="max-w-screen-xl mx-auto px-6 py-6">
            <x-sidebar />
        </div>
    </section>
    @include('inc.alert')
@endsection
