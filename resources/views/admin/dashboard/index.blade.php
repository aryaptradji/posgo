<x-layout>
    <x-slot:title>Dashboard</x-slot:title>
    <x-slot:header>Dashboard</x-slot:header>

    <!-- Toast Login Success -->
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

    <div class="flex gap-6 bg-tertiary">
        {{-- Filter Tanggal --}}
        <form action="{{ route('dashboard') }}" method="GET" enctype="multipart/form-data"
            class="flex flex-col gap-6 p-7 mb-6 rounded-2xl shadow-outer flex-[2]">
            <div class="flex gap-6">
                {{-- Input Dari Tanggal --}}
                <x-textfield-outline contClass="w-full" class="focus-within:ring focus-within:ring-primary"
                    type="date" id="dari_tanggal" name="dari_tanggal" :value="request('dari_tanggal')">Dari
                    Tanggal</x-textfield-outline>

                {{-- Input Sampai Tanggal --}}
                <x-textfield-outline contClass="w-full" class="focus-within:ring focus-within:ring-primary"
                    type="date" id="sampai_tanggal" name="sampai_tanggal" :value="request('sampai_tanggal')">Sampai
                    Tanggal</x-textfield-outline>
            </div>

            {{-- Tombol Filter --}}
            <div class="flex gap-4 justify-end">
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold rounded-full text-white bg-primary hover:scale-110 hover:shadow-drop active:shadow-none active:scale-90 transition-all duration-200">
                    Terapkan
                </button>

                @if (request()->has('dari_tanggal') || request()->has('sampai_tanggal'))
                    <a href="{{ route('dashboard', request()->except(['dari_tanggal', 'sampai_tanggal', 'page'])) }}"
                        class="px-5 py-2.5 text-sm font-semibold rounded-full text-gray-700 bg-gray-200 hover:scale-110 hover:shadow-drop active:shadow-none active:scale-90 transition-all duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Filter Cepat --}}
        <form action="{{ route('dashboard') }}" method="GET" enctype="multipart/form-data"
            class="flex flex-col gap-6 p-7 mb-6 rounded-2xl shadow-outer flex-[1]">
            <div class="w-full">
                <label for="range" class="block mb-4 text-base font-semibold text-black dark:text-white">Filter
                    Cepat</label>

                <div x-data="{
                    open: false,
                    selectedId: '{{ request('range', '') }}',
                    selectedLabel: 'Pilih Salah Satu',
                    items: [
                        { id: '0', name: 'Hari ini' },
                        { id: '1', name: 'Kemarin' },
                        { id: '7', name: '7 hari terakhir' },
                        { id: '30', name: '1 bulan terakhir' },
                        { id: '90', name: '3 bulan terakhir' }
                    ],
                }" x-init="const initialItem = items.find(item => item.id === selectedId);

                if (initialItem) {
                    selectedLabel = initialItem.name;
                }" class="relative">

                    <!-- Button to toggle dropdown -->
                    <button
                        :class="{
                            'text-black': selectedLabel !== 'Pilih Salah Satu',
                            'text-tertiary-200': selectedLabel=='Pilih Salah Satu'
                        }"
                        class="bg-tertiary h-14 text-black rounded-xl text-sm ring-1 ring-tertiary-300 outline-none placeholder-tertiary-200 w-full text-left px-6 flex
                                justify-between items-center"
                        type="button" @click="open=!open">
                        <span x-text="selectedLabel"></span>
                        <x-icons.arrow-nav
                            x-bind:class="{
                                'text-black': selectedLabel !== 'Pilih Salah Satu',
                                'text-tertiary-200': selectedLabel ==
                                    'Pilih Salah Satu',
                                'transition-transform duration-200 rotate-180': open,
                                'transition-transform duration-200 rotate-0': !open
                            }" />
                    </button>

                    <!-- Dropdown Items -->
                    <div x-cloak x-show="open" @click.away="open = false"
                        x-transition:enter="transition-all ease duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition-all ease duration-300"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-4"
                        class="absolute mt-1 w-full bg-tertiary rounded-xl shadow-lg py-2 px-4 border-t-0">
                        <ul class="text-sm text-gray-700 dark:text-gray-200 max-h-52 overflow-y-auto">
                            <template x-for="item in items" :key="item.id">
                                <li class="p-2 rounded-lg hover:bg-primary hover:text-white cursor-pointer"
                                    @click="selectedId = item.id; selectedLabel = item.name; open = false;">
                                    <span x-text="item.name"></span>
                                </li>
                            </template>
                            <input type="hidden" name="range" :value="selectedId">
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Tombol Filter --}}
            <div class="flex gap-4 justify-end">
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold rounded-full text-white bg-primary hover:scale-110 hover:shadow-drop active:shadow-none active:scale-90 transition-all duration-200">
                    Terapkan
                </button>

                @if (request()->has('range'))
                    <a href="{{ route('dashboard', request()->except(['range', 'page'])) }}"
                        class="px-5 py-2.5 text-sm font-semibold rounded-full text-gray-700 bg-gray-200 hover:scale-110 hover:shadow-drop active:shadow-none active:scale-90 transition-all duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="h-40 grid grid-cols-4 gap-6 mb-11">
        {{-- Jumlah Pesanan --}}
        <div class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
            <p class="text-tertiary-title font-semibold">
                Jumlah Pesanan
            </p>
            <span class="text-2xl font-semibold">{{ $jumlahPesanan }}</span>
        </div>

        {{-- Pemasukan --}}
        <div class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
            <p class="text-tertiary-title font-semibold">
                Pemasukan
            </p>
            <span class="text-2xl font-semibold">Rp {{ number_format($pemasukan, 0, ',', '.') }}</span>
            <img src="{{ asset('img/icon/omset.svg') }}" alt="omset.svg" class="h-7">
        </div>

        {{-- Pengeluaran --}}
        <div class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
            <p class="text-tertiary-title font-semibold">
                Pengeluaran
            </p>
            <span class="text-2xl font-semibold">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span>
            <img src="{{ asset('img/icon/expense.svg') }}" alt="expense.svg" class="h-6">
        </div>

        {{-- Laba Bersih --}}
        <div class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
            <p class="text-tertiary-title font-semibold">
                Laba Bersih
            </p>
            <span class="text-2xl font-semibold">Rp {{ number_format($labaBersih, 0, ',', '.') }}</span>
            <img src="{{ asset('img/icon/net profit.svg') }}" alt="net profit.svg" class="h-5 mt-1">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-6 mb-6">
        {{-- Chart Pemasukan --}}
        <div x-data="{
            series: @js($pemasukanPerHari),
            categories: @js($chartCategoriesPemasukan),
            renderChart() {
                new ApexCharts(this.$refs.chart, {
                    chart: {
                        type: 'area',
                        height: 260,
                        toolbar: { show: false },
                        animations: { enabled: true }
                    },
                    colors: ['#7A24F9'],
                    series: [{
                        name: 'Pemasukan',
                        data: this.series
                    }],
                    xaxis: {
                        categories: this.categories,
                        tickPlacement: 'on',
                        tickAmount: Math.min(this.categories.length, 8),
                        labels: {
                            show: true,
                            rotate: -60,
                            style: {
                                fontSize: '10px'
                            },
                            hideOverlappingLabels: false,
                            trim: false
                        },
                        axisBorder: { show: true },
                        axisTicks: { show: true }
                    },
                    stroke: { width: 3, curve: 'smooth' },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.55,
                            opacityTo: 0,
                            shade: '#7A24F9',
                            gradientToColors: ['#7A24F9']
                        }
                    },
                    dataLabels: { enabled: false },
                    grid: { show: true },
                    tooltip: {
                        x: { format: 'dd MMM' },
                        y: { formatter: (val) => `Rp ${val.toLocaleString()}` }
                    }
                }).render();
            }
        }" x-init="renderChart()" class="rounded-sm bg-none">
            <div class="w-full bg-tertiary rounded-2xl shadow-outer px-6 pt-6 pb-4">
                <div class="flex justify-between">
                    <div>
                        <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">
                            Rp {{ number_format(array_sum($pemasukanPerHari), 0, ',', '.') }}
                        </h5>
                        <p class="font-normal">
                        <div class="text-lg text-secondary-purple font-semibold mb-1">Pemasukan</div>
                        <div class="text-base text-gray-500 mb-2">{{ $labelPeriode }}</div>
                        </p>
                    </div>
                </div>

                <div x-ref="chart"></div>

                <div
                    class="grid grid-cols-1 items-center border-t border-gray-200 dark:border-gray-700 justify-between">
                    <div class="flex justify-end items-center pt-4">
                        <a href="{{ route('revenue.index') }}"
                            class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-secondary-purple hover:text-blue-700 hover:bg-gray-100 px-3 py-2">
                            Laporan Pemasukan
                            <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Pengeluaran --}}
        <div x-data="{
            series: @js($pengeluaranPerHari),
            categories: @js($chartCategoriesPengeluaran),
            renderChart() {
                new ApexCharts(this.$refs.chart, {
                    chart: {
                        type: 'area',
                        height: 260,
                        toolbar: { show: false },
                        animations: { enabled: true }
                    },
                    colors: ['#E4763F'],
                    series: [{
                        name: 'Pengeluaran',
                        data: this.series
                    }],
                    xaxis: {
                        categories: this.categories,
                        tickPlacement: 'on',
                        tickAmount: Math.min(this.categories.length, 8),
                        labels: {
                            show: true,
                            rotate: -60,
                            style: {
                                fontSize: '10px'
                            },
                            hideOverlappingLabels: false,
                            trim: false
                        },
                        axisBorder: { show: true },
                        axisTicks: { show: true }
                    },
                    stroke: {
                        width: 3,
                        curve: 'smooth'
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.55,
                            opacityTo: 0,
                            stops: [0, 100],
                            gradientToColors: ['#E4763F']
                        }
                    },
                    dataLabels: { enabled: false },
                    grid: { show: true },
                    tooltip: {
                        x: { format: 'dd MMM' },
                        y: {
                            formatter: (val) => `Rp ${val.toLocaleString()}`
                        }
                    }
                }).render();
            }
        }" x-init="renderChart()" class="rounded-sm bg-none">
            <div class="w-full bg-tertiary rounded-2xl shadow-outer px-6 pt-6 pb-4">
                <div class="flex justify-between">
                    <div>
                        <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                            Rp {{ number_format(array_sum($pengeluaranPerHari), 0, ',', '.') }}
                        </h5>
                        <p class="font-normal">
                        <div class="text-lg text-primary font-semibold mb-1">Pengeluaran</div>
                        <div class="text-base text-gray-500 mb-2">{{ $labelPeriode }}</div>
                        </p>
                    </div>
                </div>

                <div x-ref="chart"></div>

                <div
                    class="grid grid-cols-1 items-center border-t border-gray-200 dark:border-gray-700 justify-between">
                    <div class="flex justify-end items-center pt-4">
                        <a href="#"
                            class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-primary hover:text-blue-700 dark:hover:text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                            Laporan Pengeluaran
                            <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 pb-6">
        {{-- Produk Terlaris --}}
        <div class="w-full bg-tertiary rounded-2xl shadow-outer">
            <div class="flex flex-col justify-between">
                <div class="border-b-2 border-b-tertiary-title-line py-5">
                    <span class="px-7 text-lg font-semibold">Produk Terlaris</span>
                </div>

                <div class="px-7 py-4 flex justify-end">
                    <form action="{{ route('dashboard') }}" method="GET"
                        class="w-2/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                        <input type="text" name="produk_search" value="{{ request('produk_search') }}"
                            placeholder="Cari"
                            class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                        <button type="submit"
                            class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                            <x-icons.cari />
                        </button>
                        @if (request('produk_search'))
                            <a href="{{ route('dashboard', request()->except(['produk_search', 'filter', 'page'])) }}"
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
                                <th class="px-4 py-3" align="center">Gambar</th>
                                <th class="px-4 py-3" align="center">Nama Produk</th>
                                <th class="px-4 py-3" align="center">
                                    <a href="{{ request()->fullUrlWithQuery(['produk_sort' => 'sold', 'produk_desc' => request('produk_desc') ? null : 1, 'produk_page' => 1]) }}"
                                        class="flex items-center justify-center uppercase">
                                        Total Terjual
                                        <x-icons.arrow-down
                                            class="ml-2 text-tertiary-300 {{ request('produk_sort') == 'sold' && request('produk_desc') ? 'rotate-180' : '' }}" />
                                    </a>

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produkTerlaris as $product)
                                <tr class="border-b-2 border-b-tertiary-table-line">
                                    <td class="px-4 py-2" align="center">
                                        <div
                                            class="flex items-center justify-center h-14 aspect-square rounded-full bg-white">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="h-12 object-contain">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2" align="center">{{ $product->name }}</td>
                                    <td class="px-4 py-2" align="center">{{ $product->sold }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-gray-500 italic">Produk tidak
                                        ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination Controls -->
            <div class="flex items-center mx-7 justify-between gap-4 pb-4">
                <span class="text-sm italic">
                    Menampilkan {{ $produkTerlaris->firstItem() }} - {{ $produkTerlaris->lastItem() }} dari
                    {{ $produkTerlaris->total() }}
                </span>

                <div
                    class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
                    {{-- Tombol Sebelumnya --}}
                    @if ($produkTerlaris->onFirstPage())
                        <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </span>
                    @else
                        <a href="{{ $produkTerlaris->previousPageUrl() . '&' . http_build_query(request()->except('produk_page')) }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                            <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($produkTerlaris->getUrlRange(1, $produkTerlaris->lastPage()) as $page => $url)
                        @php
                            $fullUrl = $url . '&' . http_build_query(request()->except('produk_page'));
                        @endphp
                        @if ($page == $produkTerlaris->currentPage())
                            <span
                                class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $fullUrl }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if ($produkTerlaris->hasMorePages())
                        <a href="{{ $produkTerlaris->nextPageUrl() . '&' . http_build_query(request()->except('produk_page')) }}"
                            class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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

        {{-- Stok Produk --}}
        <div class="w-full bg-tertiary rounded-2xl shadow-outer">
            <div class="flex flex-col justify-between">
                <div class="border-b-2 border-b-tertiary-title-line py-5">
                    <span class="px-7 text-lg font-semibold">Stok Produk</span>
                </div>
                <div class="px-7 py-4 flex justify-end">
                    <form action="{{ route('dashboard') }}" method="GET"
                        class="w-2/5 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                            class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                        <button type="submit"
                            class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                            <x-icons.cari />
                        </button>
                        @if (request('search'))
                            <a href="{{ route('dashboard', request()->except(['search', 'filter', 'page'])) }}"
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
                                <th class="px-4 py-3" align="center">Gambar</th>
                                <th class="px-4 py-3" align="center">Nama Produk</th>
                                <th class="px-4 py-3" align="center">Status</th>
                                <th class="px-4 py-3" align="center">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'desc' => request('desc') ? null : 1, 'page' => 1]) }}"
                                        class="flex items-center justify-center uppercase">
                                        Stok
                                        <x-icons.arrow-down
                                            class="ml-2 text-tertiary-300 {{ request('sort') == 'stock' && request('desc') ? 'rotate-180' : '' }}" />
                                    </a>
                                </th>
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
                                            class="flex items-center justify-center h-14 p-2 aspect-square object-contain rounded-full bg-white">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="max-h-11">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2" align="center">{{ $product->name }}</td>
                                    <td class="px-4 py-2" align="center">
                                        <span
                                            class="px-2 py-1 rounded-lg capitalize border-2 {{ $class }}">{{ $status }}</span>
                                    </td>
                                    <td class="px-4 py-2" align="center">{{ $product->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-gray-500 italic">Produk tidak
                                        ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Controls -->
                <div class="flex items-center mx-7 justify-between gap-4 pb-4">
                    <span class="text-sm italic">
                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari
                        {{ $products->total() }}
                    </span>

                    <div
                        class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
                        {{-- Tombol Sebelumnya --}}
                        @if ($products->onFirstPage())
                            <span class="px-3 py-2 text-gray-400 bg-tertiary cursor-default">
                                <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}"
                                class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">
                                <x-icons.arrow-down class="rotate-90 text-tertiary-title" />
                            </a>
                        @endif

                        {{-- Nomor Halaman --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <span
                                    class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Tombol Selanjutnya --}}
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 bg-tertiary hover:bg-gray-100">
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
    </div>
</x-layout>
