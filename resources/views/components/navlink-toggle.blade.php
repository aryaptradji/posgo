<!-- x-navlink-toggle.blade.php -->
@props([
    'focusNo' => null,
    'menus' => [],
    'titleIcon' => null,
    'route' => null,
])

@php
    // Cek apakah ada menu di dalam toggle ini yang aktif
    $isAnyMenuInToggleActive = false;
    foreach ($menus as $menu) {
        if ($menu['active']) {
            $isAnyMenuInToggleActive = true;
            break;
        }
    }
@endphp

<li x-data="{
    isOpen: {{ $isAnyMenuInToggleActive ? 'true' : 'false' }}, // Buka toggle jika ada menu aktif di dalamnya
    focusNo: {{ $focusNo }},
}">
    <button @click="isOpen = !isOpen; focus = focusNo"
        class="flex items-center w-full px-4 py-3 rounded-2xl text-black bg-none shadow-none transition-transform hover:scale-105"
        {{ $isAnyMenuInToggleActive ? 'id="active-nav-link"' : '' }} {{-- <<< TAMBAHKAN INI --}}
    >
        <span class="stroke-black text-black">{{ $titleIcon }}</span>
        <span class="flex-1 ms-3 text-left font-semibold">{{ $slot }}</span>
        <span class="w-4 h-min transition-transform duration-500"
            :class="{ 'rotate-180': isOpen, 'rotate-0': !isOpen }">
            <x-icons.arrow-nav />
        </span>
    </button>

    <ul class="space-y-2 ring ring-primary shadow-inner mx-4 rounded-2xl" x-cloak x-show="isOpen"
        x-transition:enter="transition-all ease duration-1000" x-transition:enter-start="opacity-0 -translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-none" x-transition:leave="transition-all ease duration-800"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-6">

        @foreach ($menus as $menu)
            <li>
                <a href="{{ $menu['route'] }}"
                    class="flex items-center w-full px-6 py-2 transition-transform rounded-2xl group
                    {{ $menu['active'] ? 'text-primary' : 'text-black hover:scale-105' }}">
                    {!! $menu['icon'] !!}
                    <span class="ms-3 font-semibold">{{ $menu['name'] }}</span>
                </a>
            </li>
        @endforeach

    </ul>
</li>
