<x-layout>
    <x-slot:title>Riwayat</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Riwayat</span>
            <div class="flex gap-6">
                <a href="{{ route('expense.print') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print />
                    Print
                </a>
                <a href="{{ route('expense.export') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-primary transition-all hover:scale-105 active:scale-90">
                    <x-icons.export />
                    Export
                </a>
            </div>
        </div>
    </x-slot:header>

    @if (session('success'))
        <div class="fixed top-16 right-10 z-20 flex flex-col gap-4">
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {{ session('success') }}
            </x-toast>
        </div>
    @endif

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-between">
                <div class="w-fit flex gap-4 items-center justify-center font-semibold">
                    @foreach (['semua', 'selesai', 'dalam perjalanan', 'belum dikirim'] as $status)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $status, 'page' => 1]) }}"
                            class="px-3 py-2 rounded-lg capitalize transition-all duration-1000 cursor-pointer {{ request('filter', 'semua') === $status ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black' }}">
                            {{ $status }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('order.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('order.index', request()->except(['search', 'filter', 'page'])) }}"
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
                                    'sort' => 'code',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Kode
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'code' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'time',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Waktu
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'time' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'name',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Nama
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'name' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'category',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Kategori
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'category' && request('desc') ? 'rotate-180' : '' }}" />
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
                        @forelse ($orders as $order)
                            @php
                                $class =
                                    $order->status === 'belum dikirim'
                                        ? 'bg-danger/15 text-danger border-danger'
                                        : ($order->status === 'dalam perjalanan'
                                            ? 'bg-warning-200/15 text-warning-200 border-warning-200'
                                            : 'bg-success/15 text-success border-success');
                            @endphp
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="left">{{ $order->code }}</td>
                                <td class="px-4 py-2" align="left">
                                    {{ $order->time->translatedFormat('d M Y H:i:s') }}</td>
                                <td class="px-4 py-2" align="center">{{ $order->user->name }}</td>
                                <td class="px-4 py-2 capitalize" align="center">{{ $order->category }}</td>
                                <td class="px-4 py-4" align="center">
                                    <span
                                        class="px-2 py-1 rounded-lg capitalize border-2 {{ $class }}">{{ $order->status }}</span>
                                </td>
                                <td class="px-4 py-2" align="center">{{ $order->item }}</td>
                                <td class="px-4 py-2" align="left">Rp
                                    {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModalView: false }">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" @click="showModalView = true"
                                            class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon />
                                        </button>
                                        <template x-if="{{ $order->status === 'belum dikirim' }}">
                                            <button type="button"
                                                class="text-secondary-blue transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.send-icon />
                                            </button>
                                        </template>
                                        <template x-if="{{ $order->status === 'dalam perjalanan' }}">
                                            <button type="button"
                                                class="transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.upload-icon />
                                            </button>
                                        </template>
                                    </div>

                                    <!-- Modal View -->
                                    <x-modal show="showModalView">
                                        <x-slot:title>
                                            <div class="w-full flex justify-between">
                                                <div class="flex">
                                                    <x-icons.info-icon class="mr-3" />
                                                    <h2 class="text-lg font-bold">Detail Pesanan</h2>
                                                </div>
                                                <button
                                                    class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                                    type="button" @click="showModalView = false">
                                                    <x-icons.close />
                                                </button>
                                            </div>
                                        </x-slot:title>
                                        <div class="px-10 mb-2">
                                            <div class="grid grid-cols-2 gap-4 text-start">
                                                <div class="flex flex-col gap-4 col-span-1 justify-self-start">
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Kode</span>
                                                        <span>{{ $order->code }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Waktu</span>
                                                        <span>{{ $order->time }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Nama</span>
                                                        <span>{{ $order->user->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col gap-4 col-span-1 justify-self-end">
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Kategori</span>
                                                        <span class="capitalize">{{ $order->category }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Status</span>
                                                        <span
                                                            class="px-2 rounded-lg capitalize border-2 w-fit {{ $class }}">{{ $order->status }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-6 py-3 rounded-lg bg-gradient-to-r from-primary/30 to-secondary-purple/30">
                                                <header class="px-4 text-start font-bold">Summary</header>
                                                <hr
                                                    class="w-full h-[1px] my-2 bg-tertiary-table-line rounded-full border-0">
                                                <div class="relative overflow-x-auto overflow-y-auto">
                                                    <table class="w-full min-w-max text-sm text-left">
                                                        <thead class="text-xs uppercase">
                                                            <tr>
                                                                <th class="px-4 py-2 w-44" align="left">Nama Produk
                                                                </th>
                                                                <th class="px-4 py-2" align="center">Pcs</th>
                                                                <th class="px-4 py-2" align="center">Qty</th>
                                                                <th class="px-4 py-2" align="center">Item</th>
                                                                <th class="px-4 py-2" align="center">Harga</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($order->items as $item)
                                                                <tr class="font-medium text-xs">
                                                                    <td class="px-4 py-2" align="left">
                                                                        {{ $item->product->name }}</td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $item->product->pcs }}</td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $item->qty }}</td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $item->product->pcs * $item->qty }}</td>
                                                                    <td class="px-4 py-2" align="left">Rp
                                                                        {{ number_format($item->product->price, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr class="font-bold border-t border-tertiary-table-line">
                                                                <td class="px-4 py-2" align="left">TOTAL</td>
                                                                <td class="px-4 py-2" align="center">
                                                                    {{ $order->items->sum(function ($item) {
                                                                        return $item->product->pcs;
                                                                    }) }}
                                                                </td>
                                                                <td class="px-4 py-2" align="center">
                                                                    {{ $order->items->sum(function ($item) {
                                                                        return $item->qty;
                                                                    }) }}
                                                                </td>
                                                                <td class="px-4 py-2" align="center">
                                                                    {{ $order->items->sum(function ($item) {
                                                                        return $item->product->pcs * $item->qty;
                                                                    }) }}
                                                                </td>
                                                                @php
                                                                    $totalHarga = $order->items->sum(function ($item) {
                                                                        return $item->product->price;
                                                                    });
                                                                @endphp
                                                                <td class="px-4 py-2" align="left">
                                                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500 italic">Data pesanan tidak
                                    ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                    {{ $orders->total() }}
                </span>

                <form method="GET" action="{{ route('order.index') }}" class="flex items-center gap-2">
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
                    @if ($orders->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if ($page == $orders->currentPage())
                            <span
                                class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
