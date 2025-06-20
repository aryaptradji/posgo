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

    <div class="mt-10" x-data="{
        supplier: '',
        items: [
            { id: Date.now(), product: '', pcs: 0, qty: 0, show: false }
        ],
        addItem() {
            this.items.push({ id: Date.now() + Math.random(), product: '', pcs: 0, qty: 0 });
        },
        removeItem(id) {
            this.items = this.items.filter(item => item.id !== id);
        }
    }">

        <form action="#" method="POST">
            @csrf

            {{-- Supplier --}}
            <x-dropdown-search-nourl name="supplier" contClass="w-1/2 mb-10" :items="$suppliers->map(fn($s) => ['slug' => $s->slug, 'name' => $s->name])->toArray()" x-model="supplier">
                Supplier
            </x-dropdown-search-nourl>

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
                                <button type="button" @click="item.show = false; setTimeout(() => removeItem(item.id), 500);"
                                    class="text-danger transition-transform hover:scale-125 active:scale-90">
                                    <x-icons.delete-icon />
                                </button>
                            </div>

                            <div class="flex justify-between gap-14">
                                {{-- Produk --}}
                                <x-dropdown-search-nourl contClass="w-3/5" :items="$products
                                    ->map(fn($p) => ['slug' => $p->slug, 'name' => $p->name])
                                    ->toArray()"
                                    x-bind:name="'products[' + index + '][product]'" x-model="item.product">
                                    Produk <span x-text="index + 1"></span>
                                </x-dropdown-search-nourl>

                                {{-- Pcs --}}
                                <x-textfield class="focus:ring focus:ring-primary" type="number" placeholder="0"
                                    x-bind:name="'products[' + index + '][pcs]'" x-model="item.pcs">
                                    Pcs
                                </x-textfield>

                                {{-- Qty --}}
                                <x-textfield class="focus:ring focus:ring-primary" type="number" placeholder="0"
                                    x-bind:name="'products[' + index + '][qty]'" x-model="item.qty">
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

            {{-- Tombol Submit --}}
            <div class="flex justify-end mt-8">
                <x-button-sm type="submit" class="bg-primary text-white px-8 py-3 shadow-outer-sidebar-primary">
                    Simpan
                </x-button-sm>
            </div>

        </form>
    </div>
</x-layout>
