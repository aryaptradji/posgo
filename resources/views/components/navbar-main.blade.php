@php
    $user = Auth::user();
    $parts = explode(' ', $user->name);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    $class =
        $user->role === 'admin'
            ? 'bg-danger/15 text-danger border-danger'
            : ($user->role === 'cashier'
                ? 'bg-primary/15 text-primary border-primary'
                : 'bg-secondary-purple/15 text-secondary-purple border-secondary-purple');

@endphp

<nav x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
    :class="{
        'backdrop-blur-md bg-white/30 shadow-md': scrolled,
        'bg-transparent': !scrolled
    }"
    class="fixed z-20 top-0 w-screen h-24 px-12 flex justify-between items-center">
    <img src="{{ asset('img/posgo-logo.svg') }}" alt="posgo-logo.svg" class="w-28">
    <li class="flex uppercase gap-16 font-bold mt-2">
        <a href="{{ route('customer.home') }}"
            class="flex flex-col items-center gap-1 transition-all hover:scale-110 active:scale-90"
            x-data="{ isHomeActive: false }" @mouseenter="isHomeActive = true" @mouseleave="isHomeActive = false">
            <span class="{{ request()->is('home') ? 'text-primary' : 'text-black' }}">Home</span>
            @if (request()->is('home'))
                <hr class="inline-block w-full h-[4px] bg-primary rounded-full border-0 transition-all duration-500"
                    x-bind:class="isHomeActive ? 'scale-x-100' : 'scale-x-50'">
            @endif
        </a>
        <a href="{{ route('customer.product.index') }}"
            class="flex flex-col items-center gap-1 transition-all hover:scale-110 active:scale-90"
            x-data="{ isProductActive: false }" @mouseenter="isProductActive = true" @mouseleave="isProductActive = false">
            <span class="{{ request()->is('product') ? 'text-primary' : 'text-black' }}">Produk</span>
            @if (request()->is('product'))
                <hr class="inline-block w-full h-[4px] bg-primary rounded-full border-0 transition-all duration-500"
                    x-bind:class="isProductActive ? 'scale-x-100' : 'scale-x-50'">
            @endif
        </a>
        <a href="{{ route('customer.order.index') }}"
            class="flex flex-col items-center gap-1 transition-all hover:scale-110 active:scale-90"
            x-data="{ isOrderActive: false }" @mouseenter="isOrderActive = true" @mouseleave="isOrderActive = false">
            <span class="{{ request()->is('order') ? 'text-primary' : 'text-black' }}">Pesananku</span>
            @if (request()->is('order'))
                <hr class="inline-block w-full h-[4px] bg-primary rounded-full border-0 transition-all duration-500"
                    x-bind:class="isOrderActive ? 'scale-x-100' : 'scale-x-50'">
            @endif
        </a>
    </li>
    <div class="flex justify-between items-center w-fit gap-2 mr-5 relative" x-data="{ open: false }">
        @if ($user->photo_url)
            <img src="{{ $user->photo_url }}" class="rounded-full h-11 w-11 aspect-square object-cover">
        @else
            <div
                class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-11 h-11 flex items-center justify-center text-lg">
                {{ $initials }}
            </div>
        @endif
        <button type="button" @click="open = !open" @click.away="open = false"
            class="flex items-center transition-all rounded-lg p-2 hover:bg-tertiary-table-line">
            <span class="font-semibold">{{ $user->name }}</span>
            <x-icons.arrow-down class="ml-2 text-tertiary-300" />
            <div class="absolute right-0 -bottom-48 shadow-drop p-4 bg-white rounded-xl" x-cloak x-show="open"
                x-transition:enter="transition-all ease duration-300" x-transition:enter-start="scale-0"
                x-transition:enter-end="scale-100" x-transition:leave="transition-all ease duration-300"
                x-transition:leave-start="scale-100" x-transition:leave-end="scale-0">
                <div class="flex gap-2">
                    @if ($user->photo_url)
                        <img src="{{ $user->photo_url }}" class="rounded-full h-11 w-11 aspect-square object-cover">
                    @else
                        <div
                            class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-11 h-11 flex items-center justify-center text-lg">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="flex flex-col text-sm justify-between items-start">
                        <span class="font-semibold max-w-44">{{ $user->name }}</span>
                        <span
                            class="px-2 rounded-full capitalize border-2 w-fit text-xs {{ $class }}">{{ $user->role }}</span>
                    </div>
                </div>
                <hr class="w-full h-[1px] mt-4 mb-2 bg-tertiary-table-line rounded-full border-0">
                <a href="#"
                    class="flex items-center p-2 rounded-lg gap-2 text-sm transition-all hover:bg-tertiary-table-line">
                    <x-icons.settings />
                    <span class="font-semibold">Settings</span>
                </a>
                <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center mt-1 p-2 rounded-lg gap-2 text-sm transition-all hover:bg-tertiary-table-line">
                    <x-icons.logout class="text-danger" />
                    <span class="font-semibold text-danger">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </button>
    </div>
</nav>
