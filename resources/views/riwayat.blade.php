<x-layout>
    <x-slot:title>Riwayat</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Riwayat</span>
            <div class="flex gap-6">
                <button class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple">
                    <x-icons.print/>
                    Print
                </button>
                <button class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-primary">
                    <x-icons.export/>
                    Export
                </button>
            </div>
        </div>
    </x-slot:header>

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12" x-data="{
        search: '',
        orders: [
            { kode: 'TRX-20240930-00945', waktu: '30/9/2024 10:30', name: 'Rudi Gunawan', kategori: 'Offline', status: 'Belum dikirim', item: 60, total: 120000 },
            { kode: 'TRX-20240929-00944', waktu: '29/9/2024 13:00', name: 'Bella Graceva', kategori: 'Online', status: 'Dalam perjalanan', item: 30, total: 100000 },
            { kode: 'TRX-20240929-00943', waktu: '29/9/2024 10:45', name: 'Pragiyono', kategori: 'Offline', status: 'Dalam perjalanan', item: 40, total: 105000 },
            { kode: 'TRX-20240928-00942', waktu: '28/9/2024 9:45', name: 'Sultan Aeron', kategori: 'Online', status: 'Selesai', item: 20, total: 90000 },
            { kode: 'TRX-20240927-00941', waktu: '27/9/2024 11:23', name: 'Mulyono', kategori: 'Offline', status: 'Selesai', item: 100, total: 200000 }
        ],
        currentPage: 1,
        perPage: 5,
        open: false,
        selectedFilter: 'Semua',
        sortBy: '',
        sortAsc: true,
        get filteredOrders() {
            let filtered = this.orders.filter(order => {
                const nameMatch = order.name.toLowerCase().includes(this.search.toLowerCase()) || order.kode.toLowerCase().includes(this.search.toLowerCase());
                const filterMatch = this.selectedFilter === 'Semua' || order.status === this.selectedFilter;
                return nameMatch && filterMatch;
            });

            if (this.sortBy === 'item') {
                filtered.sort((a, b) => this.sortAsc ? a.item - b.item : b.item - a.item);
            } else if (this.sortBy === 'kategori') {
                filtered.sort((a, b) => {
                    return this.sortAsc
                        ? a.kategori.localeCompare(b.kategori)
                        : b.kategori.localeCompare(a.kategori);
                });
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
                    <span @click="selectedFilter = 'Selesai'; currentPage = 1" :class="selectedFilter === 'Selesai' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Selesai</span>
                    <span @click="selectedFilter = 'Dalam perjalanan'; currentPage = 1" :class="selectedFilter === 'Dalam perjalanan' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Dalam perjalanan</span>
                    <span @click="selectedFilter = 'Belum dikirim'; currentPage = 1" :class="selectedFilter === 'Belum dikirim' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Belum dikirim</span>
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
                            <th class="px-4 py-3" align="center">Nama</th>
                            <th class="px-4 py-3" align="center">
                                <button class="flex items-center justify-center uppercase" type="button" @click="sortBy = 'kategori'; sortAsc = !sortAsc">
                                    Kategori
                                    <span class="ml-2 text-tertiary-300 transition-transform"
                                        :class="sortAsc ? 'rotate-0' : 'rotate-180'">
                                        <x-icons.arrow-down />
                                    </span>
                                </button>
                            </th>
                            <th class="px-4 py-3" align="center">Status</th>
                            <th class="px-4 py-3" align="center">
                                <button class="flex items-center justify-center uppercase" type="button" @click="sortBy = 'item'; sortAsc = !sortAsc">
                                    Item
                                    <span class="ml-2 text-tertiary-300 transition-transform"
                                        :class="sortAsc ? 'rotate-0' : 'rotate-180'">
                                        <x-icons.arrow-down />
                                    </span>
                                </button>
                            </th>
                            <th class="px-4 py-3" align="center">Total</th>
                            <th class="px-4 py-3" align="center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(order, index) in paginatedOrders" :key="index">
                            <tr class="border-b-2 border-b-tertiary-table-line border-gray-200">
                                <td class="px-4 py-2" align="center" x-text="order.kode"></td>
                                <td class="px-4 py-2" align="center" x-text="order.waktu"></td>
                                <td class="px-4 py-2" align="center" x-text="order.name"></td>
                                <td class="px-4 py-2" align="center" x-text="order.kategori"></td>
                                <td class="px-4 py-4" align="center">
                                    <span class="p-2 rounded-lg border-2"
                                    :class="{ 'bg-danger/15 text-danger border-danger': order.status == 'Belum dikirim', 'bg-warning-200/15 text-warning-200 border-warning-200': order.status == 'Dalam perjalanan', 'bg-success/15 text-success border-success': order.status == 'Selesai' }"
                                        x-text="order.status"></span>
                                </td>
                                <td class="px-4 py-2" align="center" x-text="order.item"></td>
                                <td class="px-4 py-2" align="center" x-text="'Rp ' + order.total"></td>
                                <td class="px-4 py-2" align="center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" class="text-secondary-purple transition-transform hover:scale-125 active:scale-90">
                                            <x-icons.detail-icon/>
                                        </button>
                                        <template x-if="order.status === 'Belum dikirim'">
                                            <button type="button" class="transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.send-icon/>
                                            </button>
                                        </template>
                                        <template x-if="order.status === 'Dalam perjalanan'">
                                            <button type="button" class="transition-transform hover:scale-125 active:scale-90">
                                                <x-icons.upload-icon/>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="flex items-center justify-end gap-4 pb-4">
                <div class="relative w-1/2">
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
</x-layout>
