@props([
    'name' => null,
    'items' => [],
    'value' => 'Pilih Salah Satu',
    'contClass' => '',
    'errorClass' => '',
])

<div {{ $contClass }}>
    <label for="{{ $name }}"
        class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>

    <div x-data="{
        open: false,
        selectedId: '{{ $value }}',
        selectedLabel: 'Pilih Salah Satu',
        items: Object.entries(@js($items)).map(([id, name]) => ({ id: id, name: name })),
    }" class="relative">
        <!-- Button to toggle dropdown -->
        <button
            x-bind:class="{
                'text-black': selectedLabel !== 'Pilih Salah Satu',
                'text-tertiary-200': selectedLabel == 'Pilih Salah Satu',
                'rounded-t-2xl border-b-0': open,
                'rounded-2xl': !open
            }"
            class="{{ $errorClass }} bg-tertiary h-14 shadow-outer text-black text-sm outline-none w-full text-left px-6 flex justify-between items-center"
            type="button" @click="open = !open">
            <span x-text="selectedLabel"></span>
            <x-icons.arrow-nav
                x-bind:class="{ 'text-black': selectedLabel !== 'Pilih Salah Satu', 'text-tertiary-200': selectedLabel == 'Pilih Salah Satu' }" />
        </button>

        {{-- Dropdown Item --}}
        <div x-cloak x-show="open" @click.away="open = false"
            class="{{ $errorClass }} w-full bg-tertiary rounded-b-2xl shadow-l-rb-outer py-2 px-6 border-t-0">
            {{-- List of Items --}}
            <ul class="pb-2 text-sm text-gray-700 dark:text-gray-200 max-h-52 overflow-y-auto">
                <template x-for="item in items" :key="item.id">
                    <li class="p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer"
                        @click="selectedId = item.id; selectedLabel = item.name; open = false;">
                        <span x-text="item.name"></span>
                    </li>
                </template>
                <input type="hidden" name="{{ $name }}" :value="selectedId">
            </ul>
        </div>
    </div>
</div>
