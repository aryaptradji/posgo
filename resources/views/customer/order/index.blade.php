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
                <input type="hidden" name="status" value="{{ request('status') }}">
                @if (request('search'))
                    <a href="{{ route('customer.order.index', request()->except(['search'])) }}"
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
    ] as $key => $label)
                    <a href="{{ route('customer.order.index', ['status' => $key]) }}"
                        class="group flex flex-col items-center transition-all">
                        <span class="{{ $status === $key ? 'text-secondary-purple' : 'text-tertiary-title' }}">
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
                    @php
                        $payMethod = strtolower($order->payment_method ?? '');

                        $methodClass =
                            $payMethod == 'bank mandiri'
                                ? 'bg-warning-100/15 text-warning-100 border-warning-100'
                                : ($payMethod == 'bank bca'
                                    ? 'bg-blue-600/15 text-blue-600 border-blue-600'
                                    : ($payMethod == 'bank bni'
                                        ? 'bg-primary/15 text-primary border-primary'
                                        : (str_starts_with($payMethod, 'qris')
                                            ? 'bg-success/15 text-success border-success'
                                            : 'border-none')));
                        $statusClass =
                            $status == 'belum-dibayar'
                                ? 'bg-primary/15 text-primary'
                                : ($status == 'dikemas'
                                    ? 'bg-warning-200/15 text-warning-200'
                                    : ($status == 'dikirim'
                                        ? 'bg-secondary-blue/15 text-secondary-blue'
                                        : ($status == 'selesai'
                                            ? 'bg-success/15 text-success'
                                            : 'bg-danger/15 text-danger')));
                    @endphp

                    <div class="rounded-xl p-8 shadow-outer flex flex-col gap-2 relative" x-data="paymentHandler()">
                        {{-- Modal Expired --}}
                        <x-alert x-show="showExpiredModal">
                            <x-slot:title>
                                <div class="w-full flex justify-start">
                                    <div class="flex">
                                        <x-icons.alert class="mr-3 text-danger" />
                                        <h2 class="text-lg font-bold">Peringatan</h2>
                                    </div>
                                </div>
                            </x-slot:title>
                            <div class="px-8 mb-6">
                                Maaf pesanan ini sudah <span class="text-danger font-semibold">kadaluwarsa</span>,
                                silahkan
                                pesan lagi.
                            </div>
                            <x-slot:action>
                                <form :action="expireActionUrl" method="GET">
                                    <button type="submit"
                                        class="px-4 py-2 bg-danger text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        OK
                                    </button>
                                </form>
                            </x-slot:action>
                        </x-alert>

                        {{-- Modal Error --}}
                        <x-alert x-show="showErrorModal">
                            <x-slot:title>
                                <div class="flex items-center">
                                    <x-icons.alert class="mr-2 text-danger" />
                                    <h2 class="text-lg font-bold">Error Pembayaran</h2>
                                </div>
                            </x-slot:title>
                            <div class="px-8 mb-6">
                                Token pembayaran tidak tersedia untuk pesanan
                                #{{ $order ? $order->code : request('snap_error') }}
                            </div>
                            <x-slot:action>
                                <form :action="errorActionUrl" method="GET">
                                    <button type="submit"
                                        class="px-4 py-2 bg-danger text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                        OK
                                    </button>
                                </form>
                            </x-slot:action>
                        </x-alert>


                        <div class="flex justify-between items-start">
                            <div class="flex flex-col">
                                <div class="flex justify-between gap-4">
                                    <div>
                                        <div class="font-semibold">#{{ $order->code }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $order->time->translatedFormat('d M Y H:i:s') }}</div>
                                    </div>
                                    <div
                                        class="border-2 w-fit h-fit rounded-full px-2 py-1 text-xs uppercase {{ $methodClass }}">
                                        {{ $order->payment_method }}</div>
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
                                    class="flex justify-center items-center font-bold text-sm uppercase w-fit px-3 py-2 rounded-full {{ $statusClass }}">
                                    {{ $filterStatus }}
                                </div>
                                <div class="text-xl font-bold mt-2">Rp
                                    {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div x-data="{ showAllItems: false }">
                            @foreach ($order->items as $index => $item)
                                <div x-show="showAllItems || {{ $index }} === 0" x-cloak
                                    x-transition:enter="transition-all ease duration-500"
                                    x-transition:enter-start="-translate-y-4 opacity-0"
                                    x-transition:enter-end="translate-y-0 opacity-100"
                                    x-transition:leave="transition-all ease duration-500"
                                    x-transition:leave-start="translate-y-0 opacity-100"
                                    x-transition:leave-end="-translate-y-4 opacity-0"
                                    class="flex items-center gap-3 mt-4 w-fit">
                                    <div
                                        class="flex items-center justify-center bg-gradient-to-br from-primary/60 to-secondary-purple/60 h-20 p-3 aspect-square object-contain rounded-xl">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="max-h-16">
                                    </div>
                                    <div class="flex h-full flex-col gap-4 justify-between">
                                        <div class="flex flex-col">
                                            <span class="font-bold">{{ $item->product->name }}</span>
                                            <span class="text-tertiary-title text-xs">{{ $item->product->pcs }}
                                                pcs</span>
                                        </div>
                                        <div class="flex justify-between gap-16 min-w-fit">
                                            <span class="font-semibold text-tertiary-500">{{ $item->qty }}x</span>
                                            <span class="font-semibold">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            @if ($order->items->count() > 1)
                                <button @click="showAllItems = !showAllItems"
                                    class="text-sm text-blue-600 mt-4 transition-all hover:scale-90 hover:opacity-65 active:scale-75">
                                    <div x-cloak x-show="!showAllItems" class="flex items-center justify-between gap-2">
                                        <span>More {{ $order->items->count() - 1 }} items</span>
                                        <x-icons.arrow-down />
                                    </div>
                                    <div x-cloak x-show="showAllItems" class="flex items-center justify-between gap-2">
                                        <span>Tutup</span>
                                        <x-icons.arrow-down class="rotate-180" />
                                    </div>
                                </button>
                            @endif
                            @if (request()->status == 'belum-dibayar')
                                <x-button-sm @click="handlePayClick($event.target.closest('button'))"
                                    class="absolute bottom-8 right-8 w-fit py-1 px-10 bg-secondary-purple transition-all duration-300 hover:shadow-xl text-white"
                                    id="btn-pay-{{ $order->id }}" type="button"
                                    data-snap-token="{{ $order->snap_token }}"
                                    data-snap-expires-at="{{ $order->snap_expires_at->timestamp ?? 0 }}"
                                    data-order-code="{{ $order->code }}"
                                    data-expire-url="{{ route('customer.order.expire', ['order' => '__ORDER_CODE__']) }}">
                                    Bayar
                                </x-button-sm>
                            @endif
                        </div>
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

            <form method="GET" action="{{ route('customer.order.index') }}" class="flex items-center gap-2">
                <label for="per_page" class="text-sm text-gray-600">Per page:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="p-2 outline-none border border-gray-300 focus:border-primary focus:border-2 rounded-full text-sm bg-tertiary text-black">
                    @foreach ([2, 5, 10, 20] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                @if (request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <input type="hidden" name="status" value="{{ request('status') }}">
            </form>

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

    <script type="text/javascript">
        function paymentHandler() {
            return {
                showExpiredModal: false,
                showErrorModal: false,
                expireOrderCode: null,
                expireActionUrl: '',
                errorOrderCode: null,
                errorActionUrl: '',

                handlePayClick(button) {
                    const snapToken = button.dataset.snapToken;
                    const snapExpiresAt = parseInt(button.dataset.snapExpiresAt) * 1000;
                    const orderCode = button.dataset.orderCode;
                    const now = new Date().getTime();

                    console.log({
                        snapToken,
                        snapExpiresAt,
                        orderCode,
                        now
                    });

                    // Handle snapToken kosong
                    if (!snapToken || snapToken.trim() === '') {
                        this.errorOrderCode = orderCode;
                        this.errorActionUrl = button.dataset.expireUrl.replace('__ORDER_CODE__', orderCode);
                        this.showErrorModal = true;
                        return;
                    }


                    console.log({
                        snapToken,
                        snapExpiresAt,
                        snapExpiresAt_human: new Date(snapExpiresAt).toISOString(),
                        now,
                        now_human: new Date(now).toISOString(),
                        comparison: now > snapExpiresAt
                    });

                    if (now > snapExpiresAt) {
                        this.expireOrderCode = orderCode;
                        this.expireActionUrl = button.dataset.expireUrl.replace('__ORDER_CODE__', orderCode);
                        this.showExpiredModal = true;
                    } else {
                        window.snap.pay(snapToken, {
                            onSuccess: function(result) {
                                window.location.href =
                                    "{{ route('customer.order.index', ['status' => 'dikemas']) }}";
                            },
                            onPending: function(result) {
                                window.location.href =
                                    "{{ route('customer.order.index', ['status' => 'belum-dibayar']) }}";
                            },
                            onError: function(result) {
                                alert('Terjadi kesalahan saat pembayaran.');
                                console.error(result);
                                window.location.href =
                                    "{{ route('customer.order.index', ['status' => 'belum-dibayar']) }}";
                            },
                            onClose: function() {
                                window.location.href =
                                    "{{ route('customer.order.index', ['status' => 'belum-dibayar']) }}";
                            }
                        });
                    }
                }
            }
        }
    </script>
</x-layout-main>
