<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <title>Document</title>
</head>

<body>
    <div class="h-screen bg-tertiary px-8 py-8 flex">
        <section class="ps-16 bg-blue-100">
            <!-- <div class="bg-blue-100 ps-16"> -->
            <img src="{{ asset('img/posgo-logo.svg') }}" alt="Logo" class="w-32 pt-6 pb-16">
            <div class="ps-2">
                <p class="text-3xl font-bold pb-2">Masuk yuk</p>
                <span class="font-medium">Belum punya akun?</span>
                <a href="#"
                    class="inline-block font-semibold bg-gradient-to-r from-primary to-secondary-purple bg-clip-text text-transparent transition-all hover:scale-90 active:scale-50">
                    Daftar
                </a>

                <x-textfield type="email" name="email" id="email" placeholder="Masukkan email . . ."
                    :required="true" class="w-full mt-10">Email</x-textfield>
                <x-textfield type="password" name="password" id="password" placeholder="Masukkan password . . ."
                    :required="true" class="w-full mt-6">Password</x-textfield>

                <x-button-lg class="mt-16" type="submit">masuk</x-button-lg>
            </div>
        </section>
        <section class="bg-green-100">

        </section>
    </div>
</body>

</html>
