<div
    class="relative p-5 transition-all bg-white cursor-default dark:bg-gray-700 group rounded-2xl hover:-translate-y-2 hover:-rotate-1 @if ($deal->invalid) blur-sm hover:filter-none @endif">
    <div class="absolute items-center justify-center hidden px-2 bg-gray-100 rounded-full group-hover:flex -top-3">
        {{ $deal->created_at->diffForHumans() }}
    </div>

    <a href="{{ $deal->url }}" target="_blank"
        class="absolute flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full cursor-pointer top-4 right-4">
        <img src="{{ $deal->platforms->image }}" alt="{{ $deal->platforms->name }}" class="w-6 h-6 p-1" />
    </a>

    <div class="flex flex-col h-full">
        <div class="w-11/12 h-[310px] md:h-[230px] overflow-hidden mx-auto aspect-w-16 aspect-h-8 md:mb-2 mb-4">
            <img src="{{ $deal->image }}" alt="{{ $deal->title }}" class="object-contain w-full h-full" />
        </div>

        <div class="mb-2">
            <h3 class="text-lg font-extrabold text-gray-800 line-clamp-2 dark:text-white">
                {{ htmlspecialchars_decode($deal->title) }}
            </h3>
            <p class="mt-2 text-sm text-gray-600 line-clamp-3 dark:text-white">{{ $deal->subtitle }}</p>
        </div>

        <div class="mt-auto">
            <h4>
                <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $deal->price }}.-</span>
                @if (!empty($deal->else_price) && $deal->else_price > $deal->price)
                    <span class="ml-2 text-sm text-gray-600 line-through dark:text-white">
                        {{ $deal->else_price }}.-
                    </span>
                @endif
            </h4>
            <div class="mt-2">
                <div class="relative w-full bg-gray-200 rounded-full">
                    @if ($deal->products_total > 0)
                        <div class="h-2 bg-blue-500 rounded-full"
                            style="width: {{ ($deal->products_left / $deal->products_total) * 100 }}%"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @can('update', $deal)
        <button wire:click="toggleDealVisibility"
            class="absolute items-center justify-center hidden px-2 bg-gray-100 rounded-full cursor-pointer -bottom-3 right-4 group-hover:flex">
            @if ($deal->invalid)
                <svg class="w-6 h-6 text-gray-800" fill="none" aria-hidden="true"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2"
                        d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            @else
                <svg class="w-6 h-6 text-gray-800" aria-hidden="true"
                    viewBox="0 0 24 24">
                    <path
                        d="m4 15.6 3.055-3.056A4.913 4.913 0 0 1 7 12.012a5.006 5.006 0 0 1 5-5c.178.009.356.027.532.054l1.744-1.744A8.973 8.973 0 0 0 12 5.012c-5.388 0-10 5.336-10 7A6.49 6.49 0 0 0 4 15.6Z" />
                    <path
                        d="m14.7 10.726 4.995-5.007A.998.998 0 0 0 18.99 4a1 1 0 0 0-.71.305l-4.995 5.007a2.98 2.98 0 0 0-.588-.21l-.035-.01a2.981 2.981 0 0 0-3.584 3.583c0 .012.008.022.01.033.05.204.12.402.211.59l-4.995 4.983a1 1 0 1 0 1.414 1.414l4.995-4.983c.189.091.386.162.59.211.011 0 .021.007.033.01a2.982 2.982 0 0 0 3.584-3.584c0-.012-.008-.023-.011-.035a3.05 3.05 0 0 0-.21-.588Z" />
                    <path
                        d="m19.821 8.605-2.857 2.857a4.952 4.952 0 0 1-5.514 5.514l-1.785 1.785c.767.166 1.55.25 2.335.251 6.453 0 10-5.258 10-7 0-1.166-1.637-2.874-2.179-3.407Z" />
                </svg>
            @endif
        </button>
    @endcan
</div>
