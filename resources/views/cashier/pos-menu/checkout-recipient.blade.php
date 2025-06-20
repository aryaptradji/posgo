<x-layout-main>
    <x-slot:title>Informasi Pemesan</x-slot:title>

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

    <form action="{{ route('pos-menu.checkout.recipient.store', $order) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-grow max-h-[74vh] h-full gap-6 mx-14 mt-32">
            <!-- Detail Pesanan -->
            <div class="w-3/4 shadow-outer py-6 rounded-xl flex flex-col">
                <div class="text-2xl px-8 font-bold pb-4 border-b border-tertiary-title-line">Informasi Pemesan</div>
                <div class="flex px-8 gap-28 mt-6 w-1/2">
                    <div class="flex flex-col gap-8 flex-1">
                        {{-- Nama --}}
                        <x-dropdown-autocomplete name="user_id" :items="$users->map(fn($u) => ['id' => $u->id, 'name' => $u->name])->toArray()" :route="route('pos-menu.create-user')"
                            :errorClass="$errors->has('user_id')
                                ? 'border-[3.5px] border-danger focus:border-danger'
                                : 'border-0'">Nama
                            Pemesan</x-dropdown-autocomplete>
                        @error('user_id')
                            <x-inline-error-message class="mb-3 -mt-6"
                                x-show="$errors->has('user_id')">{{ $message }}</x-inline-error-message>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Keranjang -->
            <div class="flex flex-col flex-grow min-h-full shadow-outer py-6 px-8 rounded-xl w-[400px]">
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
                    <hr class="w-full h-[2px] my-6 bg-tertiary-table-line rounded-full border-0">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-tertiary-500/80 text-lg">Total</span>
                        <span class="font-bold text-primary text-lg">Rp
                            {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tombol Bayar -->
                <x-button-sm type="submit"
                    class="bg-primary shadow-outer-sidebar-primary text-white w-full py-2 px-6 mt-auto">Lanjut</x-button-sm>
            </div>
        </div>
    </form>
</x-layout-main>
