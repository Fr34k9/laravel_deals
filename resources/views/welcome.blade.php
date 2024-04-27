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

        <footer class="mt-3 bg-white rounded-lg dark:bg-gray-800">
            <div class="w-full max-w-screen-xl p-4 mx-auto md:flex md:items-center md:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                    Made with ♥ by
                    <a href="https://csteiger.ch" class="hover:underline">csteiger.ch</a>
                </span>
                <ul
                    class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">About</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Licensing</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">Contact</a>
                    </li>
                </ul>
            </div>
        </footer>
    </div>
</body>

</html>
