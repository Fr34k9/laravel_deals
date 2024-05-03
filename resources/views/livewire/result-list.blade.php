<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 max-xl:gap-3">
    @forelse ($deals as $deal)
        <livewire:results-card wire:key="{{ $deal->id }}" :deal="$deal" />
    @empty
        @if( !empty($this->filter_by_platform) )
            <div class="text-lg text-gray-800">No deals found for {{ $this->filter_by_platform_name }}</div>
        @else
            <div>
                <div class="text-lg text-gray-800">No deals found.</div>
                <div class="text-sm text-gray-800">This might be an error, please check the platforms by yourself.</div>
            </div>
        @endif
    @endforelse
</div>
