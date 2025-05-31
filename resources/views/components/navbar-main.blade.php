<nav x-data="{ scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
    :class="{
        'backdrop-blur-md bg-white/30 shadow-md': scrolled,
        'bg-transparent': !scrolled
    }"
    class="fixed z-20 top-0 w-screen h-24 px-12 flex justify-between items-center">
    <img src="{{ asset('img/posgo-logo.svg') }}" alt="posgo-logo.svg" class="w-28">
    <li class="flex uppercase gap-16 font-bold mt-2">
        <a href="{{ route('customer.home') }}" class="flex flex-col items-center gap-1 transition-all hover:scale-110 active:scale-90"
            x-data="{ isHomeActive: false }" @mouseenter="isHomeActive = true" @mouseleave="isHomeActive = false">
            <span class="{{ request()->is('home') ? 'text-primary' : 'text-black'}}">Home</span>
            @if (request()->is('home'))
                <hr class="inline-block w-full h-[4px] bg-primary rounded-full border-0 transition-all duration-500" x-bind:class="isHomeActive ? 'scale-x-100' : 'scale-x-50'">
            @endif
        </a>
        <a href="{{ route('customer.product.index') }}" class="flex flex-col items-center gap-1 transition-all hover:scale-110 active:scale-90"
            x-data="{ isProductActive: false }" @mouseenter="isProductActive = true" @mouseleave="isProductActive = false">
            <span class="{{ request()->is('product') ? 'text-primary' : 'text-black' }}">Produk</span>
            @if (request()->is('product'))
                <hr class="inline-block w-full h-[4px] bg-primary rounded-full border-0 transition-all duration-500" x-bind:class="isProductActive ? 'scale-x-100' : 'scale-x-50'">
            @endif
        </a>
        <a href="#">Pesanan</a>
    </li>
    <span
        class="flex items-center justify-center w-11 h-11 mr-4 aspect-square rounded-full font-semibold text-2xl bg-tertiary-400">AA</span>
</nav>
