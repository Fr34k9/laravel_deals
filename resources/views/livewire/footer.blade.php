<div>
    <footer class="mt-3 bg-white rounded-lg dark:bg-gray-800">
        <div class="w-full max-w-screen-xl p-4 mx-auto md:flex md:items-center md:justify-between">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                Made with ♥ by
                <a href="https://csteiger.ch" class="hover:underline">csteiger.ch</a>
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <button x-on:click="$dispatch('openModal', {title: 'test1', body: 'test2'})" class="hover:underline">Über</button>
                </li>
            </ul>
        </div>
    </footer>
    <livewire:modal />
</div>
