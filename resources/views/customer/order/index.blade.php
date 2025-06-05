<x-layout-main>
    <x-slot:title>Pesananku</x-slot:title>

    <div class="w-full h-full px-14 pt-32">
        <div class="flex justify-between mb-12">
            <span class="text-3xl font-bold">Pesananku</span>
            <form action="{{ route('customer.order.index') }}" method="GET"
                class="w-3/12 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                    class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                <button type="submit"
                    class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                    <x-icons.cari />
                </button>
                @if (request('search'))
                    <a href="{{ route('customer.order.index', request()->except(['search', 'page'])) }}"
                        class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                        <x-icons.close />
                    </a>
                @endif
            </form>
        </div>

        <form action="{{ route('customer.order.index') }}" method="GET">
            <div
                class="flex mb-6 gap-6 py-1.5 items-start border-b-[2px] border-b-tertiary-title-line w-fit font-semibold">
                @php
                    $status = request('status');
                @endphp

                @foreach ([
        'belum-dibayar' => 'Belum Dibayar',
        'dikemas' => 'Dikemas',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'batal' => 'Batal',
        'retur' => 'Retur',
    ] as $key => $label)
                    <a href="{{ route('customer.order.index', ['status' => $key]) }}"
                        class="group flex flex-col items-center transition-all">
                        <span
                            class="{{ $status === $key ? 'text-secondary-purple' : 'text-tertiary-title' }}">
                            {{ $label }}
                        </span>
                        <hr
                            class="h-[3.5px] relative top-2 w-full bg-secondary-purple rounded-full border-0
                    transition-all duration-300
                    transform
                    {{ $status === $key ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0 group-hover:opacity-100 group-hover:scale-x-100' }}">
                    </a>
                @endforeach
            </div>
        </form>


        @if (!$orders->isEmpty())
            <div class="flex flex-col gap-6">
                @foreach ($orders as $order)
                    <div class="rounded-xl p-4 shadow-outer flex flex-col gap-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm font-semibold">Order #{{ $order->code }}</div>
                                <div class="text-xs text-gray-500">{{ $order->time->format('d M, Y \a\t h:i A') }}</div>
                                <div class="text-xs text-gray-500">
                                    Shipping No:
                                    <a href="#"
                                        class="text-blue-600 hover:underline">{{ $order->shipping_number ?? '-' }}</a>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                @php
                                    $filterStatus = '';
                                    if ($status !== 'batal') {
                                        $filterStatus = match ($status) {
                                            'dikemas' => 'Dikemas',
                                            'dikirim' => 'Dikirim',
                                            'selesai' => 'Selesai',
                                            'retur' => 'Retur',
                                            'belum-dibayar' => 'Belum Dibayar',
                                        };
                                    } else {
                                        $filterStatus = $order->payment_status;
                                    }
                                @endphp
                                <div
                                    class="flex justify-center items-center text-sm font-medium text-gray-700 bg-gray-200 w-fit px-2 py-1 rounded">
                                    {{ $filterStatus }}
                                </div>
                                <div class="text-xl font-bold mt-2">Rp {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-3 mt-3">
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                    class="w-16 h-16 object-cover rounded">
                                <div>
                                    <div class="font-medium text-sm">{{ $item->product->name }}</div>
                                    <div class="text-xs text-gray-500">SKU: {{ $item->product->sku }}
                                    </div>
                                    <div class="text-xs text-gray-500">Qty: {{ $item->qty }}</div>
                                </div>
                            </div>
                        @endforeach

                        @if ($order->items->count() > 1)
                            <button class="text-sm text-blue-600 hover:underline mt-2">
                                More {{ $order->items->count() - 1 }} item(s)
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-3/5 flex flex-col justify-center items-center">
                <x-images.empty-order class="max-w-64" />
                <span class="italic text-gray-500">Belum ada pesanan</span>
            </div>
        @endif

        <div class="flex justify-between items-center py-10">
            <span class="text-sm italic">
                Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari
                {{ $orders->total() }}
                produk
            </span>

            <div class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
                {{-- Tombol Sebelumnya --}}
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

                {{-- Nomor Halaman --}}
                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if ($page == $orders->currentPage())
                        <span
                            class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Tombol Selanjutnya --}}
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
</x-layout-main>
