<x-layout>
    <x-slot:title>Pemasukan</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Pemasukan</span>
            <div class="flex gap-6">
                <a href="{{ route('revenue.print') }}" class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print/>
                    Print
                </a>
                <a href="{{ route('revenue.export') }}" class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-success transition-all hover:scale-105 active:scale-90">
                    <x-icons.export/>
                    Export
                </a>
                <a href="{{ route('revenue.create') }}" class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple transition-all hover:scale-105 active:scale-90">
                    <x-icons.plus/>
                    Buat
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
                    @foreach (['semua', 'produk', 'luar produk'] as $category)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $category, 'page' => 1]) }}"
                            class="px-3 py-2 rounded-lg capitalize transition-all duration-1000 cursor-pointer {{ request('filter', 'semua') === $category ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('revenue.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('revenue.index', request()->except(['search', 'filter', 'page'])) }}"
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
                                    'sort' => 'date',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1,
                                ]) }}" class="flex items-center justify-center uppercase">
                                    Waktu
                                    <x-icons.arrow-down class="ml-2 text-tertiary-300 {{ request('sort') == 'date' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">Sumber</th>
                            <th class="px-4 py-3" align="center">Kategori</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'total',
                                    'desc' => request('desc') ? null : 1,
                                    'page' => 1
                                ]) }}" class="flex items-center justify-center uppercase">
                                    Total
                                    <x-icons.arrow-down class="ml-2 text-tertiary-300 {{ request('sort') == 'total' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($revenues as $revenue)
                            @php
                                $class =
                                    $revenue->category === 'produk'
                                        ? 'bg-secondary-purple/15 text-secondary-purple border-secondary-purple'
                                        : 'bg-primary/15 text-primary border-primary';
                            @endphp
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="center">{{ $revenue->date->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-2" align="center">{{ $revenue->product_id ? $revenue->product->name : $revenue->source }}</td>
                                <td class="px-4 py-2" align="center">
                                    <span class="px-2 py-1 rounded-lg capitalize border-2 {{ $class }}">{{ $revenue->category }}</span>
                                </td>
                                <td class="px-4 py-2" align="center">Rp {{ number_format($revenue->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2" align="center" x-data="{ showModal: false }">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('revenue.edit', $revenue) }}"
                                            class="text-primary transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.edit-icon />
                                        </a>
                                        <button type="submit" @click="showModal = true"
                                            class="text-danger transition-transform hover:scale-125 active:scale-90">
                                            @include('components.icons.delete-icon')
                                        </button>
                                    </div>

                                    <!-- Modal Delete -->
                                    <x-modal show="showModal">
                                        <x-slot:title>
                                            <x-icons.delete-icon class="text-danger mr-3 mt-0.5"/>
                                            <h2 class="text-lg font-bold">Hapus Data Pendapatan</h2>
                                        </x-slot:title>
                                        <p class="mb-6 mx-6 mt-4 text-start">
                                            Yakin ingin menghapus
                                            <span class="font-bold text-danger">{{ $revenue->source }}</span>
                                            pada
                                            <span class="font-bold text-danger">{{ $revenue->date->translatedFormat('l, j M o') }}</span>
                                            jam
                                            <span class="font-bold text-danger">{{ $revenue->date->translatedFormat('H:i:s') }}</span>
                                            ?
                                        </p>
                                        <x-slot:action>
                                            <button type="button" @click="showModal = false"
                                                class="px-4 py-2 bg-btn-cancel rounded-full font-semibold transition-all hover:scale-105 active:scale-90">
                                                Batal
                                            </button>

                                            <form action="{{ route('revenue.destroy', $revenue) }}" method="POST">
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
                                <td colspan="5" class="text-center py-10 text-gray-500 italic">Data pendapatan tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center mx-7 justify-between pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $revenues->firstItem() }} - {{ $revenues->lastItem() }} dari {{ $revenues->total() }}
                </span>

                <form method="GET" action="{{ route('revenue.index') }}" class="flex items-center gap-2">
                    <label for="per_page" class="text-sm text-gray-600">Per page:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="p-2 outline-none border border-gray-300 focus:border-primary focus:border-2 rounded-full text-sm bg-tertiary text-black">
                        @foreach ([2, 5, 10, 20] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="desc" value="{{ request('desc') }}">
                </form>

                <div class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
                    @if ($revenues->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $revenues->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    @php
                        $currentPage = $revenues->currentPage();
                        $lastPage = $revenues->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $revenues->url(1) }}" class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">1</a>
                        @if ($start > 2)
                            <span class="px-3 py-2 text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $currentPage)
                            <span class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $i }}</span>
                        @else
                            <a href="{{ $revenues->url($i) }}" class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $i }}</a>
                        @endif
                    @endfor

                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="px-3 py-2 text-gray-500">...</span>
                        @endif
                        <a href="{{ $revenues->url($lastPage) }}" class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $lastPage }}</a>
                    @endif

                    @if ($revenues->hasMorePages())
                        <a href="{{ $revenues->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
