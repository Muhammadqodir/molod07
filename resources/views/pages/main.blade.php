@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    @include("inc.main.hero")
    @include("inc.main.about")
    @include("inc.main.activities")
    @include("inc.main.news")
    @include("inc.main.events")
    @include("inc.main.features")
    @include("inc.main.faq")
    @include("inc.main.contacts")

    @include("inc.alert")
@endsection
