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

<body x-cloak class="font-[sans-serif] antialiased bg-gray-100 dark:bg-gray-800" x-data="{darkMode: $persist(false)}" :class="{'dark': darkMode === true }">
    <div class="p-4 mx-auto lg:max-w-full sm:max-w-full">
        <div class="flex justify-between mb-3 ">
            <h2 class="text-4xl font-extrabold text-gray-800 dark:text-white">
                <a href="{{ route('home') }}" wire:navigate>Deals</a>
            </h2>
            <livewire:theme-toggle />
        </div>
        <livewire:result-filters />
        <livewire:result-list />
        <livewire:footer />
    </div>
</body>

</html>
