<x-layout-main>
    <x-slot:title>POS Menu</x-slot:title>

    {{-- Toast Error --}}
    @if (session('error'))
        <div class="fixed top-16 right-10 z-50 flex flex-col items-end gap-4">
            <x-toast id="toast-failed" iconClass="text-danger bg-danger/25" slotClass="text-danger" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-failed />
                </x-slot:icon>
                {{ session('error') }}
            </x-toast>
        </div>
    @endif

    {{-- Toast Success --}}
    @if (session('success'))
        <div class="fixed top-16 right-10 z-20 flex flex-col justify-end gap-4">
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {!! session('success') !!}
            </x-toast>
        </div>
    @endif

    <div class="flex flex-col-2 px-14 pt-32 pb-8 gap-16 min-h-screen" x-data="{
        cart: {},
        cartOrder: [],
        products: {},
        addToCart(id) {
            const max = this.products[id]?.stock ?? 0;
            if (max < 1) return;

            this.cart[id] = { qty: 1 };
            if (!this.cartOrder.includes(id)) this.cartOrder.push(id);
        },
        increase(id) {
            const max = this.products[id]?.stock ?? 0;
            if (this.cart[id].qty < max) {
                this.cart[id].qty++;
            }
        },
        decrease(id) {
            if (this.cart[id].qty > 1) {
                this.cart[id].qty--
            } else {
                delete this.cart[id]
                this.cartOrder = this.cartOrder.filter(i => i !== id)
            }
        },
        getProduct(id) {
            return this.products[id]
        }
    }" x-init="products = JSON.parse($refs.productsData.textContent)">
        <script type="application/json" x-ref="productsData">
            {!! $products->keyBy('id')->toJson() !!}
        </script>

        <div class="w-full">
            <div class="flex justify-between mb-16">
                <span class="text-3xl font-bold">POS Menu</span>
                <form action="{{ route('customer.product') }}" method="GET"
                    class="w-3/12 ps-4 pe-2 py-2 flex flex-row text-sm outline-none ring-1 ring-tertiary-300 rounded-lg bg-gray-50">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari"
                        class="outline-none w-full pt-1 bg-transparent placeholder:text-tertiary-title">
                    <button type="submit"
                        class="transition-all hover:scale-125 active:scale-95 hover:text-primary text-tertiary-title disabled">
                        <x-icons.cari />
                    </button>
                    @if (request('search'))
                        <a href="{{ route('pos-menu', request()->except(['search', 'page'])) }}"
                            class="ml-1 text-tertiary-title hover:text-danger transition-all hover:scale-125 active:scale-95">
                            <x-icons.close />
                        </a>
                    @endif
                </form>
            </div>
            <div class="grid grid-cols-4 gap-10 justify-between">
                {{-- Produk --}}
                @foreach ($products as $product)
                    @if ($product->stock > 5)
                        <div class="flex flex-col justify-end w-52 shadow-outer p-4 rounded-2xl">
                        @else
                            <div class="flex flex-col justify-between w-52 shadow-outer p-4 rounded-2xl">
                    @endif
                    @if ($product->stock <= 5 && $product->stock > 0)
                        <div class="text-end text-sm text-primary font-semibold mb-2">Tersisa {{ $product->stock }}
                        </div>
                    @elseif ($product->stock == 0)
                        <div class="text-end text-sm text-danger font-semibold mb-2">Habis
                        </div>
                    @endif
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $product->image) }}" class="max-h-28">
                    </div>
                    <div class="relative flex flex-col gap-1 bg-white p-3 rounded-xl">
                        <div class="flex flex-col gap-1">
                            <span class="font-bold">{{ $product->name }}</span>
                            <span class="text-tertiary-title text-xs">{{ $product->pcs }} pcs</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-primary font-bold">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</span>
                            <button x-show="!cart[{{ $product->id }}]" type="button"
                                :disabled="{{ $product->stock == 0 }}"
                                class="absolute bottom-2.5 right-2.5 w-fit p-2 disabled:bg-btn-cancel bg-primary disabled:shadow-none disabled:cursor-not-allowed shadow-outer-sidebar-primary rounded-full transition-all disabled:hover:scale-100 disabled:active:scale-100 disabled:active:non hover:scale-110 active:scale-90"
                                @click="addToCart({{ $product->id }})">
                                <x-icons.cart class="text-white" />
                            </button>
                            <div x-show="cart[{{ $product->id }}]"
                                class="absolute bottom-3 right-2.5 py-1 px-1 rounded-lg flex justify-between gap-2 items-center text-sm border border-tertiary-300">
                                <button @click="decrease({{ $product->id }})">
                                    <x-icons.min class="hover:text-primary" />
                                </button>
                                <span class="text-xs" x-text="cart[{{ $product->id }}]?.qty"></span>
                                <button @click="increase({{ $product->id }})"
                                    :disabled="cart[{{ $product->id }}]?.qty >= products[{{ $product->id }}]?.stock"
                                    class="hover:text-primary disabled:hover:text-current disabled:opacity-30 disabled:cursor-not-allowed">
                                    <x-icons.plus-sm />
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between items-center mt-10">
            <span class="text-sm italic">
                Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari
                {{ $products->total() }}
                produk
            </span>

            <div class="flex items-center gap-px rounded-full overflow-hidden border border-gray-300 shadow-sm w-fit">
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

                @php
                    $currentPage = $products->currentPage();
                    $lastPage = $products->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                <!-- First page -->
                @if ($start > 1)
                    <a href="{{ $products->url(1) }}"
                        class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-2 text-gray-500">...</span>
                    @endif
                @endif

                <!-- Middle pages -->
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <span
                            class="px-3 py-2 font-semibold bg-tertiary shadow-inner-pag text-primary">{{ $i }}</span>
                    @else
                        <a href="{{ $products->url($i) }}"
                            class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $i }}</a>
                    @endif
                @endfor

                <!-- Last page -->
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="px-3 py-2 text-gray-500">...</span>
                    @endif
                    <a href="{{ $products->url($lastPage) }}"
                        class="px-3 py-2 text-gray-700 bg-tertiary hover:bg-gray-100">{{ $lastPage }}</a>
                @endif

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

    {{-- Keranjang --}}
    <div class="px-8 py-6 w-2/5 rounded-2xl shadow-outer sticky top-32 h-fit">
        <div class="text-2xl font-bold mb-10">Keranjang</div>
        <div x-show="Object.keys(cart).length === 0" class="text-center text-tertiary-title italic py-24">
            Keranjang masih kosong
        </div>
        <template x-for="id in cartOrder" :key="id">
            <div class="flex flex-col-2 gap-6 mb-6">
                <div
                    class="flex items-center justify-center bg-tertiary-500/30 h-20 p-3 aspect-square object-contain rounded-xl">
                    <img :src="'/storage/' + getProduct(id).image" class="max-h-16">
                </div>
                <div class="flex flex-col w-full justify-between">
                    <div>
                        <div class="font-bold" x-text="getProduct(id).name"></div>
                        <div class="text-tertiary-title text-xs" x-text="getProduct(id).pcs + ' pcs'"></div>
                    </div>
                    <div class="flex justify-between gap-16 min-w-fit">
                        <span class="font-semibold text-tertiary-500" x-text="cart[id].qty + 'x'"></span>
                        <span class="font-semibold text-primary"
                            x-text="'Rp ' + (cart[id].qty * getProduct(id).price).toLocaleString()">
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <hr class="w-full h-[2px] my-6 bg-tertiary-table-line rounded-full border-0">

        <div class="flex justify-between items-center mb-6">
            <span class="font-bold text-tertiary-500/80 text-lg">Total</span>
            <span class="font-bold text-primary text-lg"
                x-text="'Rp ' + cartOrder.reduce((sum, id) => sum + (cart[id].qty * getProduct(id).price), 0).toLocaleString()">
            </span>
        </div>

        <form method="POST" action="{{ route('pos-menu.checkout') }}" x-ref="form">
            @csrf
            <input type="hidden" name="cart" :value="JSON.stringify(cart)">
            <x-button-lg type="submit" class="bg-primary shadow-outer-sidebar-primary">Pesan</x-button-lg>
        </form>
    </div>
    </div>
</x-layout-main>
