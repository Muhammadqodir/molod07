@extends('layouts.app')

@section('title', 'Главная')

@section('content')

    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-6xl mx-auto px-6">
            <h1 class="text-3xl font-bold mb-8 text-primary">О платформе</h1>
            <div class="bg-white rounded-xl shadow p-6 mb-10">
                <p class="text-lg text-gray-700 mb-4">
                    Платформа создана для поддержки молодежи Кабардино-Балкарской Республики, объединения инициатив, обмена
                    знаниями и развития современных проектов. Мы стремимся сделать взаимодействие между молодыми людьми,
                    организациями и государством максимально удобным и эффективным.
                </p>
                <p class="text-gray-600">Здесь вы найдете актуальные новости, мероприятия, гранты, вакансии и полезные
                    ресурсы для личного и профессионального роста.</p>
            </div>
            <h2 class="text-2xl font-bold mb-6 text-primary">Наша команда</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-1 md:col-span-2 flex justify-center">
                    <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col items-center">
                        <img src="https://kbrria.ru/upload/iblock/004/0041652048094357d2ce74c3dfaa4600.jpg"
                            alt="Team Member" class="w-32 h-32 rounded-full border-4 border-primary mb-4 object-cover">
                        <div class="font-semibold text-2xl text-gray-800 mb-1">Люев Азамат Хасейнович</div>
                        <div class="text-gray-500 text-lg text-center">Министр по делам молодежи Кабардино-Балкарской Республики</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col items-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png"
                        alt="Team Member" class="w-32 h-32 rounded-full border-4 border-primary mb-4 object-cover">
                    <div class="font-semibold text-2xl text-gray-800 mb-1">Асыкина Татьяна Игоревна</div>
                    <div class="text-gray-500 text-lg text-center">Заместитель министра по делам молодежи
                        Кабардино-Балкарской Республики</div>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col items-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png"
                        alt="Team Member" class="w-32 h-32 rounded-full border-4 border-primary mb-4 object-cover">
                    <div class="font-semibold text-2xl text-gray-800 mb-1">Ахметов Артур Баширович</div>
                    <div class="text-gray-500 text-lg text-center">Заместитель министра по делам молодежи
                        Кабардино-Балкарской
                        Республики</div>
                </div>
            </div>
        </div>
    </div>

    @include('inc.alert')
@endsection
