@extends('layouts.app')

@section('title', 'Партнеры')

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)]">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="flex justify-between items-center mb-4 mt-12">
                <h2 class="text-3xl font-semibold text-gray-800">Наши партнеры</h2>
            </div>

            @if ($partners->count() === 0)
                <x-empty class="w-full" title="Партнеры не найдены." />
            @else
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($partners as $partner)
                        <x-partner-card
                            :id="$partner->id"
                            :name="$partner->partnersProfile->org_name"
                            :director="$partner->partnersProfile->getDirector()"
                            :address="$partner->partnersProfile->org_address"
                            :pic="$partner->partnersProfile->pic"
                            :phone="$partner->partnersProfile->phone"
                            :web="$partner->partnersProfile->web"
                            :eventsCount="$partner->partnersProfile->getMyEventsCount()"
                            :vacanciesCount="$partner->partnersProfile->getMyVacanciesCount()"
                            :link="route('partner', $partner->id)" />
                    @endforeach
                </div>
            @endif

            <div class="pb-8"></div>
        </div>
    </section>

@endsection
