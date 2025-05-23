<x-layout>
    <x-slot:title>Kasir</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Kasir</span>
            <div class="flex gap-6">
                <a href="#"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print />
                    Print
                </a>
                <a href="#"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-primary transition-all hover:scale-105 active:scale-90">
                    <x-icons.export />
                    Export
                </a>
                <a href="{{ route('cashier.create') }}"
                    class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple transition-all hover:scale-105 active:scale-90">
                    <x-icons.plus />
                    Buat
                </a>
            </div>
        </div>
    </x-slot:header>

    <!-- Toast Create Success -->
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

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-end">
                <form action="{{ route('cashier.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('cashier.index', request()->except(['search', 'page'])) }}"
                            class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                            <x-icons.close />
                        </a>
                    @endif
                </form>
            </div>

            <div class="pb-4 relative overflow-x-auto overflow-y-auto max-h-[450px]">
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
                            <th class="px-4 py-3" align="center">Email</th>
                            <th class="px-4 py-3" align="center">Password</th>
                            <th class="px-4 py-3" align="center">No Handphone</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Waktu Dibuat
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'created_at' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cashiers as $cashier)
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="center">
                                    <!-- <img src="{{ $cashier->photo_url }}" class="max-h-11 aspect-square object-contain rounded-full"> -->
                                    @php
                                        $parts = explode(' ', $cashier->name);
                                        $initials = strtoupper(
                                            substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''),
                                        );
                                    @endphp
                                    @if ($cashier->photo_url)
                                        <img src="{{ $cashier->photo_url }}"
                                            class="rounded-full h-11 w-11 aspect-square object-cover">
                                    @else
                                        <div
                                            class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-11 h-11 flex items-center justify-center text-lg">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2" align="center">{{ $cashier->name }}</td>
                                <td class="px-4 py-2" align="center">{{ $cashier->email }}</td>
                                <td class="px-4 py-2" align="center">{{ $cashier->visible_password }}</td>
                                <td class="px-4 py-2" align="center">{{ $cashier->phone_number }}</td>
                                <td class="px-4 py-2" align="center">
                                    {{ $cashier->created->translatedFormat('d M Y H:i:s') }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModalView: false, showModalDelete: false }">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" @click="showModalView = true"
                                            class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon />
                                        </button>
                                        <a href="{{ route('product.edit', $cashier) }}"
                                            class="text-primary transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.edit-icon />
                                        </a>
                                        <button type="button" @click="showModalDelete = true"
                                            class="text-danger transition-transform hover:scale-125 active:scale-90">
                                            @include('components.icons.delete-icon')
                                        </button>
                                    </div>

                                    <!-- Modal Delete -->
                                    <x-modal show="showModalDelete">
                                        <x-slot:title>
                                            <x-icons.delete-icon class="text-danger mr-3 mt-0.5" />
                                            <h2 class="text-lg font-bold">Hapus Produk</h2>
                                        </x-slot:title>
                                        <p class="mb-6 ml-6 mt-4 text-start">
                                            Yakin ingin menghapus
                                            <span class="font-bold text-danger">{{ $cashier->name }}</span>
                                            ?
                                        </p>
                                        <x-slot:action>
                                            <button type="button" @click="showModalDelete = false"
                                                class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                Batal
                                            </button>

                                            <form action="{{ route('product.destroy', $cashier) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-danger text-white rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                    Hapus
                                                </button>
                                            </form>
                                        </x-slot:action>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 italic">Produk tidak ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $cashiers->firstItem() }} - {{ $cashiers->lastItem() }} dari
                    {{ $cashiers->total() }}
                </span>

                <form method="GET" action="{{ route('cashier.index') }}" class="flex items-center gap-2">
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
                    @if ($cashiers->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $cashiers->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($cashiers->getUrlRange(1, $cashiers->lastPage()) as $page => $url)
                        @if ($page == $cashiers->currentPage())
                            <span
                                class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if ($cashiers->hasMorePages())
                        <a href="{{ $cashiers->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
