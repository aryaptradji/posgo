<x-layout-main>
    <x-slot:title>Bayar Pesanan</x-slot:title>

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

    <div class="flex flex-grow min-h-0 gap-6 mx-14 mt-32 h-full">
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
                        <span class="font-bold">Alamat</span>
                        <span>
                            {{ $order->user->address->street }}
                        </span>
                    </div>
                </div>
                <div class="flex flex-col gap-8">
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
                    <div class="flex flex-col">
                        <span class="font-bold">Kecamatan</span>
                        <span>{{ $order->user->address->neighborhood->subDistrict->district->name }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold">Kelurahan</span>
                        <span>{{ $order->user->address->neighborhood->subDistrict->name }}</span>
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
        <div class="flex flex-col flex-grow h-full shadow-outer py-6 px-8 rounded-xl w-[400px]">
            <div class="text-2xl font-bold mb-6">Keranjang</div>

            <!-- List Item scrollable -->
            <div class="flex flex-col flex-grow overflow-y-auto min-h-0">
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
                <hr class="w-full h-[2px] my-6 bg-tertiary-table-line rounded-full border-0">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-tertiary-500/80 text-lg">Total</span>
                    <span class="font-bold text-primary text-lg">Rp
                        {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Bayar -->
            <x-button-sm id="btn-pay"
                class="bg-primary shadow-outer-sidebar-primary text-white w-full py-2 px-6 mt-auto">Bayar</x-button-sm>
        </div>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('btn-pay');
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href =
                        "{{ route('pos-menu') }}";
                },
                onPending: function(result) {
                    window.location.href =
                        "{{ route('pos-menu') }}";
                },
                onError: function(result) {
                    alert('Terjadi kesalahan saat pembayaran.');
                    console.error(result);
                    window.location.href =
                        "{{ route('pos-menu') }}";
                },
                onClose: function() {
                    window.location.href =
                        "{{ route('pos-menu') }}";
                }
            });
        });
    </script>
</x-layout-main>
