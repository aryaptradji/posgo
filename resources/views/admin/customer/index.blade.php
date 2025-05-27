<x-layout>
    <x-slot:title>Customer</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Customer</span>
            <div class="flex gap-6">
                <a href="{{ route('customer.print') }}" target="_blank"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print />
                    Print
                </a>
                <a href="{{ route('customer.export') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-success transition-all hover:scale-105 active:scale-90">
                    <x-icons.export />
                    Export
                </a>
            </div>
        </div>
    </x-slot:header>

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-end">
                <form action="{{ route('customer.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('customer.index', request()->except(['search', 'page'])) }}"
                            class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                            <x-icons.close />
                        </a>
                    @endif
                </form>
            </div>

            <div class="pb-4 relative overflow-x-auto overflow-y-auto h-fit">
                <table class="w-full min-w-max text-xs text-left">
                    <thead class="text-xs uppercase bg-white">
                        <tr>
                            <th class="px-4 py-3" align="center">Foto</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Nama
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'name' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Email
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'email' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">No Handphone</th>
                            <th class="px-4 py-3 w-44" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'address', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Alamat
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'address' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Waktu Dibuat
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'created' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            @php
                                $parts = explode(' ', $customer->name);
                                $initials = strtoupper(
                                    substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''),
                                );
                            @endphp
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="center">
                                    @if ($customer->photo_url)
                                        <img src="{{ $customer->photo_url }}"
                                            class="rounded-full h-11 w-11 aspect-square object-cover">
                                    @else
                                        <div
                                            class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-11 h-11 flex items-center justify-center text-lg">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 capitalize" align="center">{{ $customer->name }}</td>
                                <td class="px-4 py-2" align="center">{{ $customer->email }}</td>
                                <td class="px-4 py-2" align="left">{{ $customer->phone_number }}</td>
                                <td class="px-4 py-2" align="left">
                                    {{ $customer->address->street }},
                                    RT {{ $customer->address->neighborhood->rt }}/RW
                                    {{ $customer->address->neighborhood->rw }},
                                    Kec. {{ $customer->address->neighborhood->subDistrict->district->name }},
                                    Kel. {{ $customer->address->neighborhood->subDistrict->name }},
                                    {{ $customer->address->neighborhood->subDistrict->district->city->name }}
                                    {{ $customer->address->neighborhood->postal_code }}
                                </td>
                                <td class="px-4 py-2" align="left">
                                    {{ $customer->created->translatedFormat('d M Y H:i:s') }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModal: false }">
                                    <button type="button" @click="showModal = true"
                                        class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                        <x-icons.detail-icon />
                                    </button>

                                    <!-- Modal View -->
                                    <x-modal show="showModal">
                                        <x-slot:title>
                                            <div class="w-full flex justify-between">
                                                <div class="flex">
                                                    <x-icons.info-icon class="mr-3" />
                                                    <h2 class="text-lg font-bold">Detail Customer</h2>
                                                </div>
                                                <button
                                                    class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95"
                                                    type="button" @click="showModal = false">
                                                    <x-icons.close />
                                                </button>
                                            </div>
                                        </x-slot:title>
                                        <div class="px-10 mb-2">
                                            <div class="flex items-center justify-center mb-6 h-32 p-2">
                                                @if ($customer->photo_url)
                                                    <img src="{{ $customer->photo_url }}"
                                                        class="rounded-full h-32 w-32 aspect-square object-cover">
                                                @else
                                                    <div
                                                        class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-32 h-32 flex items-center justify-center text-5xl">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-2 text-start">
                                                <div class="flex flex-col gap-4 col-span-1 justify-self-start">
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Nama</span>
                                                        <span>{{ $customer->name }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Email</span>
                                                        <span>{{ $customer->email }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">No Handphone</span>
                                                        <span>{{ $customer->phone_number }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Waktu Dibuat</span>
                                                        <span>{{ $customer->created->translatedFormat('d M Y H:i:s') }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Alamat</span>
                                                        <span>{{ $customer->address->street }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col gap-4 col-span-1 justify-self-end">
                                                    <div class="flex gap-6">
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">RT</span>
                                                            <span>{{ $customer->address->neighborhood->rt }}</span>
                                                        </div>
                                                        <div class="flex flex-col gap-1">
                                                            <span class="font-bold">RW</span>
                                                            <span>{{ $customer->address->neighborhood->rw }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Kecamatan</span>
                                                        <span>{{ $customer->address->neighborhood->subDistrict->name }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Kelurahan</span>
                                                        <span>{{ $customer->address->neighborhood->subDistrict->district->name }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Kota/Kabupaten</span>
                                                        <span>{{ $customer->address->neighborhood->subDistrict->district->city->name }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Kode Pos</span>
                                                        <span>{{ $customer->address->neighborhood->postal_code }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 italic">Akun customer tidak
                                    ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $customers->firstItem() }} - {{ $customers->lastItem() }} dari
                    {{ $customers->total() }}
                </span>

                <form method="GET" action="{{ route('customer.index') }}" class="flex items-center gap-2">
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
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="desc" value="{{ request('desc') }}">
                </form>

                <div
                    class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
                    {{-- Tombol Sebelumnya --}}
                    @if ($customers->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $customers->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                        @if ($page == $customers->currentPage())
                            <span
                                class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if ($customers->hasMorePages())
                        <a href="{{ $customers->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
