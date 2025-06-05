<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>[x-cloak] { display: none !important; }</style>
    <title>{{ $title }}</title>
</head>

<body class="bg-tertiary h-screen">
    <div id="container" class="flex h-full">
        <x-navbar-main/>

        <main {{ $attributes->merge(['class' => 'pb-8 w-full h-full']) }}>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
