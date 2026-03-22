<div class="space-y-8">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">System Requirements</h3>
        <p class="mt-2 text-orange-600">Please ensure your server meets the following requirements to run MyStockMaster smoothly.</p>
    </div>

    <div class="bg-white shadow-lg rounded-xl border border-orange-100 overflow-hidden">
        <ul class="divide-y divide-orange-50">
            <li class="px-6 py-5 hover:bg-orange-50 transition-colors duration-150">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-orange-900">PHP Version</p>
                            <p class="text-sm text-orange-500">Required: PHP 8.2 or higher</p>
                        </div>
                    </div>
                    <div class="ml-2 flex-shrink-0">
                        @if(version_compare(PHP_VERSION, '8.2.0', '>='))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Pass
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                Fail
                            </span>
                        @endif
                    </div>
                </div>
            </li>

            @php
                $requiredExtensions = ['BCMath', 'Ctype', 'DOM', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'Tokenizer', 'XML', 'sqlite3'];
            @endphp

            @foreach($requiredExtensions as $extension)
                <li class="px-6 py-5 hover:bg-orange-50 transition-colors duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-orange-900">{{ $extension }} Extension</p>
                            </div>
                        </div>
                        <div class="ml-2 flex-shrink-0">
                            @if(extension_loaded(strtolower($extension)))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    OK
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    Missing
                                </span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    @if(count($requirementErrors) > 0)
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mt-4 rounded-r-xl">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Errors detected:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($requirementErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mt-4 rounded-r-xl">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        All systems go! Your environment meets the requirements.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
