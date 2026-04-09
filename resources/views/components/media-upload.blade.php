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

<style>
@keyframes progress-bar-stripes {
    from { background-position: 1rem 0; }
    to { background-position: 0 0; }
}
</style>
<div>
    <label class="block mt-4 text-sm">
        <div
            class="w-full p-4 border-2 border-dashed rounded-md transition-colors duration-200 relative cursor-pointer hover:bg-zinc-50"
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
                        <div class="flex items-center p-4 bg-white border border-zinc-200 rounded-lg shadow-sm space-x-4">
                            @if(is_object($file) && method_exists($file, 'temporaryUrl'))
                                <img src="{{ $file->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-md border border-zinc-100 shadow-sm">
                            @elseif(is_string($file) && (str_starts_with($file, 'http') || str_starts_with($file, 'data:')))
                                <img src="{{ $file }}" class="w-24 h-24 object-cover rounded-md border border-zinc-100 shadow-sm">
                            @elseif(!empty($preview))
                                <img src="{{ $preview }}" class="w-24 h-24 object-cover rounded-md border border-zinc-100 shadow-sm">
                            @endif
                            <div class="flex-1 min-w-0">
                                @if(is_object($file))
                                    <p class="text-sm font-semibold text-zinc-800 truncate">{{ $file->getClientOriginalName() }}</p>
                                    <p class="text-xs text-zinc-500 mt-1">
                                        <span class="uppercase font-medium">{{ $file->getClientOriginalExtension() }}</span> &bull; {{ \Illuminate\Support\Number::fileSize($file->getSize(), precision: 2) }}
                                    </p>
                                @else
                                    <p class="text-sm font-semibold text-zinc-800 truncate">{{ __('Uploaded File') }}</p>
                                @endif
                                <div class="flex space-x-3 mt-3">
                                    <button type="button" @click="$refs.fileInput.click()" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded hover:bg-primary-100 hover:border-primary-300 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        {{__('Change')}}
                                    </button>
                                    <button type="button" wire:click="$set('{{ $attributes->wire('model')->value() }}', null)" @click.prevent class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 hover:border-red-300 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{__('Remove')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($image))
                        <div class="flex items-center p-4 bg-white border border-zinc-200 rounded-lg shadow-sm space-x-4">
                            <img src="{{ $image }}" class="w-24 h-24 object-cover rounded-md border border-zinc-100 shadow-sm">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-zinc-800 truncate">{{ __('Existing Image') }}</p>
                                <div class="flex space-x-3 mt-3">
                                    <button type="button" @click="$refs.fileInput.click()" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded hover:bg-primary-100 hover:border-primary-300 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        {{__('Change')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif (!empty($preview))
                        <div class="flex items-center p-4 bg-white border border-zinc-200 rounded-lg shadow-sm space-x-4">
                            <img src="{{ is_string($preview) ? $preview : '' }}" class="w-24 h-24 object-cover rounded-md border border-zinc-100 shadow-sm">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-zinc-800 truncate">{{ __('Existing Image') }}</p>
                                <div class="flex space-x-3 mt-3">
                                    <button type="button" @click="$refs.fileInput.click()" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded hover:bg-primary-100 hover:border-primary-300 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        {{__('Change')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- empty state --}}
                        <div class="flex flex-col items-center justify-center py-8 cursor-pointer group" @click="$refs.fileInput.click()">
                            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-full shadow-sm mb-4 text-zinc-400 group-hover:text-primary-500 group-hover:border-primary-200 group-hover:bg-primary-50 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                            </div>
                            <p class="text-base font-medium text-zinc-700">
                                {{ __('Click to upload') }} <span class="font-normal text-zinc-500">{{ __('or drag and drop') }}</span>
                            </p>
                            <p class="text-sm text-zinc-400 mt-2">{{ $types ?? 'SVG, PNG, JPG or GIF (MAX. 800x400px)' }}</p>
                        </div>
                    @endif

                @else
                    {{-- Multiple Files --}}
                    @if ($file && is_array($file) && count($file) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach ($file as $index => $f)
                                <div class="relative group rounded-lg overflow-hidden border border-zinc-200 shadow-sm bg-white aspect-square flex items-center justify-center">
                                    @if(is_object($f) && method_exists($f, 'temporaryUrl'))
                                        <img src="{{ $f->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif(is_string($f) && (str_starts_with($f, 'http') || str_starts_with($f, 'data:')))
                                        <img src="{{ $f }}" class="w-full h-full object-cover">
                                    @elseif(!empty($preview) && is_array($preview) && isset($preview[$index]))
                                        <img src="{{ $preview[$index] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-zinc-50 flex flex-col items-center justify-center text-zinc-400 p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            @if(is_object($f))
                                                <p class="text-[10px] text-center w-full truncate px-1" title="{{ $f->getClientOriginalName() }}">
                                                    {{ $f->getClientOriginalName() }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <!-- Overlay with remove button -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        <button type="button" @click.stop="let items = [...($wire.get('{{ $attributes->wire('model')->value() }}') || [])]; items.splice({{ $index }}, 1); $wire.set('{{ $attributes->wire('model')->value() }}', items)" class="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 shadow-lg transform scale-90 group-hover:scale-100 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    @if(is_object($f) && (method_exists($f, 'temporaryUrl') || (is_string($f) && (str_starts_with($f, 'http') || str_starts_with($f, 'data:'))) || (!empty($preview) && is_array($preview) && isset($preview[$index]))))
                                        <!-- File name badge for images -->
                                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent p-2 pt-6 pointer-events-none">
                                            <p class="text-[10px] text-white truncate" title="{{ $f->getClientOriginalName() }}">
                                                {{ $f->getClientOriginalName() }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            
                            <!-- Add More Button -->
                            <div class="rounded-lg border-2 border-dashed border-zinc-300 bg-zinc-50 aspect-square flex flex-col items-center justify-center text-zinc-400 cursor-pointer hover:bg-zinc-100 hover:border-primary-400 hover:text-primary-500 transition-colors group" @click="$refs.fileInput.click()">
                                <div class="p-2 bg-white rounded-full shadow-sm mb-2 group-hover:bg-primary-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium">{{ __('Add More') }}</span>
                            </div>
                        </div>
                    @elseif (!empty($image) && is_array($image) && count($image) > 0)
                        {{-- Pre-existing multiple images --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach ($image as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-zinc-200 shadow-sm bg-white aspect-square flex items-center justify-center">
                                    <img src="{{ $img }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                            <!-- Add More Button -->
                            <div class="rounded-lg border-2 border-dashed border-zinc-300 bg-zinc-50 aspect-square flex flex-col items-center justify-center text-zinc-400 cursor-pointer hover:bg-zinc-100 hover:border-primary-400 hover:text-primary-500 transition-colors group" @click="$refs.fileInput.click()">
                                <div class="p-2 bg-white rounded-full shadow-sm mb-2 group-hover:bg-primary-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium">{{ __('Add More') }}</span>
                            </div>
                        </div>
                    @else
                        {{-- empty state --}}
                        <div class="flex flex-col items-center justify-center py-8 cursor-pointer group" @click="$refs.fileInput.click()">
                            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-full shadow-sm mb-4 text-zinc-400 group-hover:text-primary-500 group-hover:border-primary-200 group-hover:bg-primary-50 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                            </div>
                            <p class="text-base font-medium text-zinc-700">
                                {{ __('Click to upload multiple files') }} <span class="font-normal text-zinc-500">{{ __('or drag and drop') }}</span>
                            </p>
                            <p class="text-sm text-zinc-400 mt-2">{{ $types ?? 'SVG, PNG, JPG or GIF (MAX. 800x400px)' }}</p>
                        </div>
                    @endif
                @endif
            </div>

            {{-- during upload --}}
            <!-- Progress Bar -->
            <div x-show="isUploading" class="py-4">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-primary-700 dark:text-primary-500">{{ __('Uploading...') }}</span>
                    <span class="text-sm font-medium text-primary-700 dark:text-primary-500" x-text="progress + '%'"></span>
                </div>
                <div class="w-full bg-zinc-200 rounded-full h-3 dark:bg-zinc-700 overflow-hidden relative">
                    <div class="bg-primary-600 h-3 rounded-full transition-all duration-300 relative overflow-hidden" x-bind:style="'width: ' + progress + '%'">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.15)_50%,rgba(255,255,255,0.15)_75%,transparent_75%,transparent)] bg-[length:1rem_1rem] opacity-50" style="animation: progress-bar-stripes 1s linear infinite;"></div>
                    </div>
                </div>
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
