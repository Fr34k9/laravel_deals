<div>
    <button type="button"
        wire:click="filterByPlatform(0)"
        class="text-gray-900 bg-bg-gray-100 hover:bg-white border border-gray-200 focus:ring-4 focus:outline-none focus:bg-white focus:ring-white-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
        All
    </button>
    @foreach ($platforms as $platform)
        <button wire:key="{{ $platform->id }}" type="button"
            wire:click="filterByPlatform({{ $platform->id }})"
            class="text-gray-900 bg-bg-gray-100 hover:bg-white border border-gray-200 focus:ring-4 focus:outline-none focus:bg-white focus:ring-white-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
            {{ $platform->name }}
        </button>
    @endforeach
</div>
