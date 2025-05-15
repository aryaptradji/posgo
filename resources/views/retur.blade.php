<x-layout>
    <x-slot:title>Retur</x-slot:title>
    <x-slot:header>Retur</x-slot:header>

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12" x-data="{
        search: '',
        orders: [
            { kode: 'RET-20240930-00945', waktu: '30/9/2024 10:30', id_transaksi: 'TRX-20240930-00945', status: 'Ditolak', item: 60, total: 120000 },
            { kode: 'RET-20240929-00944', waktu: '29/9/2024 13:00', id_transaksi: 'TRX-20240929-00944', status: 'Menunggu konfirmasi', item: 30, total: 100000 },
            { kode: 'RET-20240929-00943', waktu: '29/9/2024 10:45', id_transaksi: 'TRX-20240929-00943', status: 'Menunggu konfirmasi', item: 40, total: 105000 },
            { kode: 'RET-20240928-00942', waktu: '28/9/2024 9:45', id_transaksi: 'TRX-20240928-00942', status: 'Diterima', item: 20, total: 90000 },
            { kode: 'RET-20240927-00941', waktu: '27/9/2024 11:23', id_transaksi: 'TRX-20240927-00941', status: 'Diterima', item: 100, total: 200000 }
        ],
        currentPage: 1,
        perPage: 5,
        open: false,
        selectedFilter: 'Semua',
        sortBy: '',
        sortDesc: false,
        get filteredOrders() {
            let filtered = this.orders.filter(order => {
                const searchMatch = order.kode.toLowerCase().includes(this.search.toLowerCase()) || order.id_transaksi.toLowerCase().includes(this.search.toLowerCase());
                const filterMatch = this.selectedFilter === 'Semua' || order.status === this.selectedFilter;
                return searchMatch && filterMatch;
            });

            if (this.sortBy === 'item') {
                filtered.sort((a, b) => this.sortDesc ? b.item - a.item : a.item - b.item);
            } else if (this.sortBy === 'total') {
                filtered.sort((a, b) => this.sortDesc ? b.total - a.total : a.total - b.total);
            }

            return filtered;
        },
        get totalPages() {
            return Math.ceil(this.filteredOrders.length / this.perPage);
        },
        get paginatedOrders() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredOrders.slice(start, start + this.perPage);
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
                    <span @click="selectedFilter = 'Diterima'; currentPage = 1" :class="selectedFilter === 'Diterima' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Diterima</span>
                    <span @click="selectedFilter = 'Menunggu konfirmasi'; currentPage = 1" :class="selectedFilter === 'Menunggu konfirmasi' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Menunggu konfirmasi</span>
                    <span @click="selectedFilter = 'Ditolak'; currentPage = 1" :class="selectedFilter === 'Ditolak' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Ditolak</span>
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
                            <th class="px-4 py-3" align="center">Kode</th>
                            <th class="px-4 py-3" align="center">Waktu</th>
                            <th class="px-4 py-3" align="center">ID Transaksi</th>
                            <th class="px-4 py-3" align="center">Status</th>
                            <th class="px-4 py-3" align="center">
                                <button class="flex items-center justify-center uppercase" type="button" @click="sortBy = 'item'; sortDesc = !sortDesc">
                                    Item
                                    <span class="ml-2 text-tertiary-300 transition-transform"
                                        :class="sortDesc && sortBy === 'item' ? 'rotate-180' : 'rotate-0'">
                                        <x-icons.arrow-down />
                                    </span>
                                </button>
                            </th>
                            <th class="px-4 py-3" align="center">
                                <button class="flex items-center justify-center uppercase" type="button" @click="sortBy = 'total'; sortDesc = !sortDesc">
                                    Total
                                    <span class="ml-2 text-tertiary-300 transition-transform"
                                        :class="sortDesc && sortBy === 'total' ? 'rotate-180' : 'rotate-0'">
                                        <x-icons.arrow-down />
                                    </span>
                                </button>
                            </th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(order, index) in filteredOrders" :key="index">
                            <tr class="border-b-2 border-b-tertiary-table-line border-gray-200">
                                <td class="px-4 py-2" align="center" x-text="order.kode"></td>
                                <td class="px-4 py-2" align="center" x-text="order.waktu"></td>
                                <td class="px-4 py-2" align="center" x-text="order.id_transaksi"></td>
                                <td class="px-4 py-4" align="center">
                                    <span class="px-2 py-1 rounded-lg border-2"
                                    :class="{ 'bg-danger/15 text-danger border-danger': order.status == 'Ditolak', 'bg-warning-200/15 text-warning-200 border-warning-200': order.status == 'Menunggu konfirmasi', 'bg-success/15 text-success border-success': order.status == 'Diterima' }"
                                        x-text="order.status"></span>
                                </td>
                                <td class="px-4 py-2" align="center" x-text="order.item"></td>
                                <td class="px-4 py-2" align="center" x-text="'Rp ' + order.total"></td>
                                <td class="px-4 py-2" align="center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon/>
                                        </button>
                                        <template x-if="order.status === 'Menunggu konfirmasi'">
                                            <button type="button" class="bg-secondary-blue text-white font-bold shadow-drop px-3 py-1 rounded-full transition-transform hover:scale-125 active:scale-90">
                                                Konfirmasi
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="filteredOrders.length === 0">
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 italic">Retur tidak ditemukan</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="flex items-center justify-between mx-7 pb-4">
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
