<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
            <x-toast id="toast-success" iconClass="text-success bg-success/25" slotClass="text-success"
                :duration="6000">
                <x-slot:icon>
                    <x-icons.toast-success />
                </x-slot:icon>
                {{ session('success') }}
            </x-toast>
        </div>
    @endif

    <div class="h-screen bg-tertiary px-8 py-8 flex">
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
                    <x-textfield x-model="email" x-on:input="validateEmail()" type="email" name="email" id="email"
                        placeholder="Masukkan email . . ." classCont="w-4/5 mt-10 mb-2" class="focus:ring"
                        x-bind:class="emailError || emailServerError ? 'ring ring-danger focus:ring-danger' : 'focus:ring-primary'">Email</x-textfield>
                    <x-inline-error-message x-show="emailError" x-text="emailError"></x-inline-error-message>
                    @error('email')
                        <x-inline-error-message x-show="emailServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    {{-- Password --}}
                    <x-textfield-password x-model="password" x-on:input="validatePassword()" name="password" id="password"
                        placeholder="Masukkan password . . ." classCont="w-4/5 mt-6 mb-2" class="focus:ring"
                        x-bind:class="passwordError || passwordServerError ? 'ring ring-danger focus:ring-danger' :
                            'focus:ring-primary'">Password</x-textfield-password>

                    <x-inline-error-message x-show="passwordError" x-text="passwordError"></x-inline-error-message>
                    @error('password')
                        <x-inline-error-message x-show="passwordServerError">{{ $message }}</x-inline-error-message>
                    @enderror

                    <x-button-lg class="bg-gradient-to-r from-primary to-secondary-purple" contClass="w-4/5 mt-16"
                        type="submit">Masuk</x-button-lg>
                </form>
            </div>
        </section>
        <section id="default-carousel" class="relative w-2/5" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative overflow-hidden rounded-2xl h-full">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out bg-gradient-to-r from-primary/80 to-secondary-purple/80" data-carousel-item>
                    <img src="{{ asset('img/Koordinasi tim lebih mudah.svg') }}"
                        class="absolute block w-4/6 -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2"
                        alt="Koordinasi tim lebih mudah.svg">
                    <p
                        class="absolute w-full text-center top-3/4 left-1/2 transform -translate-x-1/2 text-white text-2xl font-bold">
                        Koordinasi tim lebih mudah
                    </p>
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out bg-gradient-to-tr from-primary/80 to-secondary-purple/80" data-carousel-item>
                    <img src="{{ asset('img/Waktu menjadi lebih efisien.svg') }}"
                        class="absolute block w-3/5 -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2"
                        alt="Waktu menjadi lebih efisien.svg">
                    <p
                        class="absolute w-full text-center top-3/4 left-1/2 transform -translate-x-1/2 text-white text-2xl font-bold">
                        Waktu menjadi lebih efisien
                    </p>
                </div>
                <!-- Item 3 -->
                <div class="hidden duration-700 ease-in-out bg-gradient-to-bl from-primary/80 to-secondary-purple/80" data-carousel-item>
                    <img src="{{ asset('img/Pengelolaan keuangan lebih baik.svg') }}"
                        class="absolute block w-4/6 -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2"
                        alt="Pengelolaan keuangan lebih baik.svg">
                    <p
                        class="absolute w-full text-center top-3/4 left-1/2 transform -translate-x-1/2 text-white text-2xl font-bold">
                        Pengelolaan keuangan lebih baik
                    </p>
                </div>
            </div>
            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 bottom-16 left-1/2 space-x-3 rtl:space-x-reverse">
                <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1"
                    data-carousel-slide-to="0"></button>
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2"
                    data-carousel-slide-to="1"></button>
                <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3"
                    data-carousel-slide-to="2"></button>
            </div>
            <!-- Slider controls -->
            <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 1 1 5l4 4" />
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
        </section>
    </div>
</body>

</html>
