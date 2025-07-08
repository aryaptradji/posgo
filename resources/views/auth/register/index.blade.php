<!DOCTYPE html>
<html lang="en">

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
    <title>Register</title>
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

    <div class="h-screen bg-tertiary px-8 py-8 flex">
        <section class="ps-24 w-3/5" x-data="{
            name: @js(old('name')),
            phone: @js(old('phone')),
            address: @js(old('address')),
            city: @js(old('city')),
            district: @js(old('district')),
            subDistrict: @js(old('sub_district')),
            rt: @js(old('rt')),
            rw: @js(old('rw')),
            postalCode: @js(old('postal_code')),
            email: @js(old('email')),
            password: @js(old('password')),
            nameError: '',
            phoneError: '',
            addressError: '',
            rtError: '',
            rwError: '',
            postalCodeError: '',
            emailError: '',
            passwordError: '',
            nameServerError: '{{ $errors->has('name') }}',
            phoneServerError: '{{ $errors->has('phone') }}',
            addressServerError: '{{ $errors->has('address') }}',
            rtServerError: '{{ $errors->has('rt') }}',
            rwServerError: '{{ $errors->has('rw') }}',
            postalCodeServerError: '{{ $errors->has('postal_code') }}',
            emailServerError: '{{ $errors->has('email') }}',
            passwordServerError: '{{ $errors->has('password') }}',
            validateName() {
                this.nameError = '';

                if (this.name == '') {
                    this.nameError = 'Nama wajib diisi';
                } else if (!/^[a-zA-Z]+[a-zA-Z.\s]*$/.test(this.name)) {
                    this.nameError = 'Nama hanya boleh mengandung huruf'
                }
                if (this.name !== '') {
                    this.nameServerError = '';
                }
            },
            validatePhone() {
                this.phoneError = '';

                if (this.phone == '') {
                    this.phoneError = 'Nomor telepon wajib diisi';
                } else if (!/^08/.test(this.phone)) {
                    this.phoneError = 'Nomor harus berformat 08xxxxxxxxxxx';
                } else if (!/^[0-9]+$/.test(this.phone)) {
                    this.phoneError = 'Nomor hanya boleh berisi angka';
                } else if (!/^[0-9]{10,15}$/.test(this.phone)) {
                    this.phoneError = 'Nomor harus berjumlah 10-15 digit';
                }
                if (this.phone !== '') {
                    this.phoneServerError = '';
                }
            },
            validateAddress() {
                this.addressError = '';

                if (this.address == '') {
                    this.addressError = 'Alamat wajib diisi';
                }
                if (this.address !== '') {
                    this.addressServerError = '';
                }
            },
            validateRT() {
                this.rtError = '';

                if (this.rt == '') {
                    this.rtError = 'Nomor RT wajib diisi';
                } else if (!/^[0-9A-Za-z]*$/.test(this.rt)) {
                    this.rtError = 'Nomor RT tidak boleh mengandung karakter khusus';
                } else if (/^[A-Za-z]$/.test(this.rt)) {
                    this.rtError = 'Nomor RT tidak boleh mengandung huruf';
                } else if (!/^[0-9]{3}$/.test(this.rt)) {
                    this.rtError = 'Nomor RT harus mengandung angka 3 digit';
                }
                if (this.rt) {
                    this.rtServerError = '';
                }
            },
            validateRW() {
                this.rwError = '';

                if (this.rw == '') {
                    this.rwError = 'Nomor RW wajib diisi';
                } else if (!/^[0-9A-Za-z]*$/.test(this.rw)) {
                    this.rwError = 'Nomor RW tidak boleh mengandung karakter khusus';
                } else if (/^[A-Za-z]$/.test(this.rw)) {
                    this.rwError = 'Nomor RW tidak boleh mengandung huruf';
                } else if (!/^[0-9]{3}$/.test(this.rw)) {
                    this.rwError = 'RW harus mengandung angka 3 digit';
                }
                if (this.rw) {
                    this.rwServerError = '';
                }
            },
            validatePostalCode() {
                this.postalCodeError = '';

                if (this.postalCode == '') {
                    this.postalCodeError = 'Kode pos wajib diisi';
                } else if (!/^[0-9A-Za-z]*$/.test(this.postalCode)) {
                    this.postalCodeError = 'Kode pos tidak boleh mengandung karakter khusus';
                } else if (/^[A-Za-z]$/.test(this.postalCode)) {
                    this.postalCodeError = 'Kode pos tidak boleh mengandung huruf';
                } else if (!/^[0-9]{5}$/.test(this.postalCode)) {
                    this.postalCodeError = 'Kode pos harus mengandung angka 5 digit';
                }
                if (this.postalCode !== '') {
                    this.postalCodeServerError = '';
                }
            },
            validateEmail() {
                this.emailError = '';

                if (this.email == false) {
                    this.emailError = 'Email wajib diisi';
                } else if (!/^[a-zA-Z0-9](\.?[a-zA-Z0-9_]+)*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.email)) {
                    this.emailError = 'Format email tidak valid';
                }
                if (this.email !== '') {
                    this.emailServerError = '';
                }
            },
            validatePassword() {
                this.passwordError = '';

                if (this.password == '') {
                    this.passwordError = 'Password wajib diisi';
                } else if (this.password.length <= 8) {
                    this.passwordError = 'Password minimal berjumlah 8 karakter';
                } else if (!/^(?=.*[A-Z]).*$/.test(this.password)) {
                    this.passwordError = 'Password setidaknya harus mengandung 1 huruf besar';
                } else if (!/^(?=.*[0-9]).*$/.test(this.password)) {
                    this.passwordError = 'Password setidaknya harus mengandung 1 digit angka';
                } else if (!/^(?=.*[!@#$%^&*._]).*$/.test(this.password)) {
                    this.passwordError = 'Password setidaknya harus mengandung 1 karakter khusus';
                }
                if (this.password !== '') {
                    this.passwordServerError = '';
                }
            }
        }">
            <img src="{{ asset('img/posgo-logo.svg') }}" alt="Logo" class="w-32 pb-3">
            <form action="{{ route('auth.register') }}" method="POST">
                @csrf
                <div id="form-carousel" class="relative overflow-y-auto overflow-x-hidden">
                    {{-- Data Diri: Sec 1 --}}
                    <div class="form-step transition-all duration-500 ease-in-out opacity-100 translate-x-0 p-3">
                        <p class="text-3xl font-bold pb-2">Daftar yuk</p>
                        <span class="font-medium">Sudah punya akun?</span>
                        <a href="{{ route('login') }}"
                            class="inline-block font-semibold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">
                            Masuk
                        </a>
                        <div class="pt-6 pb-4 flex gap-2">
                            <span
                                class="bg-gradient-to-t from-primary to-secondary-purple w-6 h-6 aspect-square rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">1</span>
                            <span class="font-bold">Data Diri</span>
                        </div>

                        {{-- Kota --}}
                        <x-dropdown-search :errorClass="$errors->has('city')
                            ? 'border-[3.5px] border-danger focus:border-danger'
                            : 'border-0'" name="city" :items="$cities->map(fn($c) => ['slug' => $c->slug, 'name' => $c->name])->toArray()" :value="$citySlug ?? 'Pilih Salah Satu'"
                            contClass="mb-6 w-4/5">
                            Kota
                        </x-dropdown-search>
                        @error('city')
                            <x-inline-error-message class="mb-3 -mt-4"
                                x-show="$errors->has('city')">{{ $message }}</x-inline-error-message>
                        @enderror
                        <input type="hidden" name="city" value="{{ request('city') }}">

                        {{-- Kecamatan --}}
                        <x-dropdown-search :errorClass="$errors->has('district')
                            ? 'border-[3.5px] border-danger focus:border-danger'
                            : 'border-0'" name="district" :items="$districts->map(fn($d) => ['slug' => $d->slug, 'name' => $d->name])->toArray()" :value="$districtSlug ?? 'Pilih Salah Satu'"
                            contClass="mb-6 w-4/5">
                            Kecamatan
                        </x-dropdown-search>
                        @error('district')
                            <x-inline-error-message class="mb-3 -mt-4"
                                x-show="$errors->has('district')">{{ $message }}</x-inline-error-message>
                        @enderror
                        <input type="hidden" name="district" value="{{ request('district') }}">

                        {{-- Kelurahan --}}
                        <x-dropdown-search :errorClass="$errors->has('sub_district')
                            ? 'border-[3.5px] border-danger focus:border-danger'
                            : 'border-0'" name="sub_district" :items="$subDistricts->map(fn($s) => ['slug' => $s->slug, 'name' => $s->name])->toArray()" :value="$subDistrictSlug ?? 'Pilih Salah Satu'"
                            contClass="mb-6 w-4/5">
                            Kelurahan
                        </x-dropdown-search>
                        @error('sub_district')
                            <x-inline-error-message class="mb-3 -mt-4"
                                x-show="$errors->has('sub_district')">{{ $message }}</x-inline-error-message>
                        @enderror
                        <input type="hidden" name="sub_district" value="{{ request('sub_district') }}">

                        <div class="w-4/5 mt-10 text-end">
                            <x-button-sm onclick="nextStep()" type="button"
                                class="text-white bg-gradient-to-r from-primary to-secondary-purple">Lanjut</x-button-sm>
                        </div>
                    </div>
                    {{-- Data Diri: Sec 2 --}}
                    <div
                        class="form-step w-full absolute top-0 transition-all duration-500 ease-in-out opacity-0 translate-x-full px-3">
                        <div class="flex gap-2 mb-2">
                            <span
                                class="bg-gradient-to-t from-primary to-secondary-purple w-6 h-6 aspect-square rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">1</span>
                            <span class="font-bold">Data Diri</span>
                        </div>

                        {{-- Alamat --}}
                        <x-textfield x-model="address" x-on:input="validateAddress()" type="text" name="address"
                            placeholder="Masukkan alamat dan nomor rumah . . ." class="focus:ring"
                            x-bind:class="addressError || addressServerError ? 'ring ring-danger focus:ring-danger' :
                                'focus:ring-primary'"
                            classCont="w-4/5 mb-6">Alamat</x-textfield>
                        <x-inline-error-message class="mb-3 -mt-4" x-show="addressError"
                            x-text="addressError"></x-inline-error-message>
                        @error('address')
                            <x-inline-error-message class="mb-3 -mt-4"
                                x-show="addressServerError">{{ $message }}</x-inline-error-message>
                        @enderror

                        <div class="grid grid-cols-3 gap-10 mb-6 w-4/5">
                            {{-- RT --}}
                            <div>
                                <x-textfield x-model="rt" x-on:input="validateRT()"
                                    class="focus:border-[3.5px]"
                                    x-bind:class="rtError || rtServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                        'focus:border-primary'"
                                    type="text" name="rt" placeholder="ex: 001">RT</x-textfield>
                                <x-inline-error-message class="mb-3 mt-2" x-show="rtError"
                                    x-text="rtError"></x-inline-error-message>
                                @error('rt')
                                    <x-inline-error-message class="mb-3 mt-2"
                                        x-show="rtServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>

                            {{-- RW --}}
                            <div>
                                <x-textfield x-model="rw" x-on:input="validateRW()"
                                    class="focus:border-[3.5px]"
                                    x-bind:class="rwError || rwServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                        'focus:border-primary'"
                                    type="text" name="rw" placeholder="ex: 002">RW</x-textfield>
                                <x-inline-error-message class="mb-3 mt-2" x-show="rwError"
                                    x-text="rwError"></x-inline-error-message>
                                @error('rw')
                                    <x-inline-error-message class="mb-3 mt-2"
                                        x-show="rwServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>

                            {{-- Kode Pos --}}
                            <div>
                                <x-textfield x-model="postalCode" x-on:input="validatePostalCode()"
                                    class="focus:border-[3.5px]"
                                    x-bind:class="postalCodeError || postalCodeServerError ?
                                        'border-[3.5px] border-danger focus:border-danger' : 'focus:border-primary'"
                                    type="text" name="postal_code" placeholder="ex: 12123">Kode Pos</x-textfield>
                                <x-inline-error-message class="mb-3 mt-2" x-show="postalCodeError"
                                    x-text="postalCodeError"></x-inline-error-message>
                                @error('postal_code')
                                    <x-inline-error-message class="mb-3 mt-2"
                                        x-show="postalCodeServerError">{{ $message }}</x-inline-error-message>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama --}}
                        <x-textfield x-model="name" x-on:input="validateName()" type="text" name="name"
                            placeholder="Masukkan nama lengkap . . ." class="focus:border-[3.5px] capitalize"
                            x-bind:class="nameError || nameServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                'focus:border-primary'"
                            classCont="w-4/5 mb-6">Nama</x-textfield>
                        <x-inline-error-message class="mb-3 -mt-4" x-show="nameError"
                            x-text="nameError"></x-inline-error-message>
                        @error('name')
                            <x-inline-error-message class="mb-3 -mt-4"
                                x-show="nameServerError">{{ $message }}</x-inline-error-message>
                        @enderror

                        {{-- No Telepon --}}
                        <x-textfield x-model="phone" x-on:input="validatePhone()" type="text" name="phone"
                            inputmode="numeric" pattern="[0-9]*" placeholder="08xxxxxxxxx" class="focus:border-[3.5px]"
                            x-bind:class="phoneError || phoneServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                'focus:border-primary'"
                            classCont="w-4/5 mb-6">Nomor
                            Telepon</x-textfield>
                        <x-inline-error-message class="mb-3 -mt-4" x-show="phoneError"
                            x-text="phoneError"></x-inline-error-message>
                        @error('phone')
                            <x-inline-error-message class="mb-3 -mt-4"
                                x-show="phoneServerError">{{ $message }}</x-inline-error-message>
                        @enderror

                        <div class="w-4/5 flex justify-between mt-10">
                            <x-button-sm onclick="prevStep()" type="button"
                                class="text-black bg-btn-cancel">kembali</x-button-sm>
                            <x-button-sm onclick="nextStep()" type="button"
                                class="text-white bg-gradient-to-r from-primary to-secondary-purple">lanjut</x-button-sm>
                        </div>
                    </div>

                    {{-- Akun --}}
                    <div
                        class="form-step w-full absolute top-0 transition-all duration-500 ease-in-out opacity-0 translate-x-full p-3">
                        <p class="text-3xl font-bold pb-2">Daftar yuk</p>
                        <span class="font-medium">Sudah punya akun?</span>
                        <a href="{{ route('login') }}"
                            class="inline-block font-semibold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">
                            Masuk
                        </a>
                        <div class="pt-7 flex gap-2">
                            <span
                                class="bg-gradient-to-t from-primary to-secondary-purple w-6 h-6 aspect-square rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">2</span>
                            <span class="font-bold">Akun</span>
                        </div>

                        {{-- Email --}}
                        <x-textfield x-model="email" x-on:input="validateEmail()" type="email" name="email"
                            placeholder="Masukkan email . . ." class="focus:border-[3.5px]"
                            x-bind:class="emailError || emailServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                'focus:border-primary'"
                            classCont="w-4/5 mt-4 mb-6">Email</x-textfield>
                        <x-inline-error-message class="mb-6 -mt-4" x-show="emailError"
                            x-text="emailError"></x-inline-error-message>
                        @error('email')
                            <x-inline-error-message class="mb-6 -mt-4"
                                x-show="emailServerError">{{ $message }}</x-inline-error-message>
                        @enderror

                        {{-- Password --}}
                        <x-textfield-password x-model="password" x-on:input="validatePassword()" name="password"
                            placeholder="Masukkan password . . ." class="focus:border-[3.5px]"
                            x-bind:class="passwordError || passwordServerError ? 'border-[3.5px] border-danger focus:border-danger' :
                                'focus:border-primary'"
                            classCont="w-4/5 mb-6">Password</x-textfield-password>
                        <x-inline-error-message class="mb-6 -mt-4" x-show="passwordError"
                            x-text="passwordError"></x-inline-error-message>
                        @error('password')
                            <x-inline-error-message class="mb-6 -mt-4"
                                x-show="passwordServerError">{{ $message }}</x-inline-error-message>
                        @enderror

                        <div class="w-4/5 mt-16 flex justify-between">
                            <x-button-sm onclick="prevStep()" type="button"
                                class="text-black bg-btn-cancel">Kembali</x-button-sm>
                            <x-button-sm class="bg-gradient-to-r from-primary to-secondary-purple text-white"
                                type="submit">Daftar</x-button-sm>
                        </div>
                    </div>
                </div>
            </form>
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
                            :class="active === i ? 'bg-gradient-to-tr from-primary to-secondary-purple w-8' : 'bg-black/10 w-3'"
                            class="h-3 rounded-full duration-500 transition-all"></button>
                    </template>
                </div>
            </div>
        </section>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        let currentStep = parseInt(urlParams.get('step')) || 0;

        const steps = document.querySelectorAll(".form-step");

        function showStep(index) {
            steps.forEach((step, i) => {
                if (i === index) {
                    step.classList.remove("opacity-0", "translate-x-full", "-translate-x-full");
                    step.classList.add("opacity-100", "translate-x-0");
                } else {
                    step.classList.remove("opacity-100", "translate-x-0");
                    step.classList.add("opacity-0", i < index ? "-translate-x-full" : "translate-x-full");
                }
            });
        }

        function nextStep() {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        }

        showStep(currentStep);

        const form = document.querySelector("form");
        form?.addEventListener("submit", () => {
            const url = new URL(window.location.href);
            url.searchParams.delete("step");
            history.replaceState(null, '', url.toString());
        });
    </script>
</body>

</html>
