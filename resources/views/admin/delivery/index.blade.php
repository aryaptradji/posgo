<x-layout>
    <x-slot:title>Pengiriman</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Pengiriman</span>
            <div class="flex gap-6">
                <a href="{{ route('delivery.print') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print />
                    Print
                </a>
                <a href="{{ route('delivery.export') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-success transition-all hover:scale-105 active:scale-90">
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

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-8">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-between">
                <div class="w-fit flex gap-4 items-center justify-center font-semibold">
                    @foreach (['semua', 'selesai', 'dikirim', 'belum dikirim'] as $status)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $status, 'page' => 1]) }}"
                            class="px-3 py-2 rounded-lg capitalize transition-all duration-1000 cursor-pointer {{ request('filter', 'semua') === $status ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black' }}">
                            {{ $status }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('delivery.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('delivery.index', request()->except(['search', 'filter', 'page'])) }}"
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
                                    'sort' => 'shipped_at',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Waktu Dikirim
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'shipped_at' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'arrived_at',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Waktu Tiba
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'arrived_at' && request('desc') ? 'rotate-180' : '' }}" />
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
                        @forelse ($deliveries as $delivery)
                            @php
                                $class =
                                    $delivery->shipping_status == 'belum dikirim'
                                        ? 'bg-danger/15 text-danger border-danger'
                                        : ($delivery->shipping_status == 'dikirim'
                                            ? 'bg-warning-200/15 text-warning-200 border-warning-200'
                                            : 'bg-success/15 text-success border-success');
                            @endphp
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="left">{{ $delivery->code }}</td>
                                <td class="px-4 py-2 w-28" align="center">
                                    {{ $delivery->shipped_at_formatted }}</td>
                                <td class="px-4 py-2 w-28" align="center">{{ $delivery->arrived_at_formatted }}</td>
                                <td class="px-4 py-2 w-36" align="left">{{ $delivery->user->name }}</td>
                                <td class="px-4 py-2 capitalize" align="center">{{ $delivery->category }}</td>
                                <td class="px-4 py-4" align="center">
                                    <span
                                        class="px-2 py-1 rounded-lg capitalize border-2 {{ $class }}">{{ $delivery->shipping_status }}</span>
                                </td>
                                <td class="px-4 py-2" align="center">{{ $delivery->item }}</td>
                                <td class="px-4 py-2" align="center">Rp
                                    {{ number_format($delivery->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModalView: false, showModalSend: false, showModalUpload: false }">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" @click="showModalView = true"
                                            class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon />
                                        </button>
                                        <template x-if="{{ $delivery->shipping_status === 'belum dikirim' }}">
                                            <button type="button" @click="showModalSend = true"
                                                class="text-secondary-blue transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.send-icon />
                                            </button>
                                        </template>
                                        <template x-if="{{ $delivery->shipping_status === 'dikirim' }}">
                                            <button type="button" @click="showModalUpload = true"
                                                class="transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.upload-icon />
                                            </button>
                                        </template>
                                        <template x-if="{{ $delivery->shipping_status === 'dikirim' }}">
                                            <a href="{{ route('delivery.deliveryNote', $delivery) }}"
                                                class="transition-transform hover:scale-125 active:scale-90 mt-1">
                                                <x-icons.print-sm />
                                            </a>
                                        </template>
                                    </div>

                                    {{-- Modal View --}}
                                    <x-modal show="showModalView">
                                        <x-slot:title>
                                            <div class="w-full flex justify-between">
                                                <div class="flex">
                                                    <x-icons.info-icon class="mr-3" />
                                                    <h2 class="text-lg font-bold">Detail Pengiriman</h2>
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
                                                <div class="grid grid-cols-2 gap-4 text-start">
                                                    <div class="flex flex-col gap-4 col-span-1 justify-self-start">
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Kode</span>
                                                            <span>{{ $delivery->code }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Waktu Dikirim</span>
                                                            <span>{{ $delivery->shipped_at_formatted }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Nama</span>
                                                            <span>{{ $delivery->user->name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col gap-4 col-span-1 justify-self-end">
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Kategori</span>
                                                            <span class="capitalize">{{ $delivery->category }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Status</span>
                                                            <span
                                                                class="px-2 rounded-lg capitalize border-2 w-fit {{ $class }}">{{ $delivery->shipping_status }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">Kurir</span>
                                                            <span
                                                                class="capitalize w-44">{{ $delivery->courier->name ?? '-' }}</span>
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
                                                                    <th class="px-4 py-2 w-44" align="left">Nama
                                                                        Produk
                                                                    </th>
                                                                    <th class="px-4 py-2" align="center">Pcs</th>
                                                                    <th class="px-4 py-2" align="center">Qty</th>
                                                                    <th class="px-4 py-2" align="center">Harga
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($delivery->items as $item)
                                                                    <tr class="font-medium text-xs">
                                                                        <td class="px-4 py-2" align="left">
                                                                            {{ $item->product->name }}</td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $item->product->pcs }}</td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $item->qty }}</td>
                                                                        <td class="px-4 py-2" align="left">Rp
                                                                            {{ number_format($item->product->price, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr
                                                                    class="font-bold border-t border-tertiary-table-line">
                                                                    <td class="px-4 py-2" align="left">TOTAL
                                                                    </td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $delivery->items->sum(function ($item) {
                                                                            return $item->product->pcs;
                                                                        }) }}
                                                                    </td>
                                                                    <td class="px-4 py-2" align="center">
                                                                        {{ $delivery->items->sum(function ($item) {
                                                                            return $item->qty;
                                                                        }) }}
                                                                    </td>
                                                                    @php
                                                                        $totalHarga = $delivery->items->sum(function (
                                                                            $item,
                                                                        ) {
                                                                            return $item->product->price;
                                                                        });
                                                                    @endphp
                                                                    <td class="px-4 py-2" align="left">
                                                                        Rp
                                                                        {{ number_format($totalHarga, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-96 text-start">
                                                <span class="block font-bold pb-2">Bukti Pengiriman</span>
                                                <div
                                                    class="h-3/4 flex justify-center items-center bg-tertiary-table-line rounded-lg border-2 object-contain border-gray-300 text-gray-500 italic">
                                                    @if ($delivery->photo_url)
                                                        <img src="{{ asset('storage/' . $delivery->photo_url) }}"
                                                            class="max-h-52">
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

                                    {{-- Modal Kirim --}}
                                    <form action="{{ route('delivery.kirim', $delivery) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <x-modal show="showModalSend">
                                            <x-slot:title>
                                                <div class="w-full flex justify-between">
                                                    <div class="flex">
                                                        <x-icons.delivery class="mr-3 text-secondary-blue" />
                                                        <h2 class="text-lg font-bold">Kirim Pesanan</h2>
                                                    </div>
                                                    <button
                                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                                        type="button" @click="showModalSend = false">
                                                        <x-icons.close />
                                                    </button>
                                                </div>
                                            </x-slot:title>

                                            <div class="px-8 mb-8 w-[70vh] text-start">
                                                <x-textfield type="datetime-local" name="shipped_at" :value="old('shipped_at', now())"
                                                    class="focus-within:ring focus-within:ring-primary"
                                                    classCont="mb-4">
                                                    Waktu Dikirim
                                                </x-textfield>
                                                <x-dropdown :errorClass="$errors->has('courier_id')
                                                    ? 'border-[3.5px] border-danger focus:border-danger'
                                                    : 'border-0'" name="courier_id"
                                                    :items="$couriers">Kurir</x-dropdown>
                                                @error('courier_id')
                                                    <x-inline-error-message class="mt-2"
                                                        x-show="$errors->has('courier_id')">{{ $message }}</x-inline-error-message>
                                                @enderror
                                            </div>
                                            <x-slot:action>
                                                <div class="flex mr-2 gap-3">
                                                    <button type="button" @click="showModalSend = false"
                                                        class="px-6 py-3 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-6 py-3 bg-secondary-blue text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                        Kirim
                                                    </button>
                                                </div>
                                            </x-slot:action>
                                        </x-modal>
                                    </form>

                                    {{-- Modal Upload --}}
                                    <form action="{{ route('delivery.upload', $delivery) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <x-modal show="showModalUpload">
                                            <x-slot:title>
                                                <div class="w-full flex justify-between">
                                                    <div class="flex">
                                                        <x-icons.upload-title class="mr-3" />
                                                        <h2 class="text-lg font-bold">Upload Bukti Pengiriman</h2>
                                                    </div>
                                                    <button
                                                        class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                                        type="button" @click="showModalUpload = false">
                                                        <x-icons.close />
                                                    </button>
                                                </div>
                                            </x-slot:title>
                                            <div class="flex gap-8 px-10 mb-2">
                                                <div>
                                                    <div class="grid grid-cols-2 gap-4 text-start">
                                                        <div class="flex flex-col gap-4 col-span-1 justify-self-start">
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Kode</span>
                                                                <span>{{ $delivery->code }}</span>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Waktu Dikirim</span>
                                                                <span>{{ $delivery->shipped_at_formatted }}</span>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Nama</span>
                                                                <span>{{ $delivery->user->name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col gap-4 col-span-1 justify-self-end">
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Kategori</span>
                                                                <span
                                                                    class="capitalize">{{ $delivery->category }}</span>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Status</span>
                                                                <span
                                                                    class="px-2 rounded-lg capitalize border-2 w-fit {{ $class }}">{{ $delivery->shipping_status }}</span>
                                                            </div>
                                                            <div class="flex flex-col gap-1">
                                                                <span class="font-bold">Kurir</span>
                                                                <span
                                                                    class="capitalize w-44">{{ $delivery->courier->name ?? '-' }}</span>
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
                                                                        <th class="px-4 py-2 w-44" align="left">
                                                                            Nama
                                                                            Produk
                                                                        </th>
                                                                        <th class="px-4 py-2" align="center">Pcs
                                                                        </th>
                                                                        <th class="px-4 py-2" align="center">Qty
                                                                        </th>
                                                                        <th class="px-4 py-2" align="center">Harga
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($delivery->items as $item)
                                                                        <tr class="font-medium text-xs">
                                                                            <td class="px-4 py-2" align="left">
                                                                                {{ $item->product->name }}</td>
                                                                            <td class="px-4 py-2" align="center">
                                                                                {{ $item->product->pcs }}</td>
                                                                            <td class="px-4 py-2" align="center">
                                                                                {{ $item->qty }}</td>
                                                                            <td class="px-4 py-2" align="left">
                                                                                Rp
                                                                                {{ number_format($item->product->price, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr
                                                                        class="font-bold border-t border-tertiary-table-line">
                                                                        <td class="px-4 py-2" align="left">TOTAL
                                                                        </td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $delivery->items->sum(function ($item) {
                                                                                return $item->product->pcs;
                                                                            }) }}
                                                                        </td>
                                                                        <td class="px-4 py-2" align="center">
                                                                            {{ $delivery->items->sum(function ($item) {
                                                                                return $item->qty;
                                                                            }) }}
                                                                        </td>
                                                                        @php
                                                                            $totalHarga = $delivery->items->sum(
                                                                                function ($item) {
                                                                                    return $item->product->price;
                                                                                },
                                                                            );
                                                                        @endphp
                                                                        <td class="px-4 py-2" align="left">
                                                                            Rp
                                                                            {{ number_format($totalHarga, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="h-full w-96 text-start">
                                                    <x-textfield-image name="photo[{{ $loop->index }}]" fileNameClass="text-xs max-w-40"
                                                        closeSideClass="text-xs" previewClass="min-h-80"
                                                        uploadClass="min-h-80">Bukti
                                                        Pengiriman
                                                        <span class="ml-2 text-xs text-tertiary-400 font-semibold">(format
                                                            .jpg/.jpeg, max. 3 mb)</span>
                                                    </x-textfield-image>
                                                    <div class="text-center">
                                                        <button type="submit"
                                                            class="mt-8 px-6 py-3 bg-success text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                            Upload
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500 italic">Data pengiriman
                                    pesanan tidak
                                    ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $deliveries->firstItem() }} - {{ $deliveries->lastItem() }} dari
                    {{ $deliveries->total() }}
                </span>

                <form method="GET" action="{{ route('delivery.index') }}" class="flex items-center gap-2">
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
                    @if ($deliveries->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $deliveries->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    @php
                        $currentPage = $deliveries->currentPage();
                        $lastPage = $deliveries->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    <!-- First page -->
                    @if ($start > 1)
                        <a href="{{ $deliveries->url(1) }}"
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
                            <a href="{{ $deliveries->url($i) }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $i }}</a>
                        @endif
                    @endfor

                    <!-- Last page -->
                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="px-3 py-2 text-gray-500">...</span>
                        @endif
                        <a href="{{ $deliveries->url($lastPage) }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $lastPage }}</a>
                    @endif


                    @if ($deliveries->hasMorePages())
                        <a href="{{ $deliveries->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
