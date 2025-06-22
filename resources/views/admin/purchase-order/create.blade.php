<x-layout>
    <x-slot:title>Buat Purchase Order</x-slot:title>
    <x-slot:header>
        <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
            <a href="{{ route('purchase-order.index') }}"
                class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Kelola
                PO</a>
            <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
            <span class="font-semibold">Buat</span>
        </div>
        <div>
            Buat Purchase Order
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

    {{-- Hidden preload data --}}
    <script type="application/json" id="purchaseData">
        {!! json_encode(
            old('purchase')
                ? json_decode(old('purchase'), true)
                : ['supplier' => '', 'items' => [['id' => time(), 'product' => '', 'pcs' => 0, 'qty' => 0]]]
        ) !!}
    </script>

    <div class="mt-10 mb-4" x-data="purchaseOrderForm()">
        <form action="{{ route('purchase-order.store') }}" method="POST" x-ref="form">
            @csrf
            {{-- Supplier --}}
            <div x-data x-on:selected-change="supplier = $event.detail" class="w-1/2 mb-10">
                <x-dropdown-search-nourl name="supplier" :items="$suppliers->map(fn($s) => ['slug' => $s->slug, 'name' => $s->name])->toArray()">
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
                                <div class="w-3/5" x-data x-on:selected-change="item.product = $event.detail">
                                    <x-dropdown-search-nourl x-bind:name="'product-' + item.id" contClass="w-full"
                                        :items="$products
                                            ->map(fn($p) => ['slug' => $p->slug, 'name' => $p->name])
                                            ->toArray()">
                                        Produk <span x-text="index + 1"></span>
                                    </x-dropdown-search-nourl>
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
                <x-button-sm type="submit" class="bg-secondary-purple/20 text-secondary-purple w-fit px-7">
                    Buat
                </x-button-sm>
            </div>
        </form>
    </div>

    {{-- Alpine Logic --}}
    <script>
        function purchaseOrderForm() {
            let rawJson = document.getElementById('purchaseData').textContent;
            let oldData = {
                supplier: '',
                items: [{
                    id: Date.now(),
                    product: '',
                    pcs: 0,
                    qty: 0
                }]
            };

            if (rawJson) {
                try {
                    oldData = JSON.parse(rawJson);
                } catch (e) {}
            }

            oldData.items = oldData.items.map(i => ({
                ...i,
                show: true,
                id: i.id || (Date.now() + Math.random())
            }));

            return {
                supplier: oldData.supplier,
                items: oldData.items,
                addItem() {
                    this.items.push({
                        id: Date.now() + Math.random(),
                        product: '',
                        pcs: 0,
                        qty: 0,
                        show: true
                    });
                },
                removeItem(id) {
                    this.items = this.items.filter(item => item.id !== id);
                }
            }
        }
    </script>

</x-layout>
