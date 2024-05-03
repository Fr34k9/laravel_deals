<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex gap-4 -mb-px" aria-label="Tabs">
            <button href="#" wire:click="filterByPlatform(0)"
                class="inline-flex items-center gap-2 px-1 pb-2 text-sm font-medium text-gray-500 border-b-2 border-transparent shrink-0 hover:border-blue-500 hover:text-black focus:border-blue-500">
                <svg class="w-8 h-8 text-gray-800 md:w-6 md:h-6 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="hidden mx-2 md:block">All</span>
            </button>

            @foreach ($platforms as $platform)
                <button wire:key="{{ $platform->id }}" type="button" wire:click="filterByPlatform({{ $platform->id }})"
                    class="inline-flex items-center gap-2 px-1 pb-2 text-sm font-medium text-gray-500 border-b-2 border-transparent shrink-0 hover:border-blue-500 hover:text-black focus:border-blue-500 dark:hover:border-white dark:hover:text-white">
                    <img src="{{ $platform->image }}" alt="{{ $platform->name }}" class="w-8 h-8 md:w-6 md:h-6">

                    <span class="hidden mx-2 md:block">{{ $platform->name }}</span>
                </button>
            @endforeach
        </nav>
    </div>
</div>
