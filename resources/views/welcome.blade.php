<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Deals</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    @vite('resources/css/app.css')
</head>

<body class="font-[sans-serif] antialiased bg-gray-100">
    <div class="p-4 mx-auto lg:max-w-full sm:max-w-full">
        <h2 class="mb-3 text-4xl font-extrabold text-gray-800">
            <a href="{{ route('home') }}" wire:navigate>Deals</a>
        </h2>
        <livewire:result-filters />
        <livewire:result-list />
        <livewire:footer />
    </div>
</body>

</html>
