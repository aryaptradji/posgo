<x-layout>
    <x-slot:title>Detail Purchase Order</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <div class="flex mb-2 items-center gap-2 text-sm text-tertiary-title">
                    <a href="{{ route('purchase-order.index') }}"
                        class="font-semibold transition-all duration-300 hover:text-secondary-purple hover:scale-110 active:scale-90">Kelola
                        PO</a>
                    <x-icons.arrow-down class="mb-0.5 -rotate-90 text-tertiary-300" />
                    <span class="font-semibold">Detail</span>
                </div>
                <div>
                    Detail Purchase Order
                </div>
            </div>
            <div class="flex justify-center items-center">
                <a href="{{ route('purchase-order.print-invoice', $po) }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print />
                    Invoice
                </a>
            </div>
        </div>
    </x-slot:header>

    @php
        $class =
            $po->status === 'perlu dikirim'
                ? 'bg-danger/15 text-danger border-danger'
                : ($po->status === 'perlu invoice'
                    ? 'bg-secondary-blue/15 text-secondary-blue border-secondary-blue'
                    : 'bg-success/15 text-success border-success');
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

    <div class="shadow-outer py-4 mb-6 rounded-xl flex flex-col">
        <div class="text-lg px-6 font-bold pb-4 border-b border-tertiary-title-line">Produk yang dipesan</div>

        <div class="pt-4 relative overflow-x-auto overflow-y-auto">
            <table class="w-full min-w-max text-left">
                <thead class="text-sm uppercase bg-white">
                    <tr>
                        <th class="w-1/12" align="center">No</th>
                        <th class="w-6/12 px-8 py-3" align="left">Produk</th>
                        <th class="pe-28 py-3" align="right">Pcs</th>
                        <th class="pe-28 py-3" align="right">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($po->items as $index => $item)
                        <tr class="border-b-2 border-b-tertiary-table-line">
                            <td class="w-1/12" align="center">{{ $index + 1 }}</td>
                            <td class="w-6/12 px-8 py-3" align="left">{{ $item->product->name }}</td>
                            <td class="pe-28 py-3" align="right">{{ $item->pcs }}</td>
                            <td class="pe-28 py-3" align="right">{{ $item->qty }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold text-lg">
                        <td class="px-10 pt-3" colspan="2" align="left">TOTAL</td>
                        <td class="pt-3" align="center">{{ $po->items->sum('pcs') }}</td>
                        <td class="pt-3" align="center">{{ $po->items->sum('qty') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
