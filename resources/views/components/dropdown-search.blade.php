@props([
    'name' => null,
    'items' => [],
    'value' => 'Pilih Salah Satu'
])

<div {{ $attributes }}>
    <label for="{{ $name }}"
        class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>

    <div x-data="{ open: false, selected: 'Pilih Salah Satu', search: '', items: @js($items), isFocus: false }" class="relative">
        <!-- Button to toggle dropdown -->
        <button
            x-bind:class="{ 'text-black': selected !== '{{ $value }}', 'text-tertiary-200': selected == '{{ $value }}', 'rounded-t-2xl': open, 'rounded-2xl': !open }"
            class="bg-tertiary h-14 shadow-outer text-black text-sm outline-none w-full text-left px-6 flex justify-between items-center"
            type="button" name="{{ $name }}" @click="open = !open" @focus="isFocus=true">
            <span x-text="selected"></span>
            <x-icons.arrow-nav x-bind:class="{ 'text-black' : selected !== '{{ $value }}', 'text-tertiary-200': selected == '{{ $value }}' }"/>
        </button>

        <!-- Dropdown menu -->
        <div x-show="open" @click.away="open = false; isFocus = false"
            class="w-full bg-tertiary rounded-b-2xl shadow-l-rb-outer py-2 px-6 dark:bg-gray-700">
            <!-- Search input -->
            <input type="text" x-model="search" placeholder="Cari kota . . ." @focus="isFocus=true"
                class="w-full p-3 text-sm text-gray-900 outline-none ring-2 ring-tertiary-300 rounded-lg bg-gray-50 focus:ring-primary">

            <!-- List of items -->
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 max-h-52 overflow-y-auto">
                <template
                    x-for="(item, index) in items.filter(item => item.toLowerCase().includes(search.toLowerCase()))"
                    :key="index">
                    <li class="p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer" @click="selected = item; open = false; isFocus = false" tabindex="-1" @focus="isFocus=true">
                        <span x-text="item"></span>
                    </li>
                </template>
                <input type="hidden" name="{{ $name }}" :value="selected">
            </ul>
        </div>
    </div>


</div>
