@props([
    'name' => null,
    'items' => [],
    'value' => 'Pilih Salah Satu'
])

<div {{ $attributes }}>
    <label for="{{ $name }}"
        class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>

    <div x-data="{
            open: false,
            typed: '',
            btnShow: true,
            inputShow: false,
            selected: '{{ $value }}',
            items: @js($items),
            isFocus: false
        }"
        class="relative">
        <!-- Button to toggle dropdown -->
        <button x-cloak x-show="btnShow"
            x-transition:enter="transition-all ease duration-300"
            x-transition:enter-start="scale-0"
            x-transition:enter-end="scale-100"
            x-transition:leave="transition-all ease duration-300"
            x-transition:leave-start="scale-100"
            x-transition:leave-end="scale-0"
            x-bind:class="{ 'text-black': selected !== 'Pilih Salah Satu', 'text-tertiary-200': selected ==
                'Pilih Salah Satu', 'rounded-t-2xl': open, 'rounded-2xl': !open }"
            class="bg-tertiary h-14 shadow-outer text-black text-sm outline-none w-full text-left px-6 flex justify-between items-center"
            type="button" name="{{ $name }}" @click="open = !open" @focus="isFocus=true">
            <span x-text="selected"></span>
            <x-icons.arrow-nav x-bind:class="{ 'text-black' : selected !== 'Pilih Salah Satu', 'text-tertiary-200': selected == 'Pilih Salah Satu' }"/>
        </button>
        <div class="flex items-center gap-4 w-full mt-4"
            x-cloak x-show="inputShow"
            x-transition:enter="transition-all ease duration-300"
            x-transition:enter-start="scale-0"
            x-transition:enter-end="scale-100"
            x-transition:leave="transition-all ease duration-300"
            x-transition:leave-start="scale-100"
            x-transition:leave-end="scale-0"
        >
            <input type="text" name="{{ $name }}" x-model="typed" x-on:input="selected = typed" placeholder="Lain-lain . . ."  class="bg-tertiary h-14 shadow-outer rounded-2xl text-black placeholder:text-tertiary-200 focus:shadow-inner focus:ring focus:ring-primary text-sm outline-none w-full text-left px-6 flex justify-between items-center">
            <button type="button" class="text-danger transition-all hover:scale-125 active:scale-90" @click="btnShow = true; inputShow = false; open = false">
                <x-icons.close-drop />
            </button>
        </div>

        {{-- Dropdown Item --}}
        <div x-cloak x-show="open" @click.away="open = false; isFocus = false"
            class="w-full bg-tertiary rounded-b-2xl shadow-l-rb-outer py-2 px-6 dark:bg-gray-700">
            {{-- List of Items --}}
            <ul class="pb-2 text-sm text-gray-700 dark:text-gray-200 max-h-52 overflow-y-auto">
                <template
                    x-for="(item, index) in items"
                    :key="index">
                    <li class="p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer"
                        @click="selected = item; open = false; isFocus = false" tabindex="-1" @focus="isFocus=true">
                        <span x-text="item"></span>
                    </li>
                </template>
                <li class="p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer"
                    @click="selected = 'Pilih Salah Satu'; open = false; isFocus = false; btnShow = false; inputShow = true" tabindex="-1" @focus="isFocus=true">
                    Lain-lain
                </li>
                <input type="hidden" name="{{ $name }}" :value="selected">
            </ul>
        </div>
    </div>
</div>
