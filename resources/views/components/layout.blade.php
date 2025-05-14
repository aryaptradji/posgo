<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <title>{{ $title }}</title>
</head>

<body class="bg-tertiary">
    <div id="container" class="flex">
        <x-navbar/>

        <main class="px-8 pt-8 pb-4 ml-80 mt-24 flex-grow">
            <x-header>{{ $header }}</x-header>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
x
