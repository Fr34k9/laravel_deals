<div>
    <div class="p-4 mx-auto lg:max-w-7xl sm:max-w-full">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-6">Deals</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 max-xl:gap-4 gap-6">
            @forelse ($deals as $deal)
                <livewire:results-card :deal="$deal" />
            @empty
                <div class="text-gray-800 text-lg">No deals found</div>
            @endforelse
        </div>
    </div>
</div>
