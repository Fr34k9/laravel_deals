<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 max-xl:gap-3">
    @forelse ($deals as $deal)
        <livewire:results-card wire:key="{{ $deal->id }}" :deal="$deal" />
    @empty
        <div class="text-lg text-gray-800">No deals found</div>
    @endforelse
</div>
