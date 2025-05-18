<x-layout>
    <x-slot:title>Kasir</x-slot:title>
    <x-slot:header>
        <div class="flex justify-between items-center">
            <span>Kasir</span>
            <div class="flex gap-6">
                <button class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-blue transition-all hover:scale-105 active:scale-90">
                    <x-icons.print/>
                    Print
                </button>
                <button class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-primary transition-all hover:scale-105 active:scale-90">
                    <x-icons.export/>
                    Export
                </button>
                <button class="flex justify-between items-center gap-2 px-4 py-3 font-semibold text-base rounded-lg text-white bg-secondary-purple transition-all hover:scale-105 active:scale-90">
                    <x-icons.plus/>
                    Buat
                </button>
            </div>
        </div>
    </x-slot:header>

    <div class="w-full bg-tertiary rounded-2xl shadow-outer mt-12" x-data="{
        search: '',
        cashiers: [
            { name: 'Alif Solahudin', foto: '/img/kasir/alif-solahudin.png', status: 'Operasional', total: 120000 },
            { name: 'Budi Wahyudi', foto: 'Bayar Karyawan', status: 'Operasional', total: 2000000 },
            { name: 'Celiboy', foto: 'Bayar Listrik', status: 'Operasional', total: 200000 },
            { name: 'Dedi Cahyadi', foto: 'Donasi Anak Yatim', status: 'Luar Operasional', total: 300000 },
            { name: 'Eman Richard', foto: 'Biaya Perbaikan', status: 'Operasional', total: 70000 }
        ],
        currentPage: 1,
        perPage: 5,
        open: false,
        selectedFilter: 'Semua',
        sortBy: '',
        sortDesc: false,
        getStatusClass(status) {
            if (status === 'Operasional') return 'bg-secondary-purple/15 text-secondary-purple border-secondary-purple';
            return 'bg-primary/15 text-primary border-primary';
        },
        get filteredOutcomes() {
            let filtered = this.outcomes.filter(outcome => {
                const searchMatch = outcome.sumber.toLowerCase().includes(this.search.toLowerCase());
                const filterMatch = this.selectedFilter === 'Semua' || outcome.status === this.selectedFilter;
                return searchMatch && filterMatch;
            });

            if (this.sortBy === 'total') {
                filtered.sort((a, b) => this.sortDesc ? b.total - a.total : a.total - b.total );
            }

            return filtered;
        },
        get totalPages() {
            return Math.ceil(this.filteredOutcomes.length / this.perPage);
        },
        get paginatedOutcomes() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredOutcomes.slice(start, start + this.perPage);
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
                    <span @click="selectedFilter = 'Operasional'; currentPage = 1" :class="selectedFilter === 'Operasional' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Operasional</span>
                    <span @click="selectedFilter = 'Luar Operasional'; currentPage = 1" :class="selectedFilter === 'Luar Operasional' ? 'bg-primary text-white shadow-outer-sidebar-primary scale-105' : 'bg-tertiary-title-line text-black'"
                        class="px-3 py-2 rounded-lg transition-all duration-500 cursor-pointer">Luar Operasional</span>
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
                            <th class="px-4 py-3" align="center">Tanggal</th>
                            <th class="px-4 py-3" align="center">Sumber</th>
                            <th class="px-4 py-3" align="center">Status</th>
                            <th class="px-4 py-3" align="center">
                                <button type="button" class="flex items-center justify-center uppercase" @click="sortBy = 'total'; sortDesc = !sortDesc">
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
                        <template x-for="(outcome, index) in paginatedOutcomes" :key="index">
                            <tr class="border-b-2 border-b-tertiary-table-line border-gray-200">
                                <td class="px-4 py-2" align="center" x-text="outcome.tanggal"></td>
                                <td class="px-4 py-2" align="center" x-text="outcome.sumber"></td>
                                <td class="p-4" align="center">
                                    <span class="px-2 py-1 rounded-lg border-2" :class="getStatusClass(outcome.status)" x-text="outcome.status"></span>
                                </td>
                                <td class="px-4 py-2" align="center" x-text="'Rp ' + outcome.total"></td>
                                <td class="px-4 py-2" align="center">
                                    <div class="flex justify-center gap-2">
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
                        <template x-if="paginatedOutcomes.length === 0">
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 italic">Pengeluaran tidak ditemukan</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="flex items-center mx-7 justify-between pb-4">
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
