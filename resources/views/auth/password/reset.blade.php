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
    <title>Reset Password</title>
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

    <div class="h-screen bg-tertiary p-8 flex">
        <section class="ps-24 w-3/5">
            <img src="{{ asset('img/posgo-logo.svg') }}" alt="Logo" class="w-32 pt-6 pb-16">
            <div class="ps-2" x-data="{
                newPassword: @js(old('new_password')),
                confirmPassword: @js(old('confirm_password')),
                newPasswordError: '',
                confirmPasswordError: '',
                newPasswordServerError: '{{ $errors->has('new_password') }}',
                confirmPasswordServerError: '{{ $errors->has('confirm_password') }}',
                validateNewPassword() {
                    this.newPasswordError = '';

                    if (this.newPassword == '') {
                        this.newPasswordError = 'Password wajib diisi';
                    } else if (this.newPassword.length <= 8) {
                        this.newPasswordError = 'Password minimal berjumlah 8 karakter';
                    } else if (!/^(?=.*[A-Z]).*$/.test(this.newPassword)) {
                        this.newPasswordError = 'Password setidaknya harus mengandung 1 huruf besar';
                    } else if (!/^(?=.*[0-9]).*$/.test(this.newPassword)) {
                        this.newPasswordError = 'Password setidaknya harus mengandung 1 digit angka';
                    } else if (!/^(?=.*[!@#$%^&*._]).*$/.test(this.newPassword)) {
                        this.newPasswordError = 'Password setidaknya harus mengandung 1 karakter khusus';
                    }
                    if (this.newPassword !== '') {
                        this.newPasswordServerError = '';
                    }
                },
                validateConfirmPassword() {
                    this.confirmPasswordError = '';

                    if (this.confirmPassword == '') {
                        this.confirmPasswordError = 'Konfirmasi password wajib diisi';
                    } else if (this.confirmPassword !== this.newPassword) {
                        this.confirmPasswordError = 'Isi password harus sama';
                    }
                    if (this.confirmPassword !== '') {
                        this.confirmPasswordServerError = '';
                    }
                }
            }">
                <p class="text-3xl font-bold pb-2">Ubah Password</p>
                <span class="font-medium">Sudah ingat password?</span>
                <a href="{{ route('login') }}"
                    class="inline-block font-semibold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">
                    Login
                </a>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- New Password --}}
                    <x-textfield-password x-model="newPassword" x-on:input="validateNewPassword()" name="new_password"
                        id="new_password" placeholder="Masukkan password baru . . ." classCont="w-4/5 mt-10 mb-2"
                        class="focus:ring"
                        x-bind:class="newPasswordError || newPasswordServerError ? 'ring ring-danger focus:ring-danger' :
                            'focus:ring-primary'">Password
                        Baru</x-textfield-password>
                    <x-inline-error-message x-show="newPasswordError"
                        x-text="newPasswordError"></x-inline-error-message>
                    @error('new_password')
                        <x-inline-error-message x-show="newPasswordServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    {{-- Confirm Password --}}
                    <x-textfield-password x-model="confirmPassword" x-on:input="validateConfirmPassword()"
                        name="confirm_password" id="confirm_password" placeholder="Konfirmasi password baru . . ."
                        classCont="w-4/5 mt-10 mb-2" class="focus:ring"
                        x-bind:class="confirmPasswordError || confirmPasswordServerError ? 'ring ring-danger focus:ring-danger' :
                            'focus:ring-primary'">Konfirmasi
                        Password Baru</x-textfield-password>
                    <x-inline-error-message x-show="confirmPasswordError"
                        x-text="confirmPasswordError"></x-inline-error-message>
                    @error('confirm_password')
                        <x-inline-error-message
                            x-show="confirmPasswordServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">

                    <x-button-lg class="bg-gradient-to-r from-primary to-secondary-purple" contClass="w-4/5 mt-20"
                        type="submit">Simpan</x-button-lg>
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
