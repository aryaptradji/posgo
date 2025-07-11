<x-layout>
    <x-slot:title>Kelola PO</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Purchase Order</span>
            <div class="flex gap-6">
                <a href="{{ route('purchase-order.print') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print />
                    Print
                </a>
                <a href="{{ route('purchase-order.export') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-success transition-all hover:scale-105 active:scale-90">
                    <x-icons.export />
                    Export
                </a>
                <a href="{{ route('purchase-order.create') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple transition-all hover:scale-105 active:scale-90">
                    <x-icons.plus />
                    Buat
                </a>
            </div>
        </div>
    </x-slot:header>

    @if (session('success'))
        <div class="fixed top-16 right-10 z-20 flex flex-col gap-4">
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success"
                :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {{ session('success') }}
            </x-toast>
        </div>
    @endif

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-20 flex flex-col items-end gap-4">
            @foreach ($errors->all() as $error)
                <x-toast id="toast-failed{{ $loop->index }}" iconClass="text-danger bg-danger/25"
                    slotClass="text-danger" :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-failed />
                    </x-slot:icon>
                    {{ $error }}
                </x-toast>
            @endforeach
        </div>
    @endif

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-between">
                <div class="w-fit flex gap-4 items-center justify-center font-semibold">
                    @foreach (['semua', 'perlu dikirim', 'perlu invoice', 'perlu dibayar', 'selesai'] as $category)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $category, 'page' => 1]) }}"
                            class="px-3 py-2 rounded-lg capitalize transition-all duration-1000 cursor-pointer {{ request('filter', 'semua') === $category ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('purchase-order.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('purchase-order.index', request()->except(['search', 'filter', 'page'])) }}"
                            class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                            <x-icons.close />
                        </a>
                    @endif
                </form>
            </div>

            <div class="pb-4 relative overflow-x-auto overflow-y-auto">
                <table class="w-full min-w-max text-sm text-left">
                    <thead class="text-xs uppercase bg-white">
                        <tr>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'created',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Waktu Dibuat
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'created' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">Nomor PO</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'supplier',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Supplier
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'supplier' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">Status</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'item',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Item
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'item' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'total',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Total
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'total' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchase_orders as $po)
                            @php
                                $class =
                                    $po->status === 'perlu dikirim'
                                        ? 'bg-danger/15 text-danger border-danger'
                                        : ($po->status === 'perlu invoice'
                                            ? 'bg-warning-100/15 text-warning-100 border-warning-100'
                                            : ($po->status === 'perlu dibayar'
                                                ? 'bg-success/15 text-success border-success'
                                                : 'bg-secondary-blue/15 text-secondary-blue border-secondary-blue'));
                            @endphp
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="center">
                                    {{ $po->created->translatedFormat('d M Y H:i:s') }}</td>
                                <td class="px-4 py-2" align="center">{{ $po->code }}</td>
                                <td class="px-4 py-2 w-64" align="center">{{ $po->supplier->name }}</td>
                                <td class="px-4 py-4" align="center">
                                    <span
                                        class="px-2 py-1 rounded-lg capitalize border-2 {{ $class }}">{{ $po->status }}</span>
                                </td>
                                <td class="px-4 py-2" align="center">{{ $po->item }}</td>
                                <td class="px-4 py-2" align="center">Rp
                                    {{ number_format($po->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModalDelete: false, showModalSend: false, showModalPay: false, showModalView: false }">
                                    <div class="flex justify-center gap-2">
                                        <template
                                            x-if="{{ $po->status !== 'perlu dibayar' && $po->status !== 'selesai' }}">
                                            <a href="{{ route('purchase-order.show', $po) }}"
                                                class="text-secondary-purple transition-transform hover:scale-125 active:scale-90 mt-1">
                                                <x-icons.detail-icon />
                                            </a>
                                        </template>
                                        <template x-if="{{ $po->status === 'selesai' }}">
                                            <button type="button" @click="showModalView = true"
                                                class="text-secondary-purple transition-transform hover:scale-125 active:scale-90 mt-1">
                                                <x-icons.detail-icon />
                                            </button>
                                        </template>
                                        <template x-if="{{ $po->status === 'perlu dikirim' }}">
                                            <a href="{{ route('purchase-order.edit', $po) }}"
                                                class="text-primary transition-transform hover:scale-125 active:scale-90 mt-1">
                                                <x-icons.edit-icon />
                                            </a>
                                        </template>
                                        <template x-if="{{ $po->status === 'perlu dikirim' }}">
                                            <button type="button" @click="showModalDelete = true"
                                                class="text-danger transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.delete-icon />
                                            </button>
                                        </template>
                                        <template x-if="{{ $po->status === 'perlu dikirim' }}">
                                            <button type="button" @click="showModalSend = true"
                                                class="text-warning-100 transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.send-icon />
                                            </button>
                                        </template>
                                        <template x-if="{{ $po->status === 'perlu invoice' }}">
                                            <a href="{{ route('purchase-order.fill-invoice', $po) }}"
                                                class="text-success transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.invoice-sm />
                                            </a>
                                        </template>
                                        <template x-if="{{ $po->status === 'perlu dibayar' }}">
                                            <button type="button" @click="showModalPay = true"
                                                class="text-secondary-blue transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.pay />
                                            </button>
                                        </template>
                                    </div>

                                    {{-- Modal Delete --}}
                                    <x-modal show="showModalDelete">
                                        <x-slot:title>
                                            <x-icons.delete-icon class="text-danger mr-3 mt-0.5" />
                                            <h2 class="text-lg font-bold">Hapus Purchase Order</h2>
                                        </x-slot:title>
                                        <p class="mb-6 mx-1 mt-4 text-start">
                                            Yakin ingin menghapus data Purchase Order
                                            <span class="font-bold text-danger">#{{ $po->code }}</span>
                                            ini?
                                        </p>
                                        <x-slot:action>
                                            <button type="button" @click="showModalDelete = false"
                                                class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                Batal
                                            </button>

                                            <form action="{{ route('purchase-order.destroy', $po) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-danger text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                    Hapus
                                                </button>
                                            </form>
                                        </x-slot:action>
                                    </x-modal>

                                    {{-- Modal Kirim --}}
                                    <x-modal show="showModalSend">
                                        <x-slot:title>
                                            <x-icons.delivery class="mr-3 text-warning-100" />
                                            <h2 class="text-lg font-bold">Kirim Purchase Order</h2>
                                        </x-slot:title>
                                        <p class="mb-6 mx-1 mt-4 text-start">
                                            Yakin ingin mengirim Purchase Order
                                            <span class="font-bold text-warning-100">#{{ $po->code }}</span>
                                            ini?
                                        </p>
                                        <x-slot:action>
                                            <button type="button" @click="showModalSend = false"
                                                class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                Batal
                                            </button>

                                            <form action="{{ route('purchase-order.kirim', $po) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-warning-100 text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                    Kirim
                                                </button>
                                            </form>
                                        </x-slot:action>
                                    </x-modal>

                                    {{-- Modal Bayar --}}
                                    <form action="{{ route('purchase-order.pay', $po) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <x-modal show="showModalPay">
                                            <x-slot:title>
                                                <div class="w-full flex justify-between">
                                                    <div class="flex">
                                                        <x-icons.pay class="mr-3 text-secondary-blue" />
                                                        <h2 class="text-lg font-bold">Upload Bukti Pembayaran</h2>
                                                    </div>
                                                    <button
                                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                                        type="button" @click="showModalPay = false">
                                                        <x-icons.close />
                                                    </button>
                                                </div>
                                            </x-slot:title>

                                            <div class="flex gap-8 px-10 mb-2">
                                                <div>
                                                    <div class="grid grid-cols-2 gap-8 text-start">
                                                        <div class="flex flex-col gap-4 col-span-1">
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Waktu Dibuat</span>
                                                                <span>{{ $po->created->translatedFormat('d M Y H:i:s') }}</span>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Nomor PO</span>
                                                                <span>{{ $po->code }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-4 col-span-1">
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Supplier</span>
                                                                <span
                                                                    class="capitalize">{{ $po->supplier->name }}</span>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Status</span>
                                                                <span
                                                                    class="px-2 rounded-lg capitalize border-2 w-fit {{ $class }}">{{ $po->status }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Summary Produk --}}
                                                    <div
                                                        class="mt-6 py-3 rounded-lg bg-gradient-to-r from-primary/30 to-secondary-purple/30">
                                                        <header class="px-4 text-start font-bold">Summary</header>
                                                        <hr
                                                            class="w-full h-[1px] my-2 bg-tertiary-table-line rounded-full border-0">

                                                        <div class="relative overflow-x-auto overflow-y-auto">
                                                            <table class="w-full min-w-max text-sm text-left">
                                                                <thead class="text-xs uppercase">
                                                                    <tr>
                                                                        <th class="px-4 py-2 w-44" align="left">Nama
                                                                            Produk</th>
                                                                        <th class="px-4 py-2" align="center">Pcs</th>
                                                                        <th class="px-4 py-2" align="center">Qty</th>
                                                                        <th class="px-4 py-2" align="right">Harga per
                                                                            qty</th>
                                                                        <th class="px-4 py-2" align="right">Total
                                                                            harga produk</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($po->items as $item)
                                                                        <tr class="font-medium text-xs">
                                                                            <td class="px-4 py-2" align="left">
                                                                                {{ $item->product->name }}</td>
                                                                            <td class="px-4 py-2" align="center">
                                                                                {{ $item->pcs }}</td>
                                                                            <td class="px-4 py-2" align="center">
                                                                                {{ $item->qty }}</td>
                                                                            <td class="px-4 py-2" align="right">
                                                                                {{ 'Rp' . number_format($item->price, 0, ',', '.') }}
                                                                            </td>
                                                                            <td class="px-4 py-2" align="right">
                                                                                {{ 'Rp' . number_format($item->qty * $item->price, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach

                                                                    {{-- Subtotal --}}
                                                                    <tr
                                                                        class="font-bold border-t border-tertiary-table-line">
                                                                        <td class="px-4 py-2" align="left"
                                                                            colspan="3">SUBTOTAL
                                                                        </td>
                                                                        <td class="px-4 py-2" colspan="2"
                                                                            align="right">
                                                                            {{ 'Rp' . number_format($po->subtotal, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>

                                                                    {{-- PPN --}}
                                                                    <tr class="font-bold">
                                                                        <td class="px-4 py-2" align="left">PPN</td>
                                                                        <td colspan="3"></td>
                                                                        <td class="px-4 py-2" align="right">
                                                                            {{ number_format($po->ppn_percentage, 0, ',', '.') . '%' }}
                                                                        </td>
                                                                    </tr>

                                                                    {{-- TOTAL --}}
                                                                    <tr
                                                                        class="font-bold text-lg border-t border-tertiary-table-line">
                                                                        <td class="px-4 py-2" align="left">TOTAL
                                                                        </td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $po->items->sum('pcs') }}</td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $po->items->sum('qty') }}</td>
                                                                        <td class="px-4 py-2" align="right"
                                                                            colspan="2">
                                                                            {{ 'Rp' . number_format($po->total, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Upload Bukti --}}
                                                <div class="h-full w-96 text-start">
                                                    <x-textfield-image name="photo[{{ $loop->index }}]"
                                                        fileNameClass="text-xs max-w-40" closeSideClass="text-xs"
                                                        previewClass="min-h-80" uploadClass="min-h-80">Bukti
                                                        Pembayaran
                                                        <span
                                                            class="ml-2 text-xs text-tertiary-400 font-semibold">(format
                                                            .jpg/.jpeg/.png, max. 3 mb)</span>
                                                    </x-textfield-image>
                                                    <div class="text-center">
                                                        <button type="submit"
                                                            class="mt-8 px-6 py-3 bg-secondary-blue text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                            Upload
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </form>

                                    {{-- Modal View --}}
                                    <x-modal show="showModalView">
                                        <x-slot:title>
                                            <div class="w-full flex justify-between">
                                                <div class="flex">
                                                    <x-icons.info-icon class="mr-3" />
                                                    <h2 class="text-lg font-bold">Detail Purchase Order</h2>
                                                </div>
                                                <button
                                                    class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                                    type="button" @click="showModalView = false">
                                                    <x-icons.close />
                                                </button>
                                            </div>
                                        </x-slot:title>

                                        <div class="flex gap-8 px-10 mb-2">
                                            <div>
                                                <div class="grid grid-cols-2 gap-8 text-start">
                                                    <div class="flex flex-col gap-4 col-span-1">
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Waktu Dibuat</span>
                                                            <span>{{ $po->created->translatedFormat('d M Y H:i:s') }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Nomor PO</span>
                                                            <span>{{ $po->code }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col gap-4 col-span-1">
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Supplier</span>
                                                            <span class="capitalize">{{ $po->supplier->name }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Status</span>
                                                            <span
                                                                class="px-2 rounded-lg capitalize border-2 w-fit {{ $class }}">{{ $po->status }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Summary Produk --}}
                                                <div
                                                    class="mt-6 py-3 rounded-lg bg-gradient-to-r from-primary/30 to-secondary-purple/30">
                                                    <header class="px-4 text-start font-bold">Summary</header>
                                                    <hr
                                                        class="w-full h-[1px] my-2 bg-tertiary-table-line rounded-full border-0">

                                                    <div class="relative overflow-x-auto overflow-y-auto">
                                                        <table class="w-full min-w-max text-sm text-left">
                                                            <thead class="text-xs uppercase">
                                                                <tr>
                                                                    <th class="px-4 py-2 w-44" align="left">Nama
                                                                        Produk</th>
                                                                    <th class="px-4 py-2" align="center">Pcs</th>
                                                                    <th class="px-4 py-2" align="center">Qty</th>
                                                                    <th class="px-4 py-2" align="right">Harga per
                                                                        qty</th>
                                                                    <th class="px-4 py-2" align="right">Total
                                                                        harga produk</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($po->items as $item)
                                                                    <tr class="font-medium text-xs">
                                                                        <td class="px-4 py-2" align="left">
                                                                            {{ $item->product->name }}</td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $item->pcs }}</td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $item->qty }}</td>
                                                                        <td class="px-4 py-2" align="right">
                                                                            {{ 'Rp' . number_format($item->price, 0, ',', '.') }}
                                                                        </td>
                                                                        <td class="px-4 py-2" align="right">
                                                                            {{ 'Rp' . number_format($item->qty * $item->price, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                                {{-- Subtotal --}}
                                                                <tr
                                                                    class="font-bold border-t border-tertiary-table-line">
                                                                    <td class="px-4 py-2" align="left"
                                                                        colspan="3">SUBTOTAL
                                                                    </td>
                                                                    <td class="px-4 py-2" colspan="2"
                                                                        align="right">
                                                                        {{ 'Rp' . number_format($po->subtotal, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>

                                                                {{-- PPN --}}
                                                                <tr class="font-bold">
                                                                    <td class="px-4 py-2" align="left">PPN</td>
                                                                    <td colspan="3"></td>
                                                                    <td class="px-4 py-2" align="right">
                                                                        {{ number_format($po->ppn_percentage, 0, ',', '.') . '%' }}
                                                                    </td>
                                                                </tr>

                                                                {{-- TOTAL --}}
                                                                <tr
                                                                    class="font-bold text-lg border-t border-tertiary-table-line">
                                                                    <td class="px-4 py-2" align="left">TOTAL
                                                                    </td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $po->items->sum('pcs') }}</td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $po->items->sum('qty') }}</td>
                                                                    <td class="px-4 py-2" align="right"
                                                                        colspan="2">
                                                                        {{ 'Rp' . number_format($po->total, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Upload Bukti --}}
                                            <div class="min-h-full w-96 text-start">
                                                <span class="block font-bold pb-2">Bukti Pembayaran</span>
                                                <div
                                                    class="h-3/4 flex justify-center items-center bg-tertiary-table-line rounded-lg border-2 object-contain border-gray-300 text-gray-500 italic">
                                                    @if ($po->photo_url)
                                                        <img src="{{ asset('storage/' . $po->photo_url) }}"
                                                            class="max-h-72">
                                                    @else
                                                        <span>Belum ada foto</span>
                                                    @endif
                                                </div>
                                                <div class="flex justify-end mt-6">
                                                    <button type="button" @click="showModalView = false"
                                                        class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500 italic">Data PO tidak
                                    ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $purchase_orders->firstItem() }} - {{ $purchase_orders->lastItem() }} dari
                    {{ $purchase_orders->total() }}
                </span>

                <form method="GET" action="{{ route('purchase-order.index') }}" class="flex items-center gap-2">
                    <label for="per_page" class="text-sm text-gray-600">Per page:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="p-2 outline-none border border-gray-300 focus:border-primary focus:border-2 rounded-full text-sm bg-tertiary text-black">
                        @foreach ([2, 5, 10, 20] as $size)
                            <option value="{{ $size }}"
                                {{ request('per_page', 5) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="desc" value="{{ request('desc') }}">
                </form>

                <div
                    class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
                    @if ($purchase_orders->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $purchase_orders->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    @php
                        $currentPage = $purchase_orders->currentPage();
                        $lastPage = $purchase_orders->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    <!-- First page -->
                    @if ($start > 1)
                        <a href="{{ $purchase_orders->url(1) }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">1</a>
                        @if ($start > 2)
                            <span class="px-3 py-2 text-gray-500">...</span>
                        @endif
                    @endif

                    <!-- Middle pages -->
                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $currentPage)
                            <span
                                class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $i }}</span>
                        @else
                            <a href="{{ $purchase_orders->url($i) }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $i }}</a>
                        @endif
                    @endfor

                    <!-- Last page -->
                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="px-3 py-2 text-gray-500">...</span>
                        @endif
                        <a href="{{ $purchase_orders->url($lastPage) }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $lastPage }}</a>
                    @endif

                    @if ($purchase_orders->hasMorePages())
                        <a href="{{ $purchase_orders->nextPageUrl() }}"
                            class="px-3 py-2 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="-rotate-90 text-tertiary-title" />
                        </a>
                    @else
                        <span class="px-3 py-2 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="-rotate-90 text-tertiary-title" />
                        </span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-layout>
