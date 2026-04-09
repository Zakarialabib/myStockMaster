@props([
    'name' => '',
    'title' => 'Upload File',
    'single' => true,
    'types' => '',
    'fileTypes' => '*',
    'maxSize' => 0,
    'image' => null,
    'preview' => true,
    'maxFiles' => 0,
    'file' => null,
])

<div>
    <label class="block mt-4 text-sm">
        <div
            class="w-full p-4 border-2 border-dashed rounded-md transition-colors duration-200 relative"
    x-data="{ isUploading: false, progress: 0, isDropping: false, hasError: false }"
    x-on:livewire-upload-start="isUploading = true; hasError = false"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false; hasError = true"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    x-on:dragover.prevent="isDropping = true"
    x-on:dragleave.prevent="isDropping = false"
    x-on:drop.prevent="
        isDropping = false;
        hasError = false;
        if ($event.dataTransfer.files.length > 0) {
            if ('{{ !$single ? 1 : 0 }}' === '0') {
                const dt = new DataTransfer();
                dt.items.add($event.dataTransfer.files[0]);
                $refs.fileInput.files = dt.files;
            } else {
                $refs.fileInput.files = $event.dataTransfer.files;
            }
            $refs.fileInput.dispatchEvent(new Event('change'));
        }
    "
    x-bind:class="{ 'bg-blue-50 border-blue-400': isDropping, 'bg-red-50 border-red-300': hasError && !isDropping, 'bg-zinc-50 border-zinc-300': !isDropping && !hasError }"
>

            <div x-show="!isUploading">

                {{-- Form File picker --}}
                <input type="file" class="hidden" x-ref="fileInput" accept="{{ $fileTypes ?? '*' }}" {{ !$single ? 'multiple' : '' }}
                    {{ $attributes->wire('model') }}
                />

                @if($single)
                    @if ($file)
                        <div class="flex items-center space-x-4">
                            @if(is_object($file) && method_exists($file, 'temporaryUrl'))
                                <img src="{{ $file->temporaryUrl() }}" class="w-20 h-20 object-cover rounded shadow-sm border border-zinc-200">
                            @elseif(is_string($file) && (str_starts_with($file, 'http') || str_starts_with($file, 'data:')))
                                <img src="{{ $file }}" class="w-20 h-20 object-cover rounded shadow-sm border border-zinc-200">
                            @elseif(!empty($preview))
                                <img src="{{ $preview }}" class="w-20 h-20 object-cover rounded shadow-sm border border-zinc-200">
                            @endif
                            <div class="font-light text-zinc-500">
                                @if(is_object($file))
                                    <p class="text-sm font-medium text-zinc-700">Type: <span class="uppercase">{{ $file->getClientOriginalExtension() }}</span></p>
                                    <p class="text-xs">Size: {{ \Illuminate\Support\Number::fileSize($file->getSize(), precision: 2) }}</p>
                                @endif
                                <div class="flex space-x-2 mt-2">
                                    <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1 text-xs text-primary-600 border border-primary-500 rounded hover:bg-primary-50 transition">
                                        {{__('Change')}}
                                    </button>
                                    <button type="button" wire:click="$set('{{ $attributes->wire('model')->value() }}', null)" class="px-3 py-1 text-xs text-red-500 border border-red-400 rounded hover:bg-red-50 transition">
                                        {{__('Remove')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($image))
                        <div class="flex items-center space-x-4">
                            <img src="{{ $image }}" class="w-20 h-20 object-cover rounded shadow-sm border border-zinc-200">
                            <div class="font-light text-zinc-500">
                                <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1 mt-2 text-xs text-primary-600 border border-primary-500 rounded hover:bg-primary-50 transition">
                                    {{__('Change')}}
                                </button>
                            </div>
                        </div>
                    @elseif (!empty($preview))
                        <div class="flex items-center space-x-4">
                            <img src="{{ is_string($preview) ? $preview : '' }}" class="w-20 h-20 object-cover rounded shadow-sm border border-zinc-200">
                            <div class="font-light text-zinc-500">
                                <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1 mt-2 text-xs text-primary-600 border border-primary-500 rounded hover:bg-primary-50 transition">
                                    {{__('Change')}}
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- empty state --}}
                        <div class="flex flex-col items-center justify-center py-4 cursor-pointer" @click="$refs.fileInput.click()">
                            <div class="p-3 bg-white border border-zinc-200 rounded-full shadow-sm mb-3 text-zinc-500 group-hover:text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-700">
                                {{ __('Click to upload') }} <span class="font-normal text-zinc-500">{{ __('or drag and drop') }}</span>
                            </p>
                            <p class="text-xs text-zinc-400 mt-1">{{ $types ?? 'Any File' }}</p>
                        </div>
                    @endif

                @else
                    {{-- Multiple Files --}}
                    @if ($file && is_array($file) && count($file) > 0)
                        <div class="flex flex-wrap gap-4">
                            @foreach ($file as $index => $f)
                                <div class="flex flex-col items-center space-y-2 relative group">
                                    @if(is_object($f) && method_exists($f, 'temporaryUrl'))
                                        <img src="{{ $f->temporaryUrl() }}" class="w-24 h-24 object-cover rounded shadow-sm border border-zinc-200">
                                    @elseif(is_string($f) && (str_starts_with($f, 'http') || str_starts_with($f, 'data:')))
                                        <img src="{{ $f }}" class="w-24 h-24 object-cover rounded shadow-sm border border-zinc-200">
                                    @elseif(!empty($preview) && is_array($preview) && isset($preview[$index]))
                                        <img src="{{ $preview[$index] }}" class="w-24 h-24 object-cover rounded shadow-sm border border-zinc-200">
                                    @else
                                        <div class="w-24 h-24 bg-zinc-100 border border-zinc-200 rounded shadow-sm flex items-center justify-center text-zinc-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <button type="button" @click="let items = [...($wire.get('{{ $attributes->wire('model')->value() }}') || [])]; items.splice({{ $index }}, 1); $wire.set('{{ $attributes->wire('model')->value() }}', items)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    @if(is_object($f))
                                        <p class="text-[10px] text-zinc-500 max-w-[6rem] truncate" title="{{ $f->getClientOriginalName() }}">
                                            {{ $f->getClientOriginalName() }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                            
                            <div class="flex flex-col items-center justify-center w-24 h-24 border-2 border-dashed border-zinc-300 rounded text-zinc-400 cursor-pointer hover:bg-zinc-100 hover:text-primary-500 transition-colors" @click="$refs.fileInput.click()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-xs font-medium">{{ __('Add More') }}</span>
                            </div>
                        </div>
                    @elseif (!empty($image) && is_array($image) && count($image) > 0)
                        {{-- Pre-existing multiple images --}}
                        <div class="flex flex-wrap gap-4">
                            @foreach ($image as $img)
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $img }}" class="w-24 h-24 object-cover rounded shadow-sm border border-zinc-200">
                                </div>
                            @endforeach
                            <div class="flex flex-col items-center justify-center w-24 h-24 border-2 border-dashed border-zinc-300 rounded text-zinc-400 cursor-pointer hover:bg-zinc-100 hover:text-primary-500 transition-colors" @click="$refs.fileInput.click()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-xs font-medium">{{ __('Add More') }}</span>
                            </div>
                        </div>
                    @else
                        {{-- empty state --}}
                        <div class="flex flex-col items-center justify-center py-4 cursor-pointer" @click="$refs.fileInput.click()">
                            <div class="p-3 bg-white border border-zinc-200 rounded-full shadow-sm mb-3 text-zinc-500 group-hover:text-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-zinc-700">
                                {{ __('Click to upload multiple files') }} <span class="font-normal text-zinc-500">{{ __('or drag and drop') }}</span>
                            </p>
                            <p class="text-xs text-zinc-400 mt-1">{{ $types ?? 'Any File' }}</p>
                        </div>
                    @endif
                @endif
            </div>

            {{-- during upload --}}
            <!-- Progress Bar -->
            <div x-show="isUploading" class="mt-4 w-full bg-zinc-200 rounded-full h-2.5 dark:bg-zinc-700">
                <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-300" x-bind:style="'width: ' + progress + '%'"></div>
                <p class="text-xs text-zinc-500 mt-2 text-center" x-text="'Uploading... ' + progress + '%'"></p>
            </div>

            <!-- Upload Error Message -->
            <div x-show="hasError" x-cloak class="mt-3 flex items-center text-red-500 text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ __('The file failed to upload. Check your PHP upload_tmp_dir or file size limits.') }}</span>
            </div>
        </div>
        @error('file')
            <span class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</span>
        @enderror
    </label>
</div>
