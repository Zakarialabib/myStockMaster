<div class="space-y-8">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-orange-900 font-display">Who are you?</h3>
        <p class="mt-2 text-orange-600">Select the persona that best describes your technical background.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Retail Persona -->
        <button wire:click="selectPersona('retail')"
                class="group relative flex flex-col items-center p-8 bg-white border-2 {{ $persona === 'retail' ? 'border-orange-500 bg-orange-50' : 'border-orange-100 hover:border-orange-300' }} rounded-2xl transition-all duration-200 text-left focus:outline-none focus:ring-2 focus:ring-orange-500">
            <div class="w-16 h-16 bg-orange-100 group-hover:bg-orange-200 rounded-full flex items-center justify-center mb-6 transition-colors duration-200">
                <span class="text-3xl">🛒</span>
            </div>
            <h4 class="text-xl font-bold text-orange-900 mb-2">Retail / One-Click</h4>
            <p class="text-sm text-orange-600 text-center">
                Non-technical user. Wants a working app quickly with minimal configuration.
                Uses SQLite and default settings.
            </p>
            @if($isDesktop)
                <div class="mt-4 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                    Recommended for Desktop
                </div>
            @endif
        </button>

        <!-- Technician Persona -->
        <button wire:click="selectPersona('technician')"
                class="group relative flex flex-col items-center p-8 bg-white border-2 {{ $persona === 'technician' ? 'border-orange-500 bg-orange-50' : 'border-orange-100 hover:border-orange-300' }} rounded-2xl transition-all duration-200 text-left focus:outline-none focus:ring-2 focus:ring-orange-500">
            <div class="w-16 h-16 bg-orange-100 group-hover:bg-orange-200 rounded-full flex items-center justify-center mb-6 transition-colors duration-200">
                <span class="text-3xl">🛠️</span>
            </div>
            <h4 class="text-xl font-bold text-orange-900 mb-2">Technician / Expert</h4>
            <p class="text-sm text-orange-600 text-center">
                Technical user / IT staff. Full control over database (MySQL/PostgreSQL),
                server requirements, and advanced configuration.
            </p>
            @if(!$isDesktop)
                <div class="mt-4 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                    Recommended for Web/Server
                </div>
            @endif
        </button>
    </div>
</div>
