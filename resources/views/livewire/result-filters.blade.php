<div class="mb-2">
    <button type="button" wire:click="filterByPlatform(0)"
        class="text-gray-900 bg-bg-gray-100 hover:bg-white border border-gray-200 focus:ring-4 focus:outline-none focus:bg-white focus:ring-white-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
        <svg class="w-4 h-4 md:w-6 md:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
                d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                clip-rule="evenodd" />
        </svg>
        <span class="mx-2 hidden md:block">All</span>
    </button>
    @foreach ($platforms as $platform)
        <button wire:key="{{ $platform->id }}" type="button" wire:click="filterByPlatform({{ $platform->id }})"
            class="text-gray-900 bg-bg-gray-100 hover:bg-white border border-gray-200 focus:ring-4 focus:outline-none focus:bg-white focus:ring-white-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
            <img src="{{ $platform->image }}" alt="{{ $platform->name }}" class="h-4 w-4 md:w-6 md:h-6 ">
            <span class="mx-2 hidden md:block">{{ $platform->name }}</span>
        </button>
    @endforeach
</div>
