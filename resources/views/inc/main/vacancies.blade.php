<section class="py-16 bg-[#F7F9FB]">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-semibold text-gray-800">Вакансии</h2>
            <x-button variant="text">
                Смотреть все
            </x-button>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($vacancies as $vacancy)
                <x-vacancy-card title="{{ $vacancy->title }}" category="{{ $vacancy->category }}"
                    date="{{ $vacancy->created_at->format('d.m.y') }}" salary="{{ $vacancy->getSalaryRange() }}"
                    location="{{ $vacancy->org_address }}" link="{{ route('vacancy', $vacancy->id) }}" />
            @endforeach

        </div>
    </div>
</section>
