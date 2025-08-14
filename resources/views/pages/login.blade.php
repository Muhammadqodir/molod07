@extends('layouts.blank')

@section('title', 'Вход в платформу')

@section('content')

    <div class="flex align-items-center justify-content-center min-h-[100vh]">
        @include('inc.auth.login')
    </div>

    @include('inc.alert')
@endsection
