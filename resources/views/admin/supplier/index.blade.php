<x-layout>
    <x-slot:title>Supplier</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Supplier</span>
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
                <a href="{{ route('supplier.create') }}"
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

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-end">
                <form action="{{ route('supplier.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('supplier.index', request()->except(['search', 'page'])) }}"
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
                            <th class="px-4 py-3 w-44" align="center">
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
                            <th class="px-4 py-3 w-48" align="center">Telepon</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'email',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Email
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'email' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3 w-20" align="center">Fax</th>
                            <th class="px-4 py-3 w-72" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'address',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Alamat
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'address' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="center">{{ $supplier->name }}</td>
                                <td class="px-4 py-2" align="center">{{ $supplier->phone }}</td>
                                <td class="px-4 py-2" align="center">{{ $supplier->email }}</td>
                                <td class="px-4 py-2" align="center">{{ $supplier->fax }}</td>
                                <td class="px-4 py-2" align="center">{{ $supplier->address }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModalView: false, showModalDelete: false }">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" @click="showModalView = true"
                                            class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon />
                                        </button>
                                        <a href="{{ route('supplier.edit', $supplier) }}"
                                            class="text-primary transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.edit-icon />
                                        </a>
                                        <button type="submit" @click="showModalDelete = true"
                                            class="text-danger transition-transform hover:scale-125 active:scale-90">
                                            @include('components.icons.delete-icon')
                                        </button>
                                    </div>

                                    <!-- Modal View -->
                                    <x-modal show="showModalView">
                                        <x-slot:title>
                                            <div class="w-full flex justify-between">
                                                <div class="flex">
                                                    <x-icons.info-icon class="mr-3"/>
                                                    <h2 class="text-lg font-bold">Detail Supplier</h2>
                                                </div>
                                                <button class="text-tertiary-title transition-all hover:text-danger hover:scale-125 active:scale-95" type="button" @click="showModalView = false">
                                                    <x-icons.close/>
                                                </button>
                                            </div>
                                        </x-slot:title>
                                        <div class="px-10 mb-2">
                                            <div class="grid grid-cols-2 gap-4 text-start">
                                                <div class="flex flex-col gap-4 col-span-1 justify-self-start">
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Nama</span>
                                                        <span>{{ $supplier->name }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Telepon</span>
                                                        <span>{{ $supplier->phone }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Email</span>
                                                        <span>{{ $supplier->email }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col gap-4 col-span-1 justify-self-end">
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Fax</span>
                                                        <span>{{ $supplier->fax }}</span>
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="font-bold">Alamat</span>
                                                        <span>{{ $supplier->address }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-modal>

                                    <!-- Modal Delete -->
                                    <x-modal show="showModalDelete">
                                        <x-slot:title>
                                            <x-icons.delete-icon class="text-danger mr-3 mt-0.5" />
                                            <h2 class="text-lg font-bold">Hapus Data Supplier</h2>
                                        </x-slot:title>
                                        <p class="mb-6 mx-6 mt-4 text-start">
                                            Yakin ingin menghapus supplier bernama
                                            <span class="font-bold text-danger">{{ $supplier->name }}</span>
                                            ?
                                        </p>
                                        <x-slot:action>
                                            <button type="button" @click="showModalDelete = false"
                                                class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                Batal
                                            </button>

                                            <form action="{{ route('supplier.destroy', $supplier) }}" method="POST">
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
                                <td colspan="5" class="text-center py-10 text-gray-500 italic">Data supplier tidak
                                    ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} dari
                    {{ $suppliers->total() }}
                </span>

                <form method="GET" action="{{ route('supplier.index') }}" class="flex items-center gap-2">
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
                    @if ($suppliers->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $suppliers->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    @foreach ($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                        @if ($page == $suppliers->currentPage())
                            <span
                                class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($suppliers->hasMorePages())
                        <a href="{{ $suppliers->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
