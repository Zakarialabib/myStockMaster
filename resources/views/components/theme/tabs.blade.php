<div>
    <div class="border-b border-gray-400">
        <div class="flex">
            @foreach($tabs as $index => $tab)
                <div wire:click="$set('activeTab', {{ $index }})" class="cursor-pointer py-2 px-4 bg-gray-200 {{ $activeTab === $index ? 'border-l border-t border-r rounded-t text-blue-700 font-bold' : '' }}">
                    <h3>{{ $tab['title'] }}</h3>
                </div>
            @endforeach
        </div>
    </div>
    <div class="border-l border-r border-b border-gray-400 py-4">
        {{ $tabs[$activeTab]['content'] }}
    </div>
</div>
