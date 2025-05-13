<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <title>Dashboard</title>
</head>

<body>
    <div id="container" class="flex">
        <header
            class="fixed z-10 top-0 w-screen h-24 px-6 clip-text bg-gradient-to-tr from-primary/60 to-secondary-purple/60 backdrop-blur-md flex justify-between items-center">
            <img src="{{ asset('img/posgo-logo.svg') }}" alt="posgo-logo.svg" class="w-28">
            <div class="flex justify-between items-center w-24 mr-5">
                <img src="{{ asset('img/icon/notification.svg') }}" alt="notification.svg" class="w-8">
                <span
                    class="flex items-center justify-center w-11 h-11 aspect-square rounded-full font-semibold text-2xl bg-tertiary-400">AA</span>
            </div>
        </header>

        <aside id="sidebar"
            class="fixed top-0 left-0 w-80 h-screen mt-24 transition-transform -translate-x-full sm:translate-x-0"
            aria-label="Sidebar">
            <div class="h-full p-6 overflow-y-auto bg-gray-50 dark:bg-gray-800">
                <ul class="space-y-3 font-medium" x-data="{ focus: 1 }">
                    <x-navlink href="#" @click="focus = 1" :focusNo="1">
                        <img :src="focus == 1 ? '{{ asset("img/icon/dashboard-white.svg") }}' :
                            '{{ asset("img/icon/dashboard-black.svg") }}'"
                            alt="dashboard.svg">
                        <span class="ms-3 font-semibold">Dashboard</span>
                    </x-navlink>
                    <x-navlink href="#" @click="focus = 2" :focusNo="2">
                        <img :src="focus == 2 ? '{{ asset("img/icon/produk-white.svg") }}' :
                            '{{ asset("img/icon/produk-black.svg") }}'"
                            alt="produk.svg">
                        <span class="ms-3 font-semibold">Produk</span>
                    </x-navlink>
                    <x-navlink-toggle :focusNo="3" :menus="[
                        [
                            'name' => 'Riwayat',
                            'iconActive' => asset('img/icon/riwayat-primary.svg'),
                            'iconInactive' => asset('img/icon/riwayat-black.svg'),
                        ],
                        [
                            'name' => 'Retur',
                            'iconActive' => asset('img/icon/retur-primary.svg'),
                            'iconInactive' => asset('img/icon/retur-black.svg'),
                        ],
                    ]" titleIconAct="img/icon/pesanan-white.svg"
                        titleIconInact="img/icon/pesanan-black.svg">
                        Pesanan
                    </x-navlink-toggle>
                    <x-navlink-toggle :focusNo="4" :menus="[
                        [
                            'name' => 'Pemasukan',
                            'iconActive' => asset('img/icon/pemasukan-primary.svg'),
                            'iconInactive' => asset('img/icon/pemasukan-black.svg'),
                        ],
                        [
                            'name' => 'Pengeluaran',
                            'iconActive' => asset('img/icon/pengeluaran-primary.svg'),
                            'iconInactive' => asset('img/icon/pengeluaran-black.svg'),
                        ],
                    ]" titleIconAct="img/icon/keuangan-white.svg"
                        titleIconInact="img/icon/keuangan-black.svg">
                        Keuangan
                    </x-navlink-toggle>
                    <x-navlink-toggle :focusNo="5" :menus="[
                        [
                            'name' => 'Kasir',
                            'iconActive' => asset('img/icon/kasir-primary.svg'),
                            'iconInactive' => asset('img/icon/kasir-black.svg'),
                        ],
                        [
                            'name' => 'Supplier',
                            'iconActive' => asset('img/icon/supplier-primary.svg'),
                            'iconInactive' => asset('img/icon/supplier-black.svg'),
                        ],
                        [
                            'name' => 'Customer',
                            'iconActive' => asset('img/icon/customer-primary.svg'),
                            'iconInactive' => asset('img/icon/customer-black.svg'),
                        ],
                        [
                            'name' => 'Kurir',
                            'iconActive' => asset('img/icon/kurir-primary.svg'),
                            'iconInactive' => asset('img/icon/kurir-black.svg'),
                        ],
                    ]" titleIconAct="img/icon/pengguna-white.svg"
                        titleIconInact="img/icon/pengguna-black.svg">
                        Pengguna
                    </x-navlink-toggle>
                    <x-navlink-toggle :focusNo="6" :menus="[
                        [
                            'name' => 'Kelola',
                            'iconActive' => asset('img/icon/kelola-primary.svg'),
                            'iconInactive' => asset('img/icon/kelola-black.svg'),
                        ],
                        [
                            'name' => 'Invoice',
                            'iconActive' => asset('img/icon/invoice-primary.svg'),
                            'iconInactive' => asset('img/icon/invoice-black.svg'),
                        ],
                    ]" titleIconAct="img/icon/po-white.svg"
                        titleIconInact="img/icon/po-black.svg">
                        Purchase Order
                    </x-navlink-toggle>
                </ul>
            </div>
        </aside>

        <main class="p-8 ml-80 mt-24 flex-grow bg-tertiary">
            <header class="text-3xl font-semibold mb-6">
                Dashboard
            </header>
            <div class="flex items-end justify-center gap-14 h-44 pb-8 px-8 mb-6 rounded-2xl shadow-outer bg-tertiary">
                <x-textfield-outline class="w-full" type="date" id="dari_tanggal" name="dari_tanggal">Dari
                    Tanggal</x-textfield-outline>
                <x-textfield-outline class="w-full" type="date">Sampai Tanggal</x-textfield-outline>
            </div>
            <div class="h-40 grid grid-cols-4 gap-6 mb-11">
                <div
                    class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
                    <p class="text-tertiary-title font-semibold">
                        Jumlah Pesanan
                    </p>
                    <span class="text-4xl font-semibold">10</span>
                </div>
                <div
                    class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
                    <p class="text-tertiary-title font-semibold">
                        Pemasukan
                    </p>
                    <span class="text-4xl font-semibold">Rp 1.100.100</span>
                    <img src="{{ asset('img/icon/omset.svg') }}" alt="omset.svg" class="h-7">
                </div>
                <div
                    class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
                    <p class="text-tertiary-title font-semibold">
                        Pengeluaran
                    </p>
                    <span class="text-4xl font-semibold">Rp 300.000</span>
                    <img src="{{ asset('img/icon/expense.svg') }}" alt="expense.svg" class="h-6">
                </div>
                <div
                    class="px-7 py-7 flex flex-col gap-4 items-start justify-start rounded-2xl shadow-outer bg-tertiary">
                    <p class="text-tertiary-title font-semibold">
                        Laba Bersih
                    </p>
                    <span class="text-4xl font-semibold">Rp 800.536</span>
                    <img src="{{ asset('img/icon/net profit.svg') }}" alt="net profit.svg" class="h-5 mt-1">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="rounded-sm bg-none">
                    <div class="w-full bg-tertiary rounded-2xl shadow-outer p-7">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">42.8k
                                </h5>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">Pemasukan minggu ini
                                </p>
                            </div>
                            <div
                                class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                                15%
                                <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                                </svg>
                            </div>
                        </div>
                        <div id="chart-pemasukan"></div>
                        <div
                            class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                            <div class="flex justify-between items-center pt-5">
                                <!-- Button -->
                                <button id="dropdownDefaultButton" data-dropdown-toggle="pemasukanDropdown"
                                    data-dropdown-placement="bottom"
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                    type="button">
                                    7 hari terakhir
                                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="pemasukanDropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Kemarin</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Hari
                                                ini</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">7
                                                hari terakhir</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">30
                                                hari terakhir</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">90
                                                hari terakhir</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#"
                                    class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-secondary-purple hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
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
                </div class="rounded-sm bg-none">
                <!-- Chart Pengeluaran -->
                <div class="rounded-sm bg-none">
                    <div class="w-full bg-tertiary rounded-2xl shadow-outer dark:bg-gray-800 p-7">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">32.4k
                                </h5>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">Pengeluaran minggu
                                    ini</p>
                            </div>
                            <div
                                class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                                12%
                                <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                                </svg>
                            </div>
                        </div>
                        <div id="chart-pengeluaran"></div>
                        <div
                            class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                            <div class="flex justify-between items-center pt-5">
                                <!-- Button -->
                                <button id="dropdownDefaultButton" data-dropdown-toggle="pengeluaranDropdown"
                                    data-dropdown-placement="bottom"
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                    type="button">
                                    7 hari terakhir
                                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="pengeluaranDropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Kemarin</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Hari
                                                ini</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">7
                                                hari terakhir</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">30
                                                hari terakhir</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">90
                                                hari terakhir</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#"
                                    class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-primary hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
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
            <div class="grid grid-cols-2 gap-6">
                <div class="w-full bg-tertiary rounded-2xl shadow-outer" x-data="{
                    search: '',
                    products: [
                        { image: '/img/product/teh-botol.png', name: 'Teh Botol Sosro', sold: 16 },
                        { image: '/img/product/panther.png', name: 'Panther', sold: 5 },
                        { image: '/img/product/milku.png', name: 'Milku', sold: 5 },
                        { image: '/img/product/floridina.png', name: 'Floridina', sold: 8 },
                        { image: '/img/product/teh-kotak.png', name: 'Teh Kotak', sold: 12 }
                    ],
                    currentPage: 1,
                    perPage: 5,
                    open: false,
                    get totalPages() {
                        return Math.ceil(this.products.length / this.perPage);
                    },
                    get paginatedProducts() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.products.slice(start, start + this.perPage);
                    },
                    get visiblePages() {
                        const pages = [];
                        if (this.currentPage <= 1) {
                        pages.push(1);
                        if (this.totalPages > 1) pages.push(2);
                        } else if (this.currentPage >= this.totalPages) {
                        if (this.totalPages > 1) pages.push(this.totalPages - 1);
                        pages.push(this.totalPages);
                        } else {
                        pages.push(this.currentPage);
                        pages.push(this.currentPage + 1);
                        }
                        return pages;
                    }
                }">
                    <div class="flex flex-col justify-between">
                        <div class="border-b-2 border-b-tertiary-title-line py-5">
                            <span class="px-7 text-lg font-semibold">Produk Terlaris</span>
                        </div>
                        <div class="px-7 py-4 flex justify-end">
                            <div
                                class="w-2/5 px-3 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon/cari.svg') }}" alt="cari.svg">
                                <input type="text" x-model="search" placeholder="Cari"
                                    class="outline-none bg-gray-50 pt-1 ml-2 placeholder:text-tertiary-title">
                            </div>
                        </div>
                        <div class="pb-4 relative overflow-x-auto overflow-y-auto max-h-[450px]">
                            <table class="w-full min-w-max text-sm text-left dark:text-gray-400">
                                <thead class="text-xs uppercase bg-white">
                                    <tr>
                                        <th class="px-4 py-3 w-36" align="center">Gambar</th>
                                        <th class="px-4 py-3 w-48" align="center">Nama Produk</th>
                                        <th class="px-4 py-3" align="center">Jumlah Pembelian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(product, index) in paginatedProducts.filter(product => product.name.toLowerCase().includes(search.toLowerCase()) || product.category.toLowerCase().includes(search.toLowerCase()) || product.color.toLowerCase().includes(search.toLowerCase()))" :key="index">
                                        <tr class="border-b-2 border-b-tertiary-table-line border-gray-200">
                                            <td class="px-4 py-2" align="center">
                                                <div class="flex items-center justify-center h-14 aspect-square rounded-full bg-white">
                                                    <img :src="product.image" class="h-12">
                                                </div>
                                            </td>
                                            <td class="px-4 py-2" align="center" x-text="product.name"></td>
                                            <td class="px-4 py-2" align="center" x-text="product.sold"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Controls -->
                        <div class="flex items-center justify-end gap-4 pb-4">
                            <div class="relative">
                                <button class="flex items-center px-3 bg-tertiary ring-1 ring-tertiary-300 rounded-lg text-sm" type="button"
                                    @click="open = !open">
                                    <span class="pe-3 py-2 border-r border-tertiary-300 text-tertiary-300">Per page</span>
                                    <span class="px-3 py-2" x-text="perPage"></span>
                                    <x-icons.arrow-down />
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute bottom-10 right-0 bg-white rounded-lg shadow-sm w-14">
                                    <ul class="py-2 text-sm text-gray-700">
                                        <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                            @click="perPage = 2; open = false; currentPage = 1">
                                            <span>2</span>
                                        </li>
                                        <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                            @click="perPage = 4; open = false; currentPage = 1">
                                            <span>4</span>
                                        </li>
                                        <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                            @click="perPage = 6; open = false; currentPage = 1">
                                            <span>6</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Navigation -->
                            <div class="flex items-center mr-7 bg-tertiary text-sm">
                                <!-- Prev Button -->
                                <template x-if="currentPage > 1">
                                    <button
                                        @click="if (currentPage > 1) currentPage--"
                                        :disabled="currentPage === 1"
                                        class="flex items-center bg-tertiary rounded-l-lg"
                                    >
                                        <span class="px-3 py-2 border rounded-l-lg border-tertiary-300">‹</span>
                                    </button>
                                </template>
                                <!-- Page Numbers -->
                                <template x-for="page in visiblePages" :key="page">
                                    <button
                                        class="flex items-center bg-tertiary"
                                        @click="currentPage = page" type="button">
                                        <span :class="{ 'shadow-inner-pag': currentPage == page, 'rounded-l-lg': currentPage == 1 && page == 1, 'rounded-r-lg': currentPage == totalPages && page == totalPages }"
                                        class="px-3 py-2 border border-tertiary-300" x-text="page"></span>
                                    </button>
                                </template>
                                <!-- Next Button -->
                                <template x-if="currentPage < totalPages">
                                    <button
                                        class="flex items-center text-sm"
                                        :disabled="currentPage === totalPages"
                                        @click="if (currentPage < totalPages) currentPage++">
                                        <span class="px-3 py-2 border rounded-r-lg border-tertiary-300">
                                            >
                                        </span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-tertiary rounded-2xl shadow-outer" x-data="{
                    search: '',
                    products: [
                        { image: '/img/product/teh-botol.png', name: 'Teh Botol Sosro', stok: 0 },
                        { image: '/img/product/panther.png', name: 'Panther', stok: 0 },
                        { image: '/img/product/milku.png', name: 'Milku', stok: 1 },
                        { image: '/img/product/floridina.png', name: 'Floridina', stok: 4 },
                        { image: '/img/product/teh-kotak.png', name: 'Teh Kotak', stok: 46 }
                    ],
                    currentPage: 1,
                    perPage: 5,
                    open: false,
                    get totalPages() {
                        return Math.ceil(this.products.length / this.perPage);
                    },
                    get paginatedProducts() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.products.slice(start, start + this.perPage);
                    },
                    get visiblePages() {
                        const pages = [];
                        if (this.currentPage <= 1) {
                        pages.push(1);
                        if (this.totalPages > 1) pages.push(2);
                        } else if (this.currentPage >= this.totalPages) {
                        if (this.totalPages > 1) pages.push(this.totalPages - 1);
                        pages.push(this.totalPages);
                        } else {
                        pages.push(this.currentPage);
                        pages.push(this.currentPage + 1);
                        }
                        return pages;
                    }
                }">
                    <div class="flex flex-col justify-between">
                        <div class="border-b-2 border-b-tertiary-title-line py-5">
                            <span class="px-7 text-lg font-semibold">Stok Produk</span>
                        </div>
                        <div class="px-7 py-4 flex justify-end">
                            <div
                                class="w-2/5 px-3 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon/cari.svg') }}" alt="cari.svg">
                                <input type="text" x-model="search" placeholder="Cari"
                                    class="outline-none bg-gray-50 pt-1 ml-2 placeholder:text-tertiary-title">
                            </div>
                        </div>
                        <div class="pb-4 relative overflow-x-auto overflow-y-auto max-h-[450px]">
                            <table class="w-full min-w-max text-sm text-left dark:text-gray-400">
                                <thead class="text-xs uppercase bg-white">
                                    <tr>
                                        <th class="px-4 py-3 w-36" align="center">Gambar</th>
                                        <th class="px-4 py-3 w-48" align="center">Nama Produk</th>
                                        <th class="px-4 py-3" align="center">Status</th>
                                        <th class="px-4 py-3" align="center">Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(product, index) in paginatedProducts.filter(product => product.name.toLowerCase().includes(search.toLowerCase()) || product.category.toLowerCase().includes(search.toLowerCase()) || product.color.toLowerCase().includes(search.toLowerCase()))" :key="index">
                                        <tr class="border-b-2 border-b-tertiary-table-line border-gray-200">
                                            <td class="px-4 py-2" align="center">
                                                <div class="flex items-center justify-center h-14 aspect-square rounded-full bg-white">
                                                    <img :src="product.image" class="h-12">
                                                </div>
                                            </td>
                                            <td class="px-4 py-2" align="center" x-text="product.name"></td>
                                            <td class="px-4 py-2" align="center">
                                                <span class="p-2 rounded-lg border-2"
                                                :class="{ 'bg-danger/15 text-danger border-danger': product.stok == 0, 'bg-warning-200/15 text-warning-200 border-warning-200': product.stok <= 5 && product.stok > 0, 'bg-success/15 text-success border-success': product.stok > 5 }"
                                                 x-text="product.stok == 0 ? 'Habis' : product.stok <= 5 && product.stok > 0 ? 'Sedikit' : 'Banyak'"></span>
                                            </td>
                                            <td class="px-4 py-2" align="center" x-text="product.stok"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Controls -->
                        <div class="flex items-center justify-end gap-4 pb-4">
                            <div class="relative">
                                <button class="flex items-center px-3 bg-tertiary ring-1 ring-tertiary-300 rounded-lg text-sm" type="button"
                                    @click="open = !open">
                                    <span class="pe-3 py-2 border-r border-tertiary-300 text-tertiary-300">Per page</span>
                                    <span class="px-3 py-2" x-text="perPage"></span>
                                    <x-icons.arrow-down />
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute bottom-10 right-0 bg-white rounded-lg shadow-sm w-14">
                                    <ul class="py-2 text-sm text-gray-700">
                                        <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                            @click="perPage = 2; open = false; currentPage = 1">
                                            <span>2</span>
                                        </li>
                                        <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                            @click="perPage = 4; open = false; currentPage = 1">
                                            <span>4</span>
                                        </li>
                                        <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                            @click="perPage = 6; open = false; currentPage = 1">
                                            <span>6</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Navigation -->
                            <div class="flex items-center mr-7 bg-tertiary text-sm">
                                <!-- Prev Button -->
                                <template x-if="currentPage > 1">
                                    <button
                                        @click="if (currentPage > 1) currentPage--"
                                        :disabled="currentPage === 1"
                                        class="flex items-center bg-tertiary rounded-l-lg"
                                    >
                                        <span class="px-3 py-2 border rounded-l-lg border-tertiary-300">‹</span>
                                    </button>
                                </template>
                                <!-- Page Numbers -->
                                <template x-for="page in visiblePages" :key="page">
                                    <button
                                        class="flex items-center bg-tertiary"
                                        @click="currentPage = page" type="button">
                                        <span :class="{ 'shadow-inner-pag': currentPage == page, 'rounded-l-lg': currentPage == 1 && page == 1, 'rounded-r-lg': currentPage == totalPages && page == totalPages }"
                                        class="px-3 py-2 border border-tertiary-300" x-text="page"></span>
                                    </button>
                                </template>
                                <!-- Next Button -->
                                <template x-if="currentPage < totalPages">
                                    <button
                                        class="flex items-center text-sm"
                                        :disabled="currentPage === totalPages"
                                        @click="if (currentPage < totalPages) currentPage++">
                                        <span class="px-3 py-2 border rounded-r-lg border-tertiary-300">
                                            >
                                        </span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div id="container" class="flex">

    <script>
        const optionsPengeluaran = {
            chart: {
                height: "100%",
                maxWidth: "100%",
                type: "area",
                fontFamily: "Inter, sans-serif",
                dropShadow: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.55,
                    opacityTo: 0,
                    shade: "#E4763F",
                    gradientToColors: ["#E4763F"],
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 6,
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: 0
                },
            },
            series: [{
                name: "New users",
                data: [6500, 6418, 6456, 6526, 6356, 6456],
                color: "#E4763F",
            }, ],
            xaxis: {
                categories: ['01 February', '02 February', '03 February', '04 February', '05 February', '06 February',
                    '07 February'
                ],
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            }
        }

        const optionsPemasukan = {
            chart: {
                height: "100%",
                maxWidth: "100%",
                type: "area",
                fontFamily: "Inter, sans-serif",
                dropShadow: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.55,
                    opacityTo: 0,
                    shade: "#7A24F9",
                    gradientToColors: ["#7A24F9"],
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 6,
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: 0
                },
            },
            series: [{
                name: "New users",
                data: [6500, 6418, 6456, 6526, 6356, 6456],
                color: "#7A24F9",
            }, ],
            xaxis: {
                categories: ['01 February', '02 February', '03 February', '04 February', '05 February', '06 February',
                    '07 February'
                ],
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            }
        }

        const chartPemasukan = new ApexCharts(document.getElementById("chart-pemasukan"), optionsPemasukan);
        const chartPengeluaran = new ApexCharts(document.getElementById("chart-pengeluaran"), optionsPengeluaran);
        chartPemasukan.render();
        chartPengeluaran.render();
    </script>
</body>

</html>
