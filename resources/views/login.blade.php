<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <title>Login</title>
</head>

<body>
    <div class="h-screen bg-tertiary px-8 py-8 flex">
        <section class="ps-24 w-3/5">
            <!-- <div class="bg-blue-100 ps-16"> -->
            <img src="{{ asset('img/posgo-logo.svg') }}" alt="Logo" class="w-32 pt-6 pb-16">
            <div class="ps-2" x-data="{
                email: '',
                emailError: '',
                validateEmail() {
                    this.emailError = ''; // Reset error message

                    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                    if (!emailPattern.test(this.email)) {
                        this.emailError = 'Format email tidak valid';
                    }
                }
            }">
                <p class="text-3xl font-bold pb-2">Masuk yuk</p>
                <span class="font-medium">Belum punya akun?</span>
                <a href="/register"
                    class="inline-block font-semibold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">
                    Daftar
                </a>

                <form action="" method="POST">
                    @csrf
                    <x-textfield type="email" name="email" id="email" placeholder="Masukkan email . . ."
                        :required="true" classCont="w-4/5 mt-10 mb-2" x-model="email" x-on:input="validateEmail()" x-bind:class="emailError ? 'focus:ring focus:ring-danger' : 'focus:ring focus:ring-primary'">Email</x-textfield>
                    <span x-show="emailError" x-if="emailError" x-transition:enter="transition-all ease duration-1000" x-transition:enter-start="opacity-0 -translate-y-6"
                        x-transition:enter-end="opacity-100 translate-y-none" x-transition:leave="transition-all ease duration-1000"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-6"
                        class="text-danger text-sm font-semibold ml-2" x-text="emailError">
                    </span>
                    <x-textfield type="password" name="password" id="password" placeholder="Masukkan password . . ."
                        :required="true" classCont="w-4/5 mt-6">Password</x-textfield>

                    <x-button-lg class="w-4/5 mt-16" type="submit">masuk</x-button-lg>
                </form>
            </div>
        </section>
        <section id="default-carousel" class="relative w-2/5" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative overflow-hidden rounded-2xl h-full">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out bg-primary" data-carousel-item>
                    <img src="{{ asset('img/Koordinasi tim lebih mudah.svg') }}"
                        class="absolute block w-4/6 -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2"
                        alt="Koordinasi tim lebih mudah.svg">
                    <p
                        class="absolute w-full text-center top-3/4 left-1/2 transform -translate-x-1/2 text-white text-2xl font-bold">
                        Koordinasi tim lebih mudah
                    </p>
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out bg-secondary-purple" data-carousel-item>
                    <img src="{{ asset('img/Waktu menjadi lebih efisien.svg') }}"
                        class="absolute block w-3/5 -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2"
                        alt="Waktu menjadi lebih efisien.svg">
                    <p
                        class="absolute w-full text-center top-3/4 left-1/2 transform -translate-x-1/2 text-white text-2xl font-bold">
                        Waktu menjadi lebih efisien
                    </p>
                </div>
                <!-- Item 3 -->
                <div class="hidden duration-700 ease-in-out bg-primary" data-carousel-item>
                    <img src="{{ asset('img/Pengelolaan keuangan lebih baik.svg') }}"
                        class="absolute block w-4/6 -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2" alt="Pengelolaan keuangan lebih baik.svg">
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
