<x-layout>
    <x-slot:title>Ubah Purchase Order</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('purchase-order.index') }}"
                class="font-semibold transition-all duration-300 hover:text-primary hover:scale-110 active:scale-90">Kelola
                PO</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Ubah</span>
        </div>
        <div>
            Ubah Purchase Order
        </div>
    </x-slot:header>

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-20 flex flex-col items-end gap-4">
            @foreach ($errors->all() as $error)
                <x-toast id="toast-failed{{ $loop->index }}" iconClass="text-danger bg-danger/25" slotClass="text-danger"
                    :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-failed />
                    </x-slot:icon>
                    {{ $error }}
                </x-toast>
            @endforeach
        </div>
    @endif

    {{-- Hidden preload JSON --}}
    <script type="application/json" id="purchaseData">
        {!! $purchaseData !!}
    </script>

    <div class="mt-10 mb-4" x-data="purchaseOrderForm()">
        <form action="{{ route('purchase-order.update', $purchaseOrder) }}" method="POST" x-ref="form">
            @csrf
            @method('PUT')
            {{-- Supplier --}}
            <div class="w-1/2 mb-10" x-data x-on:selected-change="supplier = $event.detail">
                <x-dropdown-search-nourl name="supplier" :items="$suppliers->map(fn($s) => ['slug' => $s->slug, 'name' => $s->name])->toArray()" :value="old('supplier', $purchaseOrder->supplier->slug)">
                    Supplier
                </x-dropdown-search-nourl>
            </div>

            {{-- Items --}}
            <div class="shadow-outer py-4 rounded-xl flex flex-col">
                <div class="text-lg px-6 font-bold pb-4 border-b border-tertiary-title-line">Produk yang dipesan</div>

                <div class="pt-4 px-6 space-y-6">
                    <template x-if="items.length === 0">
                        <div class="text-center text-gray-400 italic py-10">Belum ada produk yang dipesan</div>
                    </template>

                    <template x-for="(item, index) in items" :key="item.id">
                        <div x-show="item.show" x-cloak x-transition:enter="transition-all ease duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition-all ease duration-500"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-4" x-init="setTimeout(() => {
                                item.show = true;
                            }, 300)"
                            class="bg-white rounded-xl p-6">
                            <div class="flex justify-end mb-3">
                                <button type="button"
                                    @click="item.show = false; setTimeout(() => removeItem(item.id), 500);"
                                    class="text-danger transition-transform hover:scale-125 active:scale-90">
                                    <x-icons.delete-icon />
                                </button>
                            </div>

                            <div class="flex justify-between gap-14">
                                {{-- Produk --}}
                                <div class="w-3/5">
                                    <label :for="'product-' + item.id"
                                        class="block mb-4 text-base font-bold text-black dark:text-white">Produk <span
                                            x-text="index + 1"></span></label>
                                    <div x-data="{
                                        open: false,
                                        selected: item.product, // Langsung ambil dari item.product
                                        name: 'items[' + index + '][product]', // Nama input
                                        search: '',
                                        items: @js($products->map(fn($p) => ['slug' => $p->slug, 'name' => $p->name])->toArray()),
                                        get selectedName() {
                                            const p = this.items.find(i => i.slug === this.selected);
                                            return p ? p.name : 'Pilih Salah Satu';
                                        }
                                    }" x-init="$watch('selected', value => item.product = value)" {{-- Update 'item.product' di state utama --}}
                                        class="relative">
                                        <button @click="open = !open"
                                            :class="{
                                                'text-black': selectedName !== 'Pilih Salah Satu',
                                                'text-tertiary-200': selectedName === 'Pilih Salah Satu',
                                                'rounded-t-2xl border-b-0': open,
                                                'rounded-2xl': !open
                                            }"
                                            class="bg-tertiary h-14 shadow-outer text-sm outline-none w-full text-left px-6 flex justify-between items-center"
                                            type="button">
                                            <span x-text="selectedName"></span>
                                            <x-icons.arrow-nav
                                                x-bind:class="{
                                                    'text-black': selectedName !== 'Pilih Salah Satu',
                                                    'text-tertiary-200': selectedName === 'Pilih Salah Satu'
                                                }" />
                                        </button>
                                        <input type="hidden" x-bind:name="name" x-bind:value="selected">
                                        <div x-show="open" x-cloak @click.away="open = false"
                                            class="w-full bg-tertiary rounded-b-2xl shadow-l-rb-outer py-2 px-6 border-t-0">
                                            <input type="text" x-model="search" placeholder="Cari..."
                                                class="w-full p-3 text-sm text-gray-900 outline-none ring-2 ring-tertiary-300 rounded-lg bg-gray-50 focus:ring-primary">
                                            <ul class="py-2 text-sm text-gray-700 max-h-52 overflow-y-auto">
                                                <template
                                                    x-for="(availableItem, availableIndex) in items.filter(i => i.name.toLowerCase().includes(search.toLowerCase()))"
                                                    :key="availableIndex">
                                                    <li>
                                                        <a class="block p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer"
                                                            @click.prevent="selected = availableItem.slug; open = false">
                                                            <span x-text="availableItem.name"></span>
                                                        </a>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pcs --}}
                                <x-textfield class="focus:ring focus:ring-primary" type="number" min="0"
                                    placeholder="0" :value="old('pcs', 0)" oninput="this.value = Math.max(0, this.value)"
                                    x-model="item.pcs">
                                    Pcs
                                </x-textfield>

                                {{-- Qty --}}
                                <x-textfield class="focus:ring focus:ring-primary" type="number" min="0"
                                    placeholder="0" :value="old('qty', 0)" oninput="this.value = Math.max(0, this.value)"
                                    x-model="item.qty">
                                    Qty
                                </x-textfield>
                            </div>
                        </div>
                    </template>

                    {{-- Tombol Tambah --}}
                    <div class="flex justify-center">
                        <button type="button" @click="addItem()"
                            class="flex gap-2 px-4 py-2 bg-secondary-blue text-white rounded-full font-semibold transition-all duration-200 hover:scale-110 hover:shadow-drop active:scale-90">
                            <x-icons.plus class="w-4 -mt-0.5" />
                            <span class="text-sm">Tambah</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Hidden input buat kirim JSON full --}}
            <input type="hidden" name="purchase" :value="JSON.stringify({ supplier: supplier, items: items })">

            {{-- Tombol Submit --}}
            <div class="flex justify-center gap-6 mt-8">
                <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                    <a href="{{ route('purchase-order.index') }}">Batal</a>
                </x-button-sm>
                <x-button-sm type="submit" class="bg-primary/20 text-primary w-fit px-7">
                    Simpan
                </x-button-sm>
            </div>
        </form>
    </div>

    {{-- Alpine Logic --}}
    <script>
        function purchaseOrderForm() {
            let raw = document.getElementById('purchaseData').textContent;
            let oldData = {
                supplier: '',
                items: []
            };

            if (raw) {
                try {
                    oldData = JSON.parse(raw);
                } catch {}
            }

            if (oldData.items.length === 0) {
                oldData.items.push({
                    id: null, // <--- UBAH INI: Pastikan item pertama juga null ID-nya
                    product: '',
                    pcs: 0, // atau 1, tergantung validasi min:
                    qty: 0, // atau 1, tergantung validasi min:
                    show: true
                });
            }

            return {
                supplier: oldData.supplier,
                items: oldData.items,
                addItem() {
                    this.items.push({
                        id: null,
                        product: '',
                        pcs: 0,
                        qty: 0,
                        show: false
                    });
                },
                removeItem(id) {
                    this.items = this.items.filter(i => i.id !== id);
                }
            }
        }
    </script>

</x-layout>
