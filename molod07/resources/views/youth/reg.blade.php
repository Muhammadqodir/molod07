@extends('layouts.blank')

@section('title', 'Регистрация')

@section('content')

    <div class="flex align-items-center justify-content-center min-h-[100vh]">
        @include('inc.auth.reg-user')
    </div>

    @include('inc.alert')
@endsection
