<x-layout-main>
    <x-slot:title>Informasi Pemesan</x-slot:title>

    @php
        $statusClass =
            $order->payment_status == 'belum dibayar' ||
            $order->payment_status == 'kadaluwarsa' ||
            $order->payment_status == 'dibatalkan' ||
            $order->payment_status == 'ditolak'
                ? 'bg-danger/15 text-danger border-danger'
                : 'bg-success/15 text-success border-success';
    @endphp

    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-50 flex flex-col items-end gap-4">
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
    @if (session('error'))
        <div class="fixed top-16 right-10 z-50 flex flex-col items-end gap-4">
            <x-toast id="toast-failed" iconClass="text-danger bg-danger/25" slotClass="text-danger" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-failed />
                </x-slot:icon>
                {{ session('error') }}
            </x-toast>
        </div>
    @endif

    {{-- Toast Success --}}
    @if (session('success'))
        <div class="fixed top-16 right-10 z-20 flex flex-col justify-end gap-4">
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success"
                :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {!! session('success') !!}
            </x-toast>
        </div>
    @endif

    {{-- Modal Print --}}
    @if (session('showPrintModal'))
        <x-modal-init>
            <x-slot:title>
                <x-icons.print class="text-secondary-blue mr-3 mt-0.5" />
                <h2 class="text-lg font-bold">Cetak Struk</h2>
            </x-slot:title>
            <p class="mb-6 px-8 mt-4 text-start">
                Apakah ingin mencetak struk pesanan
                <span class="font-bold text-secondary-blue">#{{ $order->code }}</span>
                ?
            </p>
            <x-slot:action>
                <a href="{{ route('pos-menu') }}"
                    class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                    Batal
                </a>

                <form action="{{ route('transaction.print-receipt', $order) }}" method="GET">
                    <button type="submit"
                        class="px-4 py-2 bg-secondary-blue text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                        Cetak
                    </button>
                </form>
            </x-slot:action>
        </x-modal-init>
    @endif


    <form action="{{ route('pos-menu.pay-cash.store', $order) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-grow min-h-0 gap-6 mx-14 mt-32 h-fit">
            <!-- Detail Pesanan -->
            <div class="w-3/4 shadow-outer py-6 rounded-xl flex flex-col">
                <div class="text-2xl px-8 font-bold pb-4 border-b border-tertiary-title-line">Detail Pesanan</div>
                <div class="flex px-8 gap-36 mt-6 flex-grow">
                    <div class="flex flex-col gap-8 w-1/2">
                        <div class="flex flex-col">
                            <span class="font-bold">No. Pesanan</span>
                            <span>{{ $order->code }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold">Waktu Dibuat</span>
                            <span>{{ $order->time }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold">Nama Penerima</span>
                            <span>{{ $order->user->name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold mb-1">Status Pembayaran</span>
                            <span
                                class="w-fit h-fit capitalize px-2 py-1 border-2 rounded-lg {{ $statusClass }}">{{ $order->payment_status }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold mb-1">Metode Pembayaran</span>
                            <span
                                class="w-fit h-fit capitalize px-2 py-1 border-2 rounded-lg bg-tertiary-title/15 text-tertiary-title border-tertiary-title">{{ $order->payment_method }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col">
                            <span class="font-bold">Alamat</span>
                            <span>
                                {{ $order->user->address->street }}
                            </span>
                        </div>
                        <div class="flex gap-12">
                            <div class="flex flex-col">
                                <span class="font-bold">RT</span>
                                <span>{{ $order->user->address->neighborhood->rt }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold">RW</span>
                                <span>{{ $order->user->address->neighborhood->rw }}</span>
                            </div>
                        </div>
                        <div class="flex gap-12">
                            <div class="flex flex-col">
                                <span class="font-bold">Kecamatan</span>
                                <span>{{ $order->user->address->neighborhood->subDistrict->district->name }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold">Kelurahan</span>
                                <span>{{ $order->user->address->neighborhood->subDistrict->name }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold">Kota/Kabupaten</span>
                            <span>{{ $order->user->address->neighborhood->subDistrict->district->city->name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold">Kode Pos</span>
                            <span>{{ $order->user->address->neighborhood->postal_code }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keranjang -->
            <div class="flex flex-col flex-grow min-h-full shadow-outer py-6 px-8 rounded-xl w-[400px]"
                x-data="{
                    isPayNow: false,
                    total: '{{ $order->total }}',
                    cash: 0,
                    get change() {
                        return Math.max(this.cash - this.total, 0);
                    }
                }">
                <div class="text-2xl font-bold mb-6">Keranjang</div>

                <!-- List Item scrollable -->
                <div class="flex flex-col flex-grow overflow-y-auto min-h-fit">
                    @foreach ($order->items as $item)
                        <div class="flex items-start gap-6 mt-4 w-full">
                            <div
                                class="flex items-center justify-center bg-tertiary-500/30 h-20 p-3 aspect-square object-contain rounded-xl">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="max-h-16">
                            </div>
                            <div class="flex h-full flex-col gap-4 justify-between flex-grow">
                                <div class="flex flex-col">
                                    <span class="font-bold">{{ $item->product->name }}</span>
                                    <span class="text-tertiary-title text-xs">{{ $item->product->pcs }} pcs</span>
                                </div>
                                <div class="flex justify-between gap-16 min-w-fit">
                                    <span class="font-semibold text-tertiary-500">{{ $item->qty }}x</span>
                                    <span class="font-semibold text-primary">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="pt-6">
                    <hr class="w-full h-[2px] mt-6 bg-tertiary-table-line rounded-full border-0">
                    <div class="flex gap-4" x-show="isPayNow" x-cloak
                        x-transition:enter="transition-all ease duration-500"
                        x-transition:enter-start="-translate-y-4 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:leave="transition-all ease duration-500"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="-translate-y-4 opacity-0">
                        <x-textfield-price classCont="mb-4 w-full" class="focus:ring focus:ring-primary h-10 py-0"
                            name="cash" :value="old('cash', 0)"
                            x-on:cashinput="cash = $event.detail.amount"></x-textfield-price>
                        <button type="button" class="text-danger transition-all hover:scale-125 active:scale-90"
                            @click="isPayNow = false">
                            <x-icons.close-drop />
                        </button>
                    </div>
                    <div class="flex justify-between items-center mb-4 text-lg" x-show="isPayNow" x-cloak
                        x-transition:enter="transition-all ease duration-500"
                        x-transition:enter-start="-translate-y-4 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        x-transition:leave="transition-all ease duration-500"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="-translate-y-4 opacity-0">
                        <span class="font-bold text-tertiary-500/80">Kembalian</span>
                        <span class="font-bold text-primary" x-text="'Rp ' + change.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between items-center my-4 text-lg">
                        <span class="font-bold text-tertiary-500/80">Total</span>
                        <span class="font-bold text-primary">Rp
                            {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tombol Bayar -->
                <div class="relative">
                    <div class="invisible">
                        <x-button-sm class="py-2 px-6">Bayar</x-button-sm>
                    </div>
                    <x-button-sm id="btn-pay" x-cloak x-show="!isPayNow" @click="isPayNow = true"
                        class="absolute top-0 bg-primary shadow-outer-sidebar-primary text-white w-full py-2 px-6 mt-auto">
                        Bayar
                    </x-button-sm>
                    <x-button-sm type="submit" id="btn-payNow" x-show="isPayNow"
                        class="absolute top-0 bg-primary shadow-outer-sidebar-primary text-white w-full py-2 px-6 mt-auto">Bayar
                        Sekarang</x-button-sm>
                </div>
            </div>
        </div>
    </form>
</x-layout-main>
