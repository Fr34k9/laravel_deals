<div>
    <h3 class="text-lg font-semibold">Manage Platforms</h3>
    <ul>
        @foreach ($platforms as $platform)
            @php
                $active = $platform->active;
            @endphp
            <li class="flex items-center justify-between py-2">
                <span>{{ $platform->name }}</span>
                <button wire:click="togglePlatform({{ $platform->id }})" class="px-4 py-2 {{ $active ? 'bg-green-500' : 'bg-red-500' }}">
                    {{ $active ? 'Deactivate' : 'Activate' }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
