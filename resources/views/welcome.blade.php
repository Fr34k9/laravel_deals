<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Deals</title>

    @vite('resources/css/app.css')
</head>

<body class="font-[sans-serif] antialiased bg-gray-100">
    <div class="p-4 mx-auto lg:max-w-7xl sm:max-w-full">
        <h2 class="mb-3 text-4xl font-extrabold text-gray-800">
            <a href="{{ route('home') }}" wire:navigate>Deals</a>
        </h2>
        <livewire:result-filters />
        <livewire:result-list />
        <livewire:footer />
    </div>
</body>

</html>
