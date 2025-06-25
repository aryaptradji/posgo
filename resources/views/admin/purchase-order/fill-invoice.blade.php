<x-layout>
    <x-slot:title>Isi Invoice Purchase Order</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
                    <a href="{{ route('purchase-order.index') }}"
                        class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Kelola
                        PO</a>
                    <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
                    <span class="font-semibold">Isi Invoice</span>
                </div>
                <div>
                    Isi Invoice Purchase Order
                </div>
            </div>
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

    @php
        $class =
            $po->status === 'perlu dikirim'
                ? 'bg-danger/15 text-danger border-danger'
                : ($po->status === 'perlu invoice'
                    ? 'bg-secondary-blue/15 text-secondary-blue border-secondary-blue'
                    : 'bg-success/15 text-success border-success');

        $invoiceItemsData = $po->items
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'pcs' => $item->pcs,
                    'qty' => $item->qty,
                    'price_per_qty' => $item->price, // Ambil harga yang sudah ada (jika ada)
                    'total_price_per_product' => $item->qty * $item->price,
                ];
            })
            ->toArray();
    @endphp

    <div class="my-10 flex">
        <div class="flex flex-col flex-1 gap-8">
            {{-- Waktu Dibuat --}}
            <div class="flex flex-col gap-1">
                <span class="text-lg font-bold">Waktu Dibuat</span>
                <span>{{ $po->created->translatedFormat('d M Y H:i:s') }}</span>
            </div>

            {{-- Kode --}}
            <div class="flex flex-col gap-1">
                <span class="text-lg font-bold">Nomor PO</span>
                <span>{{ $po->code }}</span>
            </div>
        </div>
        <div class="flex flex-col flex-1 gap-8">
            {{-- Supplier --}}
            <div class="flex flex-col gap-1">
                <span class="text-lg font-bold">Supplier</span>
                <span>{{ $po->supplier->name }}</span>
            </div>

            {{-- Status --}}
            <div class="flex flex-col gap-1">
                <span class="text-lg font-bold">Status</span>
                <span
                    class="px-2 py-1 rounded-lg capitalize border-2 w-fit {{ $class }}">{{ $po->status }}</span>
            </div>
        </div>
    </div>

    {{-- Hidden preload JSON untuk Alpine --}}
    <script type="application/json" id="invoiceItemsJson">
        {!! json_encode($invoiceItemsData) !!}
    </script>

    <div class="shadow-outer py-4 mb-6 rounded-xl flex flex-col" x-data="invoiceForm()" x-init="calculateTotals()">
        {{-- Panggil calculateTotals() di sini --}}
        <div class="text-lg px-6 font-bold pb-4 border-b border-tertiary-title-line">Produk yang dipesan</div>

        <form action="{{ route('purchase-order.save-invoice', $po) }}" method="POST" x-data="invoiceForm">
            @csrf
            @method('PUT')

            <div class="pt-4 relative overflow-x-auto overflow-y-auto">
                <table class="w-full min-w-max text-left">
                    <thead class="text-sm uppercase bg-white">
                        <tr>
                            <th class="px-6" align="center">No</th>
                            <th class="px-8 py-3" align="left">Produk</th>
                            <th class="pe-14 py-3" align="right">Pcs</th>
                            <th class="pe-14 py-3" align="right">Qty</th>
                            <th class="py-3 w-64" align="center">Harga per QTY</th>
                            <th class="py-3 w-64" align="center">Total Harga Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="item.id">
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-6" align="center" x-text="index + 1"></td>
                                <td class="px-8 py-3" align="left" x-text="item.product_name"></td>
                                <td class="pe-14 py-3" align="right" x-text="item.pcs"></td>
                                <td class="pe-14 py-3" align="right" x-text="item.qty"></td>
                                <td class="px-6 py-3" align="center">
                                    <x-textfield-price-outline class="focus:ring focus:ring-primary h-6"
                                        x-model="item.price_per_qty"
                                        @input="item.total_price_per_product = item.price_per_qty * item.qty; calculateTotals()"
                                        {{-- Panggil calculateTotals() --}}>
                                        {{-- Slot label --}}
                                    </x-textfield-price-outline>
                                </td>
                                <td class="px-6 py-3" align="center">
                                    <x-textfield-price-outline class="h-6 cursor-not-allowed"
                                        x-model="item.total_price_per_product" readonly>
                                        {{-- Slot label --}}
                                    </x-textfield-price-outline>
                                </td>
                            </tr>
                        </template>

                        {{-- Baris Total Tabel --}}
                        <tr class="font-bold text-lg">
                            <td class="px-10 pt-3" colspan="4" align="left">SUBTOTAL</td>
                            <td class="pt-3 pe-6" align="right"></td>
                            <td class="pt-3 pe-6" align="right">
                                <span x-text="formatRupiah(subtotal)"></span>
                            </td>
                        </tr>

                        {{-- PPN --}}
                        <tr class="font-bold text-lg">
                            <td class="px-10 pt-3" colspan="5" align="left">
                                <div class="flex items-center gap-2">
                                    <span>PPN</span>
                                    <x-textfield-outline type="number" name="ppn_percentage"
                                        x-model.number="ppnPercentage" class="max-w-20 px-4 mb-4 text-sm text-center"
                                        @input="calculateTotals()" :value="$initialPpnPercentage ?? 0" contClass="inline-block">
                                    </x-textfield-outline>
                                    <span>%</span>
                                </div>
                            </td>
                            <td class="pt-3 pe-6" align="right">
                                <span x-text="formatRupiah(ppnAmount)"></span>
                            </td>
                        </tr>

                        {{-- Total --}}
                        <tr class="font-bold text-lg pt-4 border-t border-tertiary-title-line">
                            <td class="px-10 pt-3" colspan="2" align="left">TOTAL</td>
                            <td class="pt-3 pe-14" align="right">{{ $po->items->sum('pcs') }}</td>
                            <td class="pt-3 pe-14" align="right">{{ $po->items->sum('qty') }}</td>
                            <td class="pt-3 pe-6" align="right" colspan="2">
                                <span x-text="formatRupiah(grandTotal)"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <input type="hidden" name="items_data" :value="JSON.stringify(items)">

            {{-- Tombol Simpan Invoice --}}
            <div class="flex justify-center gap-6 mt-8">
                <x-button-sm class="w-fit px-7 text-black bg-btn-cancel">
                    <a href="{{ route('purchase-order.index') }}">Batal</a>
                </x-button-sm>
                <x-button-sm type="submit" class="bg-primary/20 text-primary w-fit px-7">
                    Simpan Invoice
                </x-button-sm>
            </div>
        </form>
    </div>

    {{-- Alpine Logic --}}
    <script>
        function invoiceForm() {
            let rawData = document.getElementById('invoiceItemsJson').textContent;
            let initialItems = [];

            if (rawData) {
                try {
                    initialItems = JSON.parse(rawData);
                } catch (e) {
                    console.error("Error parsing invoiceItemsJson:", e);
                }
            }

            initialItems = initialItems.map(item => ({
                ...item,
                price_per_qty: Number(item.price_per_qty) || 0,
                total_price_per_product: Number(item.total_price_per_product) || 0
            }));

            return {
                items: initialItems,
                ppnPercentage: '{{ $initialPpnPercentage ?? 0 }}', // Inisialisasi PPN dari controller
                subtotal: 0,
                ppnAmount: 0,
                grandTotal: 0,

                calculateTotals() {
                    this.subtotal = this.items.reduce((sum, item) => sum + item.total_price_per_product, 0);
                    this.ppnAmount = this.subtotal * (this.ppnPercentage / 100);
                    this.grandTotal = this.subtotal + this.ppnAmount;
                },

                formatRupiah(amount) {
                    return 'Rp ' + (amount || 0).toLocaleString('id-ID');
                }
            };
        }
    </script>
</x-layout>
