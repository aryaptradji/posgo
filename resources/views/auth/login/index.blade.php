<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <title>Login</title>
</head>

<body class="bg-tertiary">
    {{-- Toast Error --}}
    @if ($errors->any())
        <div class="fixed top-16 right-10 z-50 flex flex-col items-end gap-4">
            @foreach ($errors->all() as $error)
                <x-toast id="toast-failed{{ $loop->index }}" iconClass="text-danger bg-danger/25"
                    slotClass="text-danger" :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-failed />
                    </x-slot:icon>
                    {{ $error }}
                </x-toast>
            @endforeach
        </div>
    @endif
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

    {{-- Toast Logout Success --}}
    @if (session('success'))
        <div class="fixed top-16 right-10 z-50 flex flex-col justify-end gap-4">
            @foreach ((array) session('success') as $msg)
                <x-toast id="toast-success{{ $loop->index }}" iconClass="text-success bg-success/25"
                    slotClass="text-success" :duration="6000" :delay="$loop->index * 500">
                    <x-slot:icon>
                        <x-icons.toast-success />
                    </x-slot:icon>
                    {!! nl2br(e($msg)) !!}
                </x-toast>
            @endforeach
        </div>
    @endif

    <div class="h-screen bg-tertiary p-8 flex">
        <section class="ps-24 w-3/5">
            <img src="{{ asset('img/posgo-logo.svg') }}" alt="Logo" class="w-32 pt-6 pb-16">
            <div class="ps-2" x-data="{
                email: @js(old('email')),
                password: @js(old('password')),
                emailError: '',
                passwordError: '',
                emailServerError: '{{ $errors->has('email') }}',
                passwordServerError: '{{ $errors->has('password') }}',
                validateEmail() {
                    this.emailError = '';

                    if (this.email == false) {
                        this.emailError = 'Email wajib diisi';
                    } else if (!/^[a-zA-Z0-9](\.?[a-zA-Z0-9_]+)*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.email)) {
                        this.emailError = 'Format email tidak valid';
                    }
                    if (this.email !== '') {
                        this.emailServerError = false;
                    }
                },
                validatePassword() {
                    this.passwordError = '';

                    if (this.password == false) {
                        this.passwordError = 'Password wajib diisi';
                    }
                    if (this.password !== '') {
                        this.passwordServerError = false;
                    }
                }
            }">
                <p class="text-3xl font-bold pb-2">Masuk yuk</p>
                <span class="font-medium">Belum punya akun?</span>
                <a href="{{ route('register') }}"
                    class="inline-block font-semibold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">
                    Daftar
                </a>

                <form action="{{ route('auth.login') }}" method="POST">
                    @csrf
                    {{-- Email --}}
                    <x-textfield x-model="email" x-on:input="validateEmail()" type="email" name="email"
                        id="email" placeholder="Masukkan email . . ." classCont="w-4/5 mt-10 mb-2"
                        class="focus:ring"
                        x-bind:class="emailError || emailServerError ? 'ring ring-danger focus:ring-danger' : 'focus:ring-primary'">Email</x-textfield>
                    <x-inline-error-message x-show="emailError" x-text="emailError"></x-inline-error-message>
                    @error('email')
                        <x-inline-error-message x-show="emailServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    {{-- Password --}}
                    <x-textfield-password x-model="password" x-on:input="validatePassword()" name="password"
                        id="password" placeholder="Masukkan password . . ." classCont="w-4/5 mt-6 mb-2"
                        class="focus:ring"
                        x-bind:class="passwordError || passwordServerError ? 'ring ring-danger focus:ring-danger' :
                            'focus:ring-primary'">Password</x-textfield-password>

                    <x-inline-error-message x-show="passwordError" x-text="passwordError"></x-inline-error-message>
                    @error('password')
                        <x-inline-error-message x-show="passwordServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    <a href="{{ route('password.email') }}"
                        class="mt-4 w-4/5 flex justify-end font-semibold">
                        <span class="bg-gradient-to-bl from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">Lupa Password?</span>
                    </a>

                    <x-button-lg class="bg-gradient-to-r from-primary to-secondary-purple" contClass="w-4/5 mt-16"
                        type="submit">Masuk</x-button-lg>
                </form>
            </div>
        </section>

        {{-- Carousel --}}
        <section class="relative w-2/5 rounded-2xl overflow-hidden shadow-outer px-8 py-8">
            <div class="relative w-full h-full overflow-hidden" x-data="{
                active: 0,
                slides: [{
                        title: 'Jasa Pengiriman',
                        titleColor: 'Cepat',
                        image: '{{ asset('img/Jasa Pengiriman Cepat.svg') }}'
                    },
                    {
                        title: 'Harga Produk',
                        titleColor: 'Bersaing',
                        image: '{{ asset('img/Harga Produk Bersaing.svg') }}'
                    },
                    {
                        title: 'Pesanan Diproses',
                        titleColor: 'Langsung',
                        image: '{{ asset('img/Pesanan Diproses Langsung.svg') }}'
                    }
                ],
                next() { this.active = (this.active + 1) % this.slides.length },
                prev() { this.active = (this.active - 1 + this.slides.length) % this.slides.length },
                autoplay() { setInterval(() => this.next(), 3000) }
            }" x-init="autoplay()">

                {{-- Contents --}}
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-cloak x-show="active === index"
                        class="absolute top-0 left-0 right-0 flex flex-col items-center justify-center text-center"
                        x-transition:enter="transition-all duration-500"
                        x-transition:enter-start="opacity-0 -translate-x-10"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition-all duration-500"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-10">

                        <img :src="slide.image" class="max-w-[450px] mb-10">
                        <div>
                            <span class="text-black text-2xl font-bold inline-block" x-text="slide.title"></span>
                            <span
                                class="text-2xl font-bold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent"
                                x-text="' ' + slide.titleColor"> </span>
                        </div>
                    </div>
                </template>

                {{-- Indicators --}}
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-2">
                    <template x-for="(slide, i) in slides" :key="i">
                        <button @click="active = i"
                            :class="active === i ? 'bg-gradient-to-bl from-primary to-secondary-purple w-8' : 'bg-black/10 w-3'"
                            class="h-3 rounded-full duration-500 transition-all"></button>
                    </template>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
