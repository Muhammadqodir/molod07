<section class="py-16 bg-[#F7F9FB]">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-semibold text-gray-800">Вакансии</h2>
            <a href="{{ route('vacancies.list') }}">
                <x-button variant="text">
                    Смотреть все
                </x-button>
            </a>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($vacancies as $vacancy)
                <x-vacancy-card title="{{ $vacancy->title }}" category="{{ $vacancy->category }}"
                    date="{{ $vacancy->created_at->format('d.m.y') }}" salary="{{ $vacancy->getSalaryRange() }}"
                    location="{{ $vacancy->org_address }}" link="{{ route('vacancy', $vacancy->id) }}" />
            @endforeach
            @if ($vacancies->isEmpty())
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                    <x-empty class="w-full" title="Список вакансий пуст." />
                </div>
            @endif
        </div>
    </div>
</section>
