@php
    $user = Auth::user();
    $parts = explode(' ', $user->name);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    $class =
        $user->role === 'admin'
            ? 'bg-danger/15 text-danger border-danger'
            : ($user->role === 'cashier'
                ? 'bg-primary/15 text-primary border-primary'
                : 'bg-secondary-purple/15 text-secondary-purple border-secondary-purple');

@endphp

<header
    class="fixed z-20 top-0 w-screen h-24 px-6 backdrop-blur-md bg-white/30 shadow-md flex justify-between items-center">
    <img src="{{ asset('img/posgo-logo.svg') }}" alt="posgo-logo.svg" class="w-28">
    <div class="flex justify-between items-center w-fit gap-2 mr-5 relative" x-data="{ open: false }">
        @if ($user->photo_url)
            <img src="{{ $user->photo_url }}" class="rounded-full h-11 w-11 aspect-square object-cover">
        @else
            <div
                class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-11 h-11 flex items-center justify-center text-lg">
                {{ $initials }}
            </div>
        @endif
        <button type="button" @click="open = !open" @click.away="open = false"
            class="flex items-center transition-all rounded-lg p-2 hover:bg-tertiary-table-line">
            <span class="font-semibold">{{ $user->name }}</span>
            <x-icons.arrow-down class="ml-2 text-tertiary-300" />
            <div class="absolute right-0 -bottom-48 shadow-drop p-4 bg-white rounded-xl" x-cloak x-show="open"
                x-transition:enter="transition-all ease duration-300" x-transition:enter-start="scale-0"
                x-transition:enter-end="scale-100" x-transition:leave="transition-all ease duration-300"
                x-transition:leave-start="scale-100" x-transition:leave-end="scale-0">
                <div class="flex gap-2">
                    @if ($user->photo_url)
                        <img src="{{ $user->photo_url }}" class="rounded-full h-11 w-11 aspect-square object-cover">
                    @else
                        <div
                            class="bg-tertiary-title-line text-tertiary-title font-semibold rounded-full w-11 h-11 flex items-center justify-center text-lg">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="flex flex-col gap-1 text-sm justify-between items-start">
                        <span class="font-semibold max-w-44 text-start">{{ $user->name }}</span>
                        <span
                            class="px-2 rounded-full capitalize border-2 w-fit text-xs {{ $class }}">{{ $user->role }}</span>
                    </div>
                </div>
                <hr class="w-full h-[1px] mt-4 mb-2 bg-tertiary-table-line rounded-full border-0">
                <a href="#"
                    class="flex items-center p-2 rounded-lg gap-2 text-sm transition-all hover:bg-tertiary-table-line">
                    <x-icons.settings />
                    <span class="font-semibold">Settings</span>
                </a>
                <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center mt-1 p-2 rounded-lg gap-2 text-sm transition-all hover:bg-tertiary-table-line">
                    <x-icons.logout class="text-danger" />
                    <span class="font-semibold text-danger">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </button>
    </div>
</header>

<nav id="sidebar"
    class="fixed z-10 top-0 pt-24 left-0 w-80 h-full bg-gray-50 transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full p-6 overflow-y-auto bg-gray-50">
        <ul class="space-y-3 font-medium" x-data="{ focus: 1 }">
            <x-navlink href="{{ route('admin.dashboard') }}" :active="request()->is('admin/dashboard')" :focusNo="1" @click="focus = 1">
                <x-icons.dashboard />
                <x-slot:title>Dashboard</x-slot:title>
            </x-navlink>
            <x-navlink href="{{ route('product.index') }}" :active="request()->is('admin/product/' . '*') || request()->is('admin/product')" :focusNo="2" @click="focus = 2">
                <x-icons.produk />
                <x-slot:title>Produk</x-slot:title>
            </x-navlink>
            <x-navlink-toggle :focusNo="3" :menus="[
                [
                    'name' => 'Riwayat',
                    'active' => request()->is('admin/order') || request()->is('admin/order' . '*'),
                    'icon' => view('components.icons.riwayat')->render(),
                    'route' => route('order.index'),
                ],
                [
                    'name' => 'Retur',
                    'active' => request()->is('retur'),
                    'icon' => view('components.icons.retur')->render(),
                    'route' => '',
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.pesanan />
                </x-slot:titleIcon>
                Pesanan
            </x-navlink-toggle>
            <x-navlink-toggle :focusNo="4" :menus="[
                [
                    'name' => 'Pemasukan',
                    'active' => request()->is('pemasukan'),
                    'icon' => view('components.icons.pemasukan')->render(),
                    'route' => '',
                ],
                [
                    'name' => 'Pengeluaran',
                    'active' => request()->is('admin/expense') || request()->is('admin/expense' . '*'),
                    'icon' => view('components.icons.pengeluaran')->render(),
                    'route' => route('expense.index'),
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.keuangan />
                </x-slot:titleIcon>
                Keuangan
            </x-navlink-toggle>
            <x-navlink-toggle :focusNo="5" :menus="[
                [
                    'name' => 'Kasir',
                    'active' => request()->is('admin/cashier') || request()->is('admin/cashier' . '*'),
                    'icon' => view('components.icons.kasir')->render(),
                    'route' => route('cashier.index'),
                ],
                [
                    'name' => 'Supplier',
                    'active' => request()->is('admin/supplier') || request()->is('admin/supplier' . '*'),
                    'icon' => view('components.icons.supplier')->render(),
                    'route' => route('supplier.index'),
                ],
                [
                    'name' => 'Customer',
                    'active' => request()->is('admin/customer') || request()->is('admin/customer' . '*'),
                    'icon' => view('components.icons.customer')->render(),
                    'route' => route('customer.index'),
                ],
                [
                    'name' => 'Kurir',
                    'active' => request()->is('admin/courier') || request()->is('admin/courier' . '*'),
                    'icon' => view('components.icons.kurir')->render(),
                    'route' => route('courier.index'),
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.pengguna />
                </x-slot:titleIcon>
                Pengguna
            </x-navlink-toggle>
            <x-navlink-toggle :focusNo="6" :menus="[
                [
                    'name' => 'Kelola',
                    'active' => request()->is('kelola'),
                    'icon' => view('components.icons.kelola')->render(),
                    'route' => '',
                ],
                [
                    'name' => 'Invoice',
                    'active' => request()->is('invoice'),
                    'icon' => view('components.icons.invoice')->render(),
                    'route' => '',
                ],
            ]">
                <x-slot:titleIcon>
                    <x-icons.po />
                </x-slot:titleIcon>
                Purchase Order
            </x-navlink-toggle>
        </ul>
    </div>
</nav>
