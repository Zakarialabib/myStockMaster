<div class="space-y-8">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">Database Configuration</h3>
        <p class="mt-2 text-orange-600">Connect MyStockMaster to your database. MySQL is recommended for production.</p>
    </div>

    <div class="space-y-8">
        <div class="bg-white shadow-lg rounded-xl border border-orange-100 overflow-hidden">
            <div class="p-6 space-y-6">
                @if($isDesktopMode)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-green-700">
                                <strong>Desktop mode detected.</strong> SQLite will be used automatically. No database configuration needed.
                            </p>
                        </div>
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-orange-700 mb-3">Database Type</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach(['mysql' => 'MySQL', 'pgsql' => 'PostgreSQL', 'sqlite' => 'SQLite'] as $value => $label)
                                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-orange-50 transition-all {{ $database['connection'] === $value ? 'border-orange-500 bg-orange-50' : 'border-gray-200 bg-white' }}">
                                    <input type="radio" wire:model.live="database.connection" value="{{ $value }}" class="sr-only">
                                    <div class="flex items-center w-full">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ $database['connection'] === $value ? 'bg-orange-100' : 'bg-gray-100' }} flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 {{ $database['connection'] === $value ? 'text-orange-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold {{ $database['connection'] === $value ? 'text-orange-900' : 'text-gray-900' }}">{{ $label }}</p>
                                        </div>
                                    </div>
                                    @if($database['connection'] === $value)
                                        <div class="ml-auto">
                                            <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if($database['connection'] !== 'sqlite')
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="db_host" class="block text-sm font-medium text-orange-700">Host</label>
                                <input type="text" id="db_host" wire:model.live="database.host" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm md:text-md text-gray-900 border-gray-300 rounded-xl px-4 py-2 border" placeholder="127.0.0.1">
                                @error('database.host') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="db_port" class="block text-sm font-medium text-orange-700">Port</label>
                                <input type="text" id="db_port" wire:model.live="database.port" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm md:text-md text-gray-900 border-gray-300 rounded-xl px-4 py-2 border" placeholder="{{ $database['connection'] === 'mysql' ? '3306' : '5432' }}">
                                @error('database.port') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="db_username" class="block text-sm font-medium text-orange-700">Username</label>
                                <input type="text" id="db_username" wire:model.live="database.username" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm md:text-md text-gray-900 border-gray-300 rounded-xl px-4 py-2 border">
                                @error('database.username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="db_password" class="block text-sm font-medium text-orange-700">Password</label>
                                <input type="password" id="db_password" wire:model.live="database.password" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm md:text-md text-gray-900 border-gray-300 rounded-xl px-4 py-2 border">
                                @error('database.password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="db_name" class="block text-sm font-medium text-orange-700">Database Name</label>
                        <input type="text" id="db_name" wire:model.live="database.database" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm md:text-md text-gray-900 border-gray-300 rounded-xl px-4 py-2 border" placeholder="{{ $database['connection'] === 'sqlite' ? 'database/database.sqlite' : 'mystockmaster' }}">
                        @error('database.database') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            @if(!$isDesktopMode)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center">
                        @if($connectionTested)
                            @if($connectionSuccess)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Connection Successful
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Connection Failed
                                </span>
                            @endif
                        @endif
                    </div>

                    <button type="button" wire:click="testConnection" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                        <span wire:loading.remove wire:target="testConnection">Test Connection</span>
                        <span wire:loading wire:target="testConnection" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Testing...
                        </span>
                    </button>
                </div>
            @endif
        </div>

        @if($connectionSuccess || $isDesktopMode)
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            @if($isDesktopMode)
                                SQLite is ready. Click <strong>Next</strong> to continue — database setup is automatic.
                            @else
                                {{ session('connection_success') ?? 'Connection successful! Your database is ready. Click Next to continue.' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session()->has('connection_error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ session('connection_error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
