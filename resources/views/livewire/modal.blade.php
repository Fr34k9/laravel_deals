<div class="text-white" x-data="{ isOpen: @entangle('isOpen') }" x-show="isOpen">
    <div
        class="translate-x-0 fixed left-0 top-0 inset-x-0 transition-all duration-300 transform max-w-sm size-full z-[80] bg-gray-500 border-b dark:bg-neutral-800 dark:border-neutral-700"
        tabindex="-1">
        <div class="flex items-center justify-between px-4 py-3 border-b dark:border-neutral-700">
            <h3 class="font-bold dark:text-white">
                Current platforms
            </h3>
            <button type="button" @click="isOpen = false"
                class="flex items-center justify-center text-sm font-semibold text-gray-800 border border-transparent rounded-full size-7 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700">
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div class="flex flex-col text-white dark:text-neutral-400">
                <ul class="">
                    @foreach ($platforms as $platform)
                        <div class="flex flex-col mb-3">
                            <div>
                                <a href="{{ $platform->url }}" class="hover:underline">
                                    <img class="inline w-4 h-4 m-1" src="{{ $platform->image }}" />
                                    {{ $platform->name }}
                                </a>
                            </div>
                            <div>
                                @if($platform->last_crawled)
                                    {{ __('Last crawled') }}: {{ $platform->last_crawled->diffForHumans() }}
                                @else
                                    {{ __('Not crawled yet') }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
