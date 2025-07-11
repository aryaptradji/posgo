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
            @if ($po->status === 'perlu invoice')
                <div class="flex justify-center items-center">
                    <a href="{{ route('purchase-order.fill-invoice', $po) }}"
                        class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-success transition-all hover:scale-105 active:scale-90">
                        <x-icons.invoice />
                        Isi Invoice
                    </a>
                </div>
            @elseif ($po->status === 'perlu dikirim')
                <div class="flex justify-center items-center">
                    <a href="{{ route('purchase-order.print-invoice', $po) }}"
                        class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                        <x-icons.print />
                        Cetak Invoice
                    </a>
                </div>
            @endif
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
                        <th class="px-8 py-3" align="left">Produk</th>
                        <th class="pe-28 py-3" align="right">Pcs</th>
                        <th class="pe-28 py-3" align="right">Qty</th>
                        @if ($po->status === 'perlu invoice' || $po->status === 'siap')
                            <th class="pe-10 py-3" align="right">Harga per qty</th>
                            <th class="pe-10 py-3" align="right">Total harga produk</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($po->items as $index => $item)
                        <tr class="border-b-2 border-b-tertiary-table-line">
                            <td class="w-1/12" align="center">{{ $index + 1 }}</td>
                            <td class="px-8 py-3" align="left">{{ $item->product->name }}</td>
                            <td class="pe-28 py-3" align="right">{{ $item->pcs }}</td>
                            <td class="pe-28 py-3" align="right">{{ $item->qty }}</td>
                            @if ($po->status === 'perlu invoice' || $po->status === 'siap')
                                <td class="pe-10 py-3" align="right">
                                    {{ $item->price === 0 ? '-' : 'Rp' . number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="pe-10 py-3" align="right">
                                    {{ $item->price === 0 ? '-' : 'Rp' . number_format($item->qty * $item->price, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    @if ($po->status === 'siap' || $po->status === 'perlu invoice')
                        {{-- Baris Total Tabel --}}
                        <tr class="font-bold text-lg">
                            <td class="px-10 pt-3" colspan="5" align="left">SUBTOTAL</td>
                            <td class="pt-3 pe-10" align="right">
                                <span>{{ $po->subtotal === 0 ? '-' : 'Rp' . number_format($po->subtotal, 0, ',', '.') }}</span>
                            </td>
                        </tr>

                        {{-- PPN --}}
                        <tr class="font-bold text-lg">
                            <td class="px-10 pt-3 pb-4" colspan="5" align="left">
                                <div class="flex items-center gap-2">
                                    <span>PPN</span>
                                </div>
                            </td>
                            <td class="pt-3 pe-10 pb-4" align="right">
                                <span>{{ $po->ppn_percentage == 0 ? '-' : number_format($po->ppn_percentage, 0, ',', '.') . '%' }}</span>
                            </td>
                        </tr>
                    @endif

                    <tr class="font-bold text-lg pt-4 border-t border-tertiary-title-line">
                        <td class="pt-3 px-10" colspan="2" align="left">TOTAL</td>
                        <td class="pt-3 pe-28" align="right">{{ $po->items->sum('pcs') }}</td>
                        <td class="pt-3 pe-28" align="right">{{ $po->items->sum('qty') }}</td>
                        @if ($po->status === 'perlu invoice' || $po->status === 'siap')
                            <td class="pt-3 pe-10" align="right" colspan="2">
                                {{ $po->total === 0 ? '-' : 'Rp' . number_format($po->total, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
