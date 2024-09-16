<div>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 max-xl:gap-3">
        @forelse ($deals as $deal)
            <livewire:results-card wire:key="{{ $deal->id }}" :deal="$deal" />
        @empty
            @if( !empty($this->platform) )
                <div class="text-lg text-gray-800 dark:text-white">No deals found for {{ $this->platform->name }}</div>
            @else
                <div class="text-gray-800 dark:text-white">
                    <div class="text-lg">No deals found.</div>
                </div>
            @endif
        @endforelse
    </div>
    
    @if( !empty($this->platform) && !empty($this->platform->last_crawled) )
        <div class="mt-3 text-sm text-center text-gray-500 dark:text-gray-400">
            Last refresh for {{ $this->platform->name }}: {{ $this->platform->last_crawled->diffForHumans() }}
        </div>
    @endif
</div>