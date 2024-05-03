<div class="relative p-5 transition-all bg-white cursor-default dark:bg-gray-700 group rounded-2xl hover:-translate-y-2 hover:-rotate-1">
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
            <h3 class="text-lg font-extrabold text-gray-800 line-clamp-2 dark:text-white">{{ htmlspecialchars_decode($deal->title) }}</h3>
            <p class="mt-2 text-sm text-gray-600 line-clamp-3 dark:text-white">{{ $deal->subtitle }}</p>
        </div>

        <div class="mt-auto">
            <h4>
                <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $deal->price }}.-</span>
                @if (!empty($deal->else_price) && $deal->else_price > $deal->price)
                    <span class="ml-2 text-sm text-gray-600 line-through dark:text-white">{{ $deal->else_price }}.-</span>
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
</div>
