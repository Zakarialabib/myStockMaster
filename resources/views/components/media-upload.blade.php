<label class="block mt-4 text-sm">
    <div
        class="w-full p-2 bg-zinc-100 border border-zinc-300 border-dashed rounded"
        x-data="{ isUploading: false, progress: 0 }"
                x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

        <div x-show="!isUploading">

            {{-- Form File picker --}}
            <input type="file" class="hidden" accept="{{ $rules ?? '' }}" {{ ($multiple ?? false) ? 'multiple':'' }}
                @if ( $defer ?? true )
                    wire:model.defer='{{ $name ?? '' }}'
                @else
                    wire:model='{{ $name ?? '' }}'
                @endif
            />

            @if ( !empty($image) )

                <div class="flex items-center space-x-4">
                    @if ( $image ?? true )
                        <img src="{{ $image->temporaryUrl() ?? '' }}" class="w-20 h-20">
                    @endif
                    <div class="font-light text-zinc-500">
                        <p>Type: {{ Str::upper($photoInfo["extension"]) }}</p>
                        <p>Size: {{ $photoInfo["size"] }} MB</p>
                        <button wire:click="$set('{{ $name }}')" class="px-2 mt-2 text-xs text-red-400 border border-red-400 rounded">
                            {{__('Remove')}}
                        </button>
                    </div>
                </div>

            @elseif ( !empty($preview) )

                <div class="flex items-center space-x-4">
                    <img src="{{ $preview }}" class="w-20 h-20">
                    <div class="font-light text-zinc-500">
                        <div class="px-2 mt-2 text-xs border rounded text-primary-400 border-primary-400">
                            {{__('Change')}}
                        </div>
                    </div>
                </div>

            @else

                {{-- empty state --}}
                <p class="flex items-center text-sm font-light text-zinc-400">
                    <i class="bi bi-cloud-upload mr-2"></i>
                    {{ __('Upload a file or drag and drop') }} | {{ $types ?? 'Any File' }}
                </p>

            @endif
        </div>

        {{-- during upload --}}
        <!-- Progress Bar -->
        <div x-show="isUploading">
            <progress max="100" x-bind:value="progress" class="w-full h-1 overflow-hidden bg-red-500 rounded"></progress>
        </div>
    </div>
    @error($name ?? '')
        <span class="mt-1 text-xs text-red-700">{{ $message }}</span>
    @enderror
  </label>