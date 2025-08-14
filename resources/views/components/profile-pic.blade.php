{{-- Accept a $user argument for the component --}}
@props(['user'])
<div
    {{ $attributes->merge(['class' => 'w-20 h-20 rounded-xl bg-[#e5efff] flex items-center justify-center text-2xl text-gray-700 font-medium overflow-hidden']) }}>
    @php
        $userPic = $user->getProfile()->pic;
    @endphp
    @if ($userPic)
        <img src="{{ asset('uploads/' . $user->getProfile()->pic) }}" alt="userPic"
            class="object-cover w-full h-full rounded-xl" />
    @else
        <p>{{ strtoupper(mb_substr($user->getFullName() ?? '?', 0, 1)) }}</p>
    @endif
</div>
