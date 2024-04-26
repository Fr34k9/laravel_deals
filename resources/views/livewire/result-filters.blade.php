<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex gap-4" aria-label="Tabs">
            <button href="#" wire:click="filterByPlatform(0)"
                class="inline-flex shrink-0 items-center gap-2 border-b-2 border-transparent px-1 pb-2 text-sm font-medium text-gray-500 hover:border-blue-500 hover:text-black focus:border-blue-500">
                <svg class="w-8 h-8 md:w-6 md:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="mx-2 hidden md:block">All</span>
            </button>

            @foreach ($platforms as $platform)
                <button wire:key="{{ $platform->id }}" type="button" wire:click="filterByPlatform({{ $platform->id }})"
                    class="inline-flex shrink-0 items-center gap-2 border-b-2 border-transparent px-1 pb-2 text-sm font-medium text-gray-500 hover:border-blue-500 hover:text-black focus:border-blue-500">
                    <img src="{{ $platform->image }}" alt="{{ $platform->name }}" class="h-8 w-8 md:w-6 md:h-6">

                    <span class="mx-2 hidden md:block">{{ $platform->name }}</span>
                </button>
            @endforeach
        </nav>
    </div>
</div>
