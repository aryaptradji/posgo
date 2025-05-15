<x-layout>
    <x-slot:title>Produk</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Produk</span>
            <button class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple">
                <x-icons.plus/>
                Buat
            </button>
        </div>
    </x-slot:header>

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12" x-data="{
        search: '',
        products: [
            { image: '/img/product/teh-botol.png', name: 'Teh Botol Sosro', stok: 0, pcs: '50', harga: 60000 },
            { image: '/img/product/panther.png', name: 'Panther', stok: 0, pcs: 30, harga: 70000 },
            { image: '/img/product/milku.png', name: 'Milku', stok: 1, pcs: 45, harga: 20000 },
            { image: '/img/product/floridina.png', name: 'Floridina', stok: 4, pcs: 25, harga: 72000 },
            { image: '/img/product/teh-kotak.png', name: 'Teh Kotak', stok: 46, pcs: 20, harga: 25000 }
        ],
        currentPage: 1,
        perPage: 5,
        open: false,
        selectedFilter: 'Semua',
        sortBy: '',
        sortDesc: false,
        get filteredProducts() {
            let filtered = this.products.filter(product => {
                const nameMatch = product.name.toLowerCase().includes(this.search.toLowerCase());
                const filterMatch =
                    this.selectedFilter === 'Semua' ||
                    (this.selectedFilter === 'Habis' && product.stok == 0) ||
                    (this.selectedFilter === 'Sedikit' && product.stok > 0 && product.stok <= 5) ||
                    (this.selectedFilter === 'Banyak' && product.stok > 5);
                return nameMatch && filterMatch;
            });

            if (this.sortBy === 'stok') {
                filtered.sort((a, b) => this.sortDesc ? b.stok - a.stok : a.stok - b.stok );
            } else if (this.sortBy === 'harga') {
                filtered.sort((a, b) => this.sortDesc ? b.harga - a.harga : a.harga - b.harga);
            }

            return filtered;
        },
        get totalPages() {
            return Math.ceil(this.filteredProducts.length / this.perPage);
        },
        get paginatedProducts() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredProducts.slice(start, start + this.perPage);
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
            <div class="px-7 py-4 flex justify-between">
                <div class="w-fit flex gap-4 items-center justify-center font-semibold">
                    <span @click="selectedFilter = 'Semua'; currentPage = 1" :class="selectedFilter === 'Semua' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Semua</span>
                    <span @click="selectedFilter = 'Banyak'; currentPage = 1" :class="selectedFilter === 'Banyak' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Banyak</span>
                    <span @click="selectedFilter = 'Sedikit'; currentPage = 1" :class="selectedFilter === 'Sedikit' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Sedikit</span>
                    <span @click="selectedFilter = 'Habis'; currentPage = 1" :class="selectedFilter === 'Habis' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Habis</span>
                </div>
                <div
                    class="w-1/5 px-3 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <img src="{{ asset('img/icon/cari.svg') }}" alt="cari.svg">
                    <input type="text" x-model="search" placeholder="Cari"
                        class="outline-none bg-transparent pt-1 ml-2 placeholder:text-tertiary-title">
                </div>
            </div>
            <div class="pb-4 relative overflow-x-auto overflow-y-auto max-h-[450px]">
                <table class="w-full min-w-max text-sm text-left dark:text-gray-400">
                    <thead class="text-xs uppercase bg-white">
                        <tr>
                            <th class="px-4 py-3 w-44" align="center">Gambar</th>
                            <th class="px-4 py-3 w-60" align="center">Nama Produk</th>
                            <th class="px-4 py-3" align="center">
                                <button class="flex items-center justify-center uppercase" type="button" @click="sortBy = 'stok'; sortDesc = !sortDesc">
                                    Stok
                                    <span class="ml-2 text-tertiary-300 transition-transform"
                                        :class="sortDesc && sortBy === 'stok' ? 'rotate-180' : 'rotate-0'">
                                        <x-icons.arrow-down />
                                    </span>
                                </button>
                            </th>
                            <th class="px-4 py-3" align="center">Pcs</th>
                            <th class="px-4 py-3 w-36" align="center">Status</th>
                            <th class="px-4 py-3 w-44" align="center">
                                <button type="button" class="flex items-center justify-center uppercase" @click="sortBy = 'harga'; sortDesc = !sortDesc">
                                    Harga
                                    <span class="ml-2 text-tertiary-300 transition-transform"
                                        :class="sortDesc && sortBy === 'harga' ? 'rotate-180' : 'rotate-0'">
                                        <x-icons.arrow-down />
                                    </span>
                                </button>
                            </th>
                            <th class="px-4 py-3 w-36" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(product, index) in paginatedProducts" :key="index">
                            <tr class="border-b-2 border-b-tertiary-table-line border-gray-200">
                                <td class="px-4 py-2" align="center">
                                    <div class="flex items-center justify-center h-14 aspect-square rounded-full bg-white">
                                        <img :src="product.image" class="h-12">
                                    </div>
                                </td>
                                <td class="px-4 py-2" align="center" x-text="product.name"></td>
                                <td class="px-4 py-2" align="center" x-text="product.stok"></td>
                                <td class="px-4 py-2" align="center" x-text="product.pcs"></td>
                                <td class="px-4 py-2" align="center">
                                    <span class="p-2 rounded-lg border-2"
                                    :class="{ 'bg-danger/15 text-danger border-danger': product.stok == 0, 'bg-warning-200/15 text-warning-200 border-warning-200': product.stok <= 5 && product.stok > 0, 'bg-success/15 text-success border-success': product.stok > 5 }"
                                        x-text="product.stok == 0 ? 'Habis' : product.stok <= 5 && product.stok > 0 ? 'Sedikit' : 'Banyak'"></span>
                                </td>
                                <td class="px-4 py-2" align="center" x-text="'Rp ' + product.harga"></td>
                                <td class="px-4 py-2" align="center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon/>
                                        </button>
                                        <button type="button" class="text-primary transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.edit-icon/>
                                        </button>
                                        <button type="button" class="text-danger transition-transform hover:scale-125 active:scale-90">
                                            @include('components.icons.delete-icon')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="flex items-center mx-7 justify-between gap-4 pb-4">
                <span class="text-sm italic">
                    Showing <span x-text="currentPage"></span> of <span x-text="totalPages"></span> pages
                </span>
                <div class="relative">
                    <button class="flex items-center px-3 bg-tertiary ring-1 ring-tertiary-300 rounded-lg text-sm" type="button"
                        @click="open = !open">
                        <span class="pe-3 py-2 border-r border-tertiary-300 text-tertiary-300">Per page</span>
                        <span class="px-3 py-2" x-text="perPage"></span>
                        <x-icons.arrow-down class="text-tertiary-300" />
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute bottom-10 right-0 bg-white rounded-lg shadow-sm w-14">
                        <ul class="py-2 text-sm text-gray-700">
                            <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                @click="perPage = 2; open = false; currentPage = 1">
                                <span>2</span>
                            </li>
                            <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                @click="perPage = 5; open = false; currentPage = 1">
                                <span>5</span>
                            </li>
                            <li class="block px-4 py-2 hover:bg-primary hover:text-white cursor-pointer text-center"
                                @click="perPage = 10; open = false; currentPage = 1">
                                <span>10</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Navigation -->
                <div class="flex items-center bg-tertiary text-sm">
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

</x-layout>
