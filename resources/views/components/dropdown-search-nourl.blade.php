@props([
    'name' => null,
    'items' => [],
    'value' => 'Pilih Salah Satu',
    'contClass' => '',
    'errorClass' => '',
])

<div class="{{ $contClass }}">
    <label for="{{ $name }}"
        class="block mb-4 text-base font-bold text-black dark:text-white">{{ $slot }}</label>

    <div x-data="{
        open: false,
        selected: '{{ $value }}',
        name: '{{ $name }}',
        search: '',
        items: @js($items),
        get selectedName() {
            console.log('DEBUG selectedName called:', { currentSelected: this.selected, itemsToSearch: this.items });
            const item = this.items.find(i => {
                console.log('  Comparing:', i.slug, 'with', this.selected);
                return i.slug === this.selected;
            });
            console.log('  Found item:', item);
            return item ? item.name : 'Pilih Salah Satu';
        }
    }" x-init="
        // *** LOGIKA KRUSIAL DI SINI ***
        // Dengarkan event 'selected-change' yang mungkin dipicu oleh parent
        // atau oleh inisialisasi awal itu sendiri, dan gunakan untuk mengatur 'selected'.
        this.$el.addEventListener('selected-change', (event) => {
            const newValue = event.detail;
            if (this.selected !== newValue) { // Hanya update jika berbeda untuk menghindari loop
                this.selected = newValue;
            }
        });

        // Setelah listener diatur, picu event 'selected-change' dengan nilai awal dari prop 'value'.
        // Ini akan memastikan 'selected' diinisialisasi dengan nilai yang datang dari Blade.
        // Gunakan $nextTick untuk memastikan semua ter-render dan listener siap.
        this.$nextTick(() => {
            if (this.selected !== 'Pilih Salah Satu' || ('{{ $value }}' !== 'Pilih Salah Satu')) {
                 // Kirim nilai yang paling benar antara selected awal atau $value dari prop
                const initialVal = (this.selected !== 'Pilih Salah Satu') ? this.selected : '{{ $value }}';
                this.$el.dispatchEvent(new CustomEvent('selected-change', { detail: initialVal }));
            }
        });


        // Ini tetap ada untuk watcher internal komponen yang memancarkan event ke luar
        $watch('selected', value => $dispatch('selected-change', value));
    "
    class="relative">
        <!-- Button -->
        <button @click="open = !open"
            :class="{
                'text-black': selectedName !== 'Pilih Salah Satu',
                'text-tertiary-200': selectedName === 'Pilih Salah Satu',
                'rounded-t-2xl border-b-0': open,
                'rounded-2xl': !open
            }"
            class="{{ $errorClass }} bg-tertiary h-14 shadow-outer text-sm outline-none w-full text-left px-6 flex justify-between items-center"
            type="button">
            <span x-text="selectedName"></span>
            <x-icons.arrow-nav
                x-bind:class="{
                    'text-black': selectedName !== 'Pilih Salah Satu',
                    'text-tertiary-200': selectedName === 'Pilih Salah Satu'
                }" />
        </button>

        <!-- Hidden input untuk submit -->


        <!-- Dropdown -->
        <div x-show="open" x-cloak @click.away="open = false"
            class="{{ $errorClass }} w-full bg-tertiary rounded-b-2xl shadow-l-rb-outer py-2 px-6 border-t-0">

            <!-- Search -->
            <input type="text" x-model="search" placeholder="Cari..."
                class="w-full p-3 text-sm text-gray-900 outline-none ring-2 ring-tertiary-300 rounded-lg bg-gray-50 focus:ring-primary">

            <!-- Items -->
            <ul class="py-2 text-sm text-gray-700 max-h-52 overflow-y-auto">
                <template
                    x-for="(item, index) in items.filter(i => i.name.toLowerCase().includes(search.toLowerCase()))"
                    :key="index">
                    <li>
                        <a class="block p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer"
                            @click.prevent="selected = item.slug; open = false; $dispatch('selected-change', selected)">
                            <span x-text="item.name"></span>
                        </a>
                    </li>
                </template>
                <input type="hidden" x-bind:name="name" x-bind:value="selected">
            </ul>
        </div>
    </div>
</div>
