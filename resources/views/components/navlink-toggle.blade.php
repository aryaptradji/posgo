<!-- x-navlink-toggle.blade.php -->
@props([
    'focusNo' => null,
    'menus' => [],
    'titleIconAct' => null,
    'titleIconInact' => null
])

<li x-data="{
    isOpen: false,
    focusNo: {{ $focusNo }},
    menus: @js($menus),
    menu_index: 0,
    get isActive() {
        return focus == this.focusNo || focus == (this.focusNo + 'in' + this.menu_index)
    },
    titleIconAct: '{{ asset($titleIconAct) }}',
    titleIconInact: '{{ asset($titleIconInact) }}'
}">
    <button @click="isOpen = !isOpen; focus = focusNo"
        :class="isActive ? 'text-white bg-primary shadow-outer-sidebar' : 'text-black bg-none shadow-none transition-transform hover:scale-105'"
        class="flex items-center w-full px-4 py-3 rounded-2xl group"
    >
        <img :src="isActive ? titleIconAct : titleIconInact" alt="icon">
        <span class="flex-1 ms-3 text-left font-semibold">{{ $slot }}</span>
        <img :src="isActive ? '{{ asset("img/icon/arrow-up-white.svg") }}' : '{{ asset("img/icon/arrow-up-black.svg") }}'"
            :class="{ 'rotate-0': isOpen, 'rotate-180': !isOpen }" class="w-4 h-min transition-transform duration-500">
    </button>

    <ul id="dropdown-pesanan" class="space-y-2 ring ring-primary shadow-inner mx-4 rounded-2xl"
        x-cloak
        x-show="isOpen"
        x-transition:enter="transition-all ease duration-1000"
        x-transition:enter-start="opacity-0 -translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-none"
        x-transition:leave="transition-all ease duration-800"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 -translate-y-6"
    >
        <template x-for="(menu, index) in menus" :key="index">
            <li>
                <a {{ $attributes }} @click="focus = focusNo + 'in' + index; menu_index = index"
                    x-bind:class="focus == focusNo + 'in' + index ? 'text-primary' : 'text-black hover:scale-105'"
                    class="flex items-center w-full px-6 py-2 transition-transform rounded-2xl group">
                    <img :src="focus == focusNo + 'in' + index ? menu.iconActive : menu.iconInactive" :alt="menu.name">
                    <span class="ms-3 font-semibold" x-text="menu.name"></span>
                </a>
            </li>
        </template>
    </ul>
</li>
