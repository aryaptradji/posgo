<x-layout-main>
    <x-slot:title>Pesananku</x-slot:title>

    <div class="w-full px-14 pt-32">
        <div class="flex justify-between mb-16">
            <span class="text-3xl font-bold">Pesananku</span>
            <form action="{{ route('customer.order.index') }}" method="GET"
                class="w-3/12 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                    class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                <button type="submit"
                    class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                    <x-icons.cari />
                </button>
                @if (request('search'))
                    <a href="{{ route('customer.order.index', request()->except(['search', 'page'])) }}"
                        class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                        <x-icons.close />
                    </a>
                @endif
            </form>
        </div>
        <div class="flex gap-4 bg-red-300">
            <span>Belum Bayar</span>
            <span>Dikemas</span>
            <span>Dikirim</span>
            <span>Selesai</span>
            <span>Retur</span>
        </div>
    </div>
</x-layout-main>
