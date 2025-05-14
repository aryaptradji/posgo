<header
    class="fixed z-10 top-0 w-screen h-24 px-6 clip-text bg-gradient-to-tr from-primary/60 to-secondary-purple/60 backdrop-blur-md flex justify-between items-center">
    <img src="{{ asset('img/posgo-logo.svg') }}" alt="posgo-logo.svg" class="w-28">
    <div class="flex justify-between items-center w-24 mr-5">
        <img src="{{ asset('img/icon/notification.svg') }}" alt="notification.svg" class="w-8">
        <span
            class="flex items-center justify-center w-11 h-11 aspect-square rounded-full font-semibold text-2xl bg-tertiary-400">AA</span>
    </div>
</header>

<nav id="sidebar"
    class="fixed top-24 left-0 w-80 h-[calc(100vh-6rem)] transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full p-6 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <ul class="space-y-3 font-medium" x-data="{ focus: 1 }">
            <x-navlink href="/dashboard" :active="request()->is('dashboard')" :focusNo="1" @click="focus = 1">
                <x-icons.dashboard/>
                <x-slot:title>Dashboard</x-slot:title>
            </x-navlink>
            <x-navlink href="/produk" :active="request()->is('produk')" :focusNo="2" @click="focus = 2">
                <x-icons.produk/>
                <x-slot:title>Produk</x-slot:title>
            </x-navlink>
            <x-navlink-toggle :focusNo="3" :menus="[
                [
                    'name' => 'Riwayat',
                    'active' => request()->is('riwayat'),
                    'icon' => view('components.icons.riwayat')->render()
                ],
                [
                    'name' => 'Retur',
                    'active' => request()->is('retur'),
                    'icon' => view('components.icons.retur')->render()
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.pesanan/>
                </x-slot:titleIcon>
                Pesanan
            </x-navlink-toggle>
            <x-navlink-toggle :focusNo="4" :menus="[
                [
                    'name' => 'Pemasukan',
                    'active' => request()->is('pemasukan'),
                    'icon' => view('components.icons.pemasukan')->render()
                ],
                [
                    'name' => 'Pengeluaran',
                    'active' => request()->is('pengeluaran'),
                    'icon' => view('components.icons.pengeluaran')->render()
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.keuangan/>
                </x-slot:titleIcon>
                Keuangan
            </x-navlink-toggle>
            <x-navlink-toggle :focusNo="5" :menus="[
                [
                    'name' => 'Kasir',
                    'active' => request()->is('kasir'),
                    'icon' => view('components.icons.kasir')->render()
                ],
                [
                    'name' => 'Supplier',
                    'active' => request()->is('supplier'),
                    'icon' => view('components.icons.supplier')->render()
                ],
                [
                    'name' => 'Customer',
                    'active' => request()->is('customer'),
                    'icon' => view('components.icons.customer')->render()
                ],
                [
                    'name' => 'Kurir',
                    'active' => request()->is('kurir'),
                    'icon' => view('components.icons.kurir')->render()
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.pengguna/>
                </x-slot:titleIcon>
                Pengguna
            </x-navlink-toggle>
            <x-navlink-toggle :focusNo="6" :menus="[
                [
                    'name' => 'Kelola',
                    'active' => request()->is('kelola'),
                    'icon' => view('components.icons.kelola')->render()
                ],
                [
                    'name' => 'Invoice',
                    'active' => request()->is('invoice'),
                    'icon' => view('components.icons.invoice')->render()
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.po/>
                </x-slot:titleIcon>
                Purchase Order
            </x-navlink-toggle>
        </ul>
    </div>
</nav>
