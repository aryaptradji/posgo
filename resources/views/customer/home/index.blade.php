<x-layout-main>
    <x-slot:title>Home</x-slot:title>

    {{-- Toast Create Success --}}
    @if (session('success'))
        <div class="fixed top-16 right-10 z-20 flex flex-col gap-4">
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success" :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {{ session('success') }}
            </x-toast>
        </div>
    @endif

    {{-- Carousel --}}
    <div class="flex px-14 justify-end relative w-full" x-data="{
        active: 0,
        slides: [{
                title: 'Jasa Pengiriman',
                titleColor: 'Cepat',
                subTitle: 'Kami berusaha menyediakan jasa pengiriman yang cepat dan tepat waktu',
                image: '{{ asset('img/Jasa Pengiriman Cepat.svg') }}'
            },
            {
                title: 'Harga Produk',
                titleColor: 'Bersaing',
                subTitle: 'Kami berusaha menyediakan harga produk yang bersaing dengan toko lain',
                image: '{{ asset('img/Harga Produk Bersaing.svg') }}'
            },
            {
                title: 'Pesanan Diproses',
                titleColor: 'Langsung',
                subTitle: 'Kami berusaha memproses pesanan langsung tanpa ada keterlambatan',
                image: '{{ asset('img/Pesanan Diproses Langsung.svg') }}'
            }
        ],
        next() {
            this.active = (this.active + 1) % this.slides.length
        },
        prev() {
            this.active = (this.active - 1 + this.slides.length) % this.slides.length
        },
        autoplay() {
            setInterval(() => this.next(), 3000)
        }
    }" x-init="autoplay()">
        <div class="relative w-full min-h-[calc(100vh-2rem)] overflow-hidden">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-cloak x-show="active === index" class="absolute inset-0 flex justify-between items-center px-14"
                    x-transition:enter="transition-all duration-500"
                    x-transition:enter-start="opacity-0 -translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all duration-500"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-10">
                    <div class="flex flex-col">
                        <p class="text-start font-bold text-5xl leading-normal">
                            <span class="block" x-text="slide.title"></span>
                            <span
                                class="bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent"
                                x-text="slide.titleColor"></span>
                        </p>
                        <p class="font-medium w-96 mt-4" x-text="slide.subTitle"></p>
                        <x-button-sm type="button"
                            class="w-fit px-8 py-3 mt-8 text-white bg-gradient-to-r from-primary to-secondary-purple">
                            <a href="{{ route('customer.product') }}">Pesan</a>
                        </x-button-sm>
                    </div>
                    <img :src="slide.image" class="relative right-16 -top-8" width="700" />
                </div>
            </template>
        </div>

        <!-- Controls -->
        <div class="flex flex-col w-fit justify-center gap-2">
            <template x-for="(slide, i) in slides" :key="i">
                <button @click="active = i" :class="active === i ? 'bg-gradient-to-bl from-primary to-secondary-purple h-7' : 'bg-black/10 h-3'"
                    class="w-3 rounded-full duration-500 transition-all mr-6"></button>
            </template>
        </div>
    </div>

</x-layout-main>
