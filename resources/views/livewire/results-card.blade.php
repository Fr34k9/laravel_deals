<div class="bg-white rounded-2xl p-5 cursor-pointer hover:-translate-y-2 transition-all relative">
    <a href="{{ $deal->url }}" target="_blank"
        class="bg-gray-100 w-10 h-10 flex items-center justify-center rounded-full cursor-pointer absolute top-4 right-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="18px" class="fill-gray-800 inline-block" viewBox="0 0 64 64">
            <path
                d="M45.5 4A18.53 18.53 0 0 0 32 9.86 18.5 18.5 0 0 0 0 22.5C0 40.92 29.71 59 31 59.71a2 2 0 0 0 2.06 0C34.29 59 64 40.92 64 22.5A18.52 18.52 0 0 0 45.5 4ZM32 55.64C26.83 52.34 4 36.92 4 22.5a14.5 14.5 0 0 1 26.36-8.33 2 2 0 0 0 3.27 0A14.5 14.5 0 0 1 60 22.5c0 14.41-22.83 29.83-28 33.14Z"
                data-original="#000000"></path>
        </svg>
    </a>
    <div
        class="bg-gray-100 flex items-center justify-center rounded-full absolute -top-3 px-2">
        {{ $deal->created_at->diffForHumans() }}
    </div>
    <div class="w-11/12 h-[310px] md:h-[230px] overflow-hidden mx-auto aspect-w-16 aspect-h-8 md:mb-2 mb-4">
        <img src="{{ $deal->image }}" alt="Product 1" class="h-full w-full object-contain" />
    </div>
    <div>
        <h3 class="text-lg font-extrabold text-gray-800">{{ htmlspecialchars_decode( $deal->title ) }}</h3>
        <p class="text-gray-600 text-sm mt-2">{{ $deal->subtitle }}</p>
        <h4 class="mt-4">
            <span class="text-lg text-gray-800 font-bold">{{ $deal->price }}.-</span>
            @if( !empty( $deal->else_price ) && $deal->else_price > $deal->price )
                <span class="text-sm text-gray-600 line-through ml-2">{{ $deal->else_price }}.-</span>
            @endif
        </h4>
        <div class="mt-4">
            <div class="relative w-full bg-gray-200 rounded-full">
                @if( $deal->products_total > 0)
                    <div class="h-2 bg-blue-500 rounded-full" style="width: {{ $deal->products_left / $deal->products_total * 100 }}%"></div>
                @endif
            </div>
        </div>
    </div>
</div>
