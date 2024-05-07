<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Deals</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    @vite('resources/css/app.css')
</head>

<body x-cloak class="font-[sans-serif] antialiased bg-gray-100 dark:bg-gray-800" x-data="{ darkMode: $persist(false) }"
    :class="{ 'dark': darkMode === true }">
    <div class="p-4 mx-auto lg:max-w-full sm:max-w-full">
        <div class="flex justify-between mb-3 ">
            <h2 class="text-4xl font-extrabold text-gray-800 dark:text-white">
                <a href="{{ route('home') }}" wire:navigate>Deals</a>
            </h2>
            <div class="flex items-center">
                @auth
                    <span class="flex items-center justify-center mr-2 text-sm">
                        <span class="mr-2 dark:text-white">Admin</span>
                        <img src="{{ asset('storage/icons/admin.svg') }}" alt="Admin" class="w-6 h-6 text-gray-800 dark:text-white" />
                    </span>
                @endauth
                <livewire:theme-toggle />
            </div>
        </div>
        <livewire:result-filters />
        <livewire:result-list />
        <livewire:footer />
    </div>
</body>

</html>
