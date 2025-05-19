<x-layout>
    <x-slot:title>Produk</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Produk</span>
            <a href="{{ route('product.create') }}"
                class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple transition-all hover:scale-105 active:scale-90">
                <x-icons.plus />
                Buat
            </a>
        </div>
    </x-slot:header>

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12">
        <div class="flex flex-col justify-between">
            <div class="px-7 py-4 flex justify-between">
                <div class="w-fit flex gap-4 items-center justify-center font-semibold">
                    @foreach (['semua', 'banyak', 'sedikit', 'habis'] as $status)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $status, 'page' => 1]) }}"
                            class="px-3 py-2 rounded-lg capitalize transition-all duration-1000 cursor-pointer {{ request('filter', 'semua') === $status ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black' }}">
                            {{ $status }}
                        </a>
                    @endforeach
                </div>
                <form action="{{ route('product.index') }}" method="GET"
                    class="w-1/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('product.index', request()->except(['search', 'filter', 'page'])) }}"
                            class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                            <x-icons.close />
                        </a>
                    @endif
                </form>

            </div>

            <div class="pb-4 relative overflow-x-auto overflow-y-auto max-h-[450px]">
                <table class="w-full min-w-max text-sm text-left">
                    <thead class="text-xs uppercase bg-white">
                        <tr>
                            <th class="px-4 py-3 w-44" align="center">Gambar</th>
                            <th class="px-4 py-3 w-60" align="center">Nama Produk</th>
                            <th class="px-4 py-3" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Stok
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'stock' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3" align="center">Pcs</th>
                            <th class="px-4 py-3 w-36" align="center">Status</th>
                            <th class="px-4 py-3 w-44" align="center">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                    class="flex items-center justify-center uppercase">
                                    Harga
                                    <x-icons.arrow-down
                                        class="ml-2 text-tertiary-300 {{ request('sort') == 'price' && request('desc') ? 'rotate-180' : '' }}" />
                                </a>
                            </th>
                            <th class="px-4 py-3 w-36" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $status =
                                    $product->stock === 0 ? 'habis' : ($product->stock <= 5 ? 'sedikit' : 'banyak');
                                $class =
                                    $product->stock === 0
                                        ? 'bg-danger/15 text-danger border-danger'
                                        : ($product->stock <= 5
                                            ? 'bg-warning-200/15 text-warning-200 border-warning-200'
                                            : 'bg-success/15 text-success border-success');
                            @endphp
                            <tr class="border-b-2 border-b-tertiary-table-line">
                                <td class="px-4 py-2" align="center">
                                    <div
                                        class="flex items-center justify-center h-14 aspect-square object-contain rounded-full bg-white">
                                        <img src="/img/product/{{ $product->image }}" class="h-12">
                                    </div>
                                </td>
                                <td class="px-4 py-2" align="center">{{ $product->name }}</td>
                                <td class="px-4 py-2" align="center">{{ $product->stock }}</td>
                                <td class="px-4 py-2" align="center">{{ $product->pcs }}</td>
                                <td class="px-4 py-2" align="center">
                                    <span
                                        class="px-2 py-1 rounded-lg capitalize border-2 {{ $class }}">{{ $status }}</span>
                                </td>
                                <td class="px-4 py-2" align="center">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2" align="center">
                                    <div class="flex justify-center gap-2">
                                        <a href="#"
                                            class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon />
                                        </a>
                                        <a href="#"
                                            class="text-primary transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.edit-icon />
                                        </a>
                                        <form action="#" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-danger transition-transform hover:scale-125 active:scale-90">
                                                @include('components.icons.delete-icon')
                                            </button>
                                        </form>
                                    </div>
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
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari total
                    {{ $products->total() }} produk
                </span>

                <form method="GET" action="{{ route('product.index') }}" class="flex items-center gap-2">
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
                    {{-- Tombol Sebelumnya --}}
                    @if ($products->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title"/>
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title"/>
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                            class="px-3 py-2 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="-rotate-90 text-tertiary-title"/>
                        </a>
                    @else
                        <span class="px-3 py-2 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="-rotate-90 text-tertiary-title"/>
                        </span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-layout>
