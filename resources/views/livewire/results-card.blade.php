<div class="relative p-5 transition-all bg-white cursor-default group rounded-2xl hover:-translate-y-2">
    <a href="{{ $deal->url }}" target="_blank"
        class="absolute flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full cursor-pointer top-4 right-4">
        <img src="{{ $deal->platforms->image }}" alt="{{ $deal->platforms->name }}" class="w-6 h-6 p-1" />
    </a>
    <div class="absolute items-center justify-center hidden px-2 bg-gray-100 rounded-full group-hover:flex -top-3">
        {{ $deal->created_at->diffForHumans() }}
    </div>
    <div class="w-11/12 h-[310px] md:h-[230px] overflow-hidden mx-auto aspect-w-16 aspect-h-8 md:mb-2 mb-4">
        <img src="{{ $deal->image }}" alt="Product 1" class="object-contain w-full h-full" />
    </div>
    <div>
        <h3 class="text-lg font-extrabold text-gray-800">{{ htmlspecialchars_decode($deal->title) }}</h3>
        <p class="mt-2 text-sm text-gray-600">{{ $deal->subtitle }}</p>
        <h4 class="mt-4">
            <span class="text-lg font-bold text-gray-800">{{ $deal->price }}.-</span>
            @if (!empty($deal->else_price) && $deal->else_price > $deal->price)
                <span class="ml-2 text-sm text-gray-600 line-through">{{ $deal->else_price }}.-</span>
            @endif
        </h4>
        <div class="mt-4">
            <div class="relative w-full bg-gray-200 rounded-full">
                @if ($deal->products_total > 0)
                    <div class="h-2 bg-blue-500 rounded-full"
                        style="width: {{ ($deal->products_left / $deal->products_total) * 100 }}%"></div>
                @endif
            </div>
        </div>
    </div>
</div>
