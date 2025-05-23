<header
    class="fixed z-20 top-0 w-screen h-24 px-6 clip-text bg-gradient-to-tr from-primary/60 to-secondary-purple/60 backdrop-blur-md flex justify-between items-center">
    <img src="{{ asset('img/posgo-logo.svg') }}" alt="posgo-logo.svg" class="w-28">
    <div class="flex justify-between items-center w-24 mr-5">
        <img src="{{ asset('img/icon/notification.svg') }}" alt="notification.svg" class="w-8">
        <span
            class="flex items-center justify-center w-11 h-11 aspect-square rounded-full font-semibold text-2xl bg-tertiary-400">AA</span>
    </div>
</header>

<nav id="sidebar"
    class="fixed z-10 top-0 pt-24 left-0 w-80 h-full bg-gray-50 transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full p-6 overflow-y-auto bg-gray-50">
        <ul class="space-y-3 font-medium" x-data="{ focus: 1 }">
            <x-navlink href="{{ route('dashboard.index') }}" :active="request()->is('admin/dashboard')" :focusNo="1" @click="focus = 1">
                <x-icons.dashboard/>
                <x-slot:title>Dashboard</x-slot:title>
            </x-navlink>
            <x-navlink href="{{ route('product.index') }}" :active="request()->is('admin/product/' . '*') || request()->is('admin/product')" :focusNo="2" @click="focus = 2">
                <x-icons.produk/>
                <x-slot:title>Produk</x-slot:title>
            </x-navlink>
            <x-navlink-toggle :focusNo="3" :menus="[
                [
                    'name' => 'Riwayat',
                    'active' => request()->is('riwayat'),
                    'icon' => view('components.icons.riwayat')->render(),
                    'route' => ''
                ],
                [
                    'name' => 'Retur',
                    'active' => request()->is('retur'),
                    'icon' => view('components.icons.retur')->render(),
                    'route' => ''
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
                    'icon' => view('components.icons.pemasukan')->render(),
                    'route' => ''
                ],
                [
                    'name' => 'Pengeluaran',
                    'active' => request()->is('admin/expense') || request()->is('admin/expense' . '*'),
                    'icon' => view('components.icons.pengeluaran')->render(),
                    'route' => route('expense.index')
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
                    'active' => request()->is('admin/cashier') || request()->is('admin/cashier' . '*'),
                    'icon' => view('components.icons.kasir')->render(),
                    'route' => route('cashier.index')
                ],
                [
                    'name' => 'Supplier',
                    'active' => request()->is('supplier'),
                    'icon' => view('components.icons.supplier')->render(),
                    'route' => ''
                ],
                [
                    'name' => 'Customer',
                    'active' => request()->is('customer'),
                    'icon' => view('components.icons.customer')->render(),
                    'route' => ''
                ],
                [
                    'name' => 'Kurir',
                    'active' => request()->is('kurir'),
                    'icon' => view('components.icons.kurir')->render(),
                    'route' => ''
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
                    'icon' => view('components.icons.kelola')->render(),
                    'route' => ''
                ],
                [
                    'name' => 'Invoice',
                    'active' => request()->is('invoice'),
                    'icon' => view('components.icons.invoice')->render(),
                    'route' => ''
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
