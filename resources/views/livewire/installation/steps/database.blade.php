<div class="space-y-8">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l2 2m0 0l2-2m-2 2v6"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">Database Configuration</h3>
        <p class="mt-2 text-orange-600">Connect your restaurant system to the database for data storage.</p>
    </div>

    <div class="space-y-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="db_connection" class="block text-sm font-medium text-orange-700">Connection Type</label>
                <select 
                    id="db_connection" 
                    wire:model="database.connection" 
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-orange-300 focus:outline-hidden focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md"
                >
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                    <option value="sqlite">SQLite</option>
                    <option value="sqlsrv">SQL Server</option>
                </select>
                @error('database.connection') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            @if($database['connection'] !== 'sqlite')
                <div>
                    <label for="db_host" class="block text-sm font-medium text-orange-700">Host</label>
                    <input 
                        type="text" 
                        id="db_host" 
                        wire:model.defer="database.host" 
                        class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-xs sm:text-sm border-orange-300 rounded-md"
                        placeholder="localhost"
                    >
                    @error('database.host') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            <div>
                <label for="db_port" class="block text-sm font-medium text-orange-700">Port</label>
                <input 
                    type="text" 
                    id="db_port" 
                    wire:model.defer="database.port" 
                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-xs sm:text-sm border-orange-300 rounded-md"
                    placeholder="{{ $database['connection'] === 'mysql' ? '3306' : ($database['connection'] === 'pgsql' ? '5432' : '') }}"
                >
                @error('database.port') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="db_name" class="block text-sm font-medium text-orange-700">Database Name</label>
                <input 
                    type="text" 
                    id="db_name" 
                    wire:model.defer="database.database" 
                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-xs sm:text-sm border-orange-300 rounded-md"
                >
                @error('database.database') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="db_username" class="block text-sm font-medium text-orange-700">Username</label>
                <input 
                    type="text" 
                    id="db_username" 
                    wire:model.defer="database.username" 
                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-xs sm:text-sm border-orange-300 rounded-md"
                >
                @error('database.username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="db_password" class="block text-sm font-medium text-orange-700">Password</label>
                <input 
                    type="password" 
                    id="db_password" 
                    wire:model.defer="database.password" 
                    class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-xs sm:text-sm border-orange-300 rounded-md"
                >
                @error('database.password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <button 
                type="button" 
                wire:click="testConnection" 
                wire:loading.attr="disabled" 
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-xs text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
            >
                <span wire:loading.remove wire:target="testConnection">Test Connection</span>
                <span wire:loading wire:target="testConnection">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Testing...
                </span>
            </button>
        </div>

        @if(session()->has('connection_success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('connection_success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session()->has('connection_error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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

        <div class="mt-4 text-sm text-orange-600">
            <p class="font-medium">What happens next?</p>
            <ul class="mt-2 list-disc pl-5 space-y-1">
                <li>We'll verify your database connection</li>
                <li>Update your .env file with the database settings</li>
                <li>Run database migrations to set up the necessary tables</li>
                <li>Seed the database with essential data</li>
            </ul>
        </div>
    </div>
</div>