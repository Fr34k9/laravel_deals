<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 max-xl:gap-3 gap-6">
    @forelse ($deals as $deal)
        <livewire:results-card wire:key="{{ $deal->id }}" :deal="$deal" />
    @empty
        <div class="text-gray-800 text-lg">No deals found</div>
    @endforelse
</div>
