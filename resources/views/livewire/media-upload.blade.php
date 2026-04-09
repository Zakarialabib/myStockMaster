<div>
    <label class="block mt-4 text-sm">
        <div
            class="w-full p-2 bg-zinc-100 border border-zinc-300 border-dashed rounded-sm"
            x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">

            <div x-show="!isUploading">

                {{-- Form File picker --}}
                <input type="file" class="hidden" accept="{{ $fileTypes ?? '*' }}" {{ !$single ? 'multiple' : '' }}
                    wire:model="file"
                />

                @if($single)
                    @if ($file)
                        <div class="flex items-center space-x-4">
                            @if(is_object($file) && method_exists($file, 'temporaryUrl'))
                                <img src="{{ $file->temporaryUrl() }}" class="w-20 h-20 object-cover">
                            @elseif(is_string($file))
                                <img src="{{ $file }}" class="w-20 h-20 object-cover">
                            @endif
                            <div class="font-light text-zinc-500">
                                @if(is_object($file))
                                    <p>Type: {{ $file->getClientOriginalExtension() }}</p>
                                    <p>Size: {{ \Illuminate\Support\Number::fileSize($file->getSize(), precision: 2) }}</p>
                                @endif
                                <button type="button" wire:click="removeSingle" class="px-2 mt-2 text-xs text-red-400 border border-red-400 rounded-sm">
                                    {{__('Remove')}}
                                </button>
                            </div>
                        </div>
                    @elseif (!empty($image))
                        <div class="flex items-center space-x-4">
                            <img src="{{ $image }}" class="w-20 h-20 object-cover">
                            <div class="font-light text-zinc-500">
                                <div class="px-2 mt-2 text-xs border rounded-sm text-primary-400 border-primary-400">
                                    {{__('Change')}}
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($preview))
                        {{-- Usually preview acts similarly to existing image if string URL --}}
                        <div class="flex items-center space-x-4">
                            <img src="{{ is_string($preview) ? $preview : '' }}" class="w-20 h-20 object-cover">
                            <div class="font-light text-zinc-500">
                                <div class="px-2 mt-2 text-xs border rounded-sm text-primary-400 border-primary-400">
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

                @else
                    {{-- Multiple Files --}}
                    @if ($file && is_array($file) && count($file) > 0)
                        <div class="flex flex-wrap gap-4">
                            @foreach ($file as $index => $f)
                                <div class="flex flex-col items-center space-y-2">
                                    @if(is_object($f) && method_exists($f, 'temporaryUrl'))
                                        <img src="{{ $f->temporaryUrl() }}" class="w-20 h-20 object-cover">
                                    @elseif(is_string($f))
                                        <img src="{{ $f }}" class="w-20 h-20 object-cover">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                    @endif
                                    <div class="font-light text-zinc-500 text-xs text-center">
                                        @if(is_object($f))
                                            <p>{{ \Illuminate\Support\Number::fileSize($f->getSize(), precision: 2) }}</p>
                                        @endif
                                        <button type="button" wire:click="removeMultiple({{ $index }})" class="px-2 mt-1 text-red-400 border border-red-400 rounded-sm">
                                            {{__('Remove')}}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            <div class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-zinc-300 text-zinc-400 cursor-pointer" onclick="this.closest('div[x-data]').querySelector('input[type=file]').click()">
                                <i class="bi bi-plus text-2xl"></i>
                            </div>
                        </div>
                    @elseif (!empty($image) && is_array($image) && count($image) > 0)
                        {{-- Pre-existing multiple images --}}
                        <div class="flex flex-wrap gap-4">
                            @foreach ($image as $img)
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $img }}" class="w-20 h-20 object-cover">
                                </div>
                            @endforeach
                            <div class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-zinc-300 text-zinc-400 cursor-pointer" onclick="this.closest('div[x-data]').querySelector('input[type=file]').click()">
                                <i class="bi bi-plus text-2xl"></i>
                            </div>
                        </div>
                    @else
                        {{-- empty state --}}
                        <p class="flex items-center text-sm font-light text-zinc-400">
                            <i class="bi bi-cloud-upload mr-2"></i>
                            {{ __('Upload files or drag and drop') }} | {{ $types ?? 'Any File' }}
                        </p>
                    @endif
                @endif
            </div>

            {{-- during upload --}}
            <!-- Progress Bar -->
            <div x-show="isUploading">
                <progress max="100" x-bind:value="progress" class="w-full h-1 overflow-hidden bg-red-500 rounded-sm"></progress>
            </div>
        </div>
        @error('file')
            <span class="mt-1 text-xs text-red-700">{{ $message }}</span>
        @enderror
    </label>
</div>
