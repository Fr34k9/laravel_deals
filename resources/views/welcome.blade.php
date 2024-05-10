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
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z"
                                clip-rule="evenodd" />
                        </svg>
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
