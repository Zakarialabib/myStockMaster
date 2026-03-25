<div>
    @if(config('installation.skip', false))
        {{-- Installation is skipped via configuration --}}
        <div class="bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">
            <div class="flex justify-end p-6">
                @include('components.theme.language-switcher', ['languages' => \App\Models\Language::where('status', true)->pluck('name', 'code')->toArray()])
            </div>
            
            <div class="flex items-center justify-center min-h-screen -mt-20">
            <div class="max-w-md mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-green-900 mb-4">Installation Skipped</h1>
                <p class="text-green-600 mb-6">The installation process has been bypassed via configuration.</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200">
                    Go to Dashboard
                </a>
            </div>
            </div>
        </div>
    @elseif(settings('installation_completed', false) && !config('installation.force', false))
        {{-- Installation is already completed --}}
        <div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
            <div class="flex justify-end p-6">
                @include('components.theme.language-switcher', ['languages' => \App\Models\Language::where('status', true)->pluck('name', 'code')->toArray()])
            </div>
            
            <div class="flex items-center justify-center min-h-screen -mt-20">
            <div class="max-w-md mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-blue-900 mb-4">Already Installed</h1>
                <p class="text-blue-600 mb-6">{{ config('app.name', 'Stock Management System') }} has already been installed and configured.</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-200">
                    Go to Dashboard
                </a>
            </div>
            </div>
        </div>
    @else
        {{-- Show installation process --}}
        <div class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen">
        
        <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-8">
                @if($isDesktopMode)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 mb-4">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5z"/></svg>
                        Desktop Mode
                    </div>
                @endif
        
                <h1 class="text-4xl font-bold text-orange-900 font-display sm:text-5xl">
                    {{ config('app.name', 'MYSTOCKMASTER') }} {{ __('Installation') }}
                </h1>
                <p class="mt-3 text-lg text-orange-600 font-medium">
                    {{ __('Step') }} {{ $currentStep }} {{ __('of') }} {{ count($this->steps) }}
                </p>
            </div>

            {{-- Progress bar --}}
            <div class="mb-8">
                <div class="w-full bg-orange-100 rounded-full h-3">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                         style="width: {{ ($currentStep / count($this->steps)) * 100 }}%"></div>
                </div>
                <div class="mt-3 flex justify-between text-sm font-medium text-orange-600">
                    @foreach($this->steps as $index => $stepName)
                        @php
                            $stepIndex = $index + 1;
                            $label = match($stepName) {
                                'persona' => 'Persona',
                                'requirements' => 'Requirements',
                                'database' => 'Database',
                                'company' => 'Company',
                                'settings' => 'Settings',
                                'admin' => 'Admin',
                                'demo' => 'Demo Data',
                                'finish' => 'Finish',
                                default => ucfirst($stepName),
                            };
                        @endphp
                        <button wire:click="goToStep({{ $stepIndex }})" 
                                class="{{ $currentStep >= $stepIndex ? 'text-orange-600' : 'text-orange-400' }} hover:text-orange-700 transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500 rounded px-2 py-1">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Step content --}}
            <div class="bg-white shadow-xl rounded-2xl border border-orange-100 overflow-hidden">
                <div class="px-8 py-8">
                    @php
                        $currentStepName = $this->steps[$currentStep - 1] ?? 'finish';
                    @endphp
                    
                    @if($currentStepName === 'persona')
                        @include('livewire.installation.steps.persona')
                    @elseif($currentStepName === 'requirements')
                        @include('livewire.installation.steps.requirements')
                    @elseif($currentStepName === 'database')
                        @include('livewire.installation.steps.database')
                    @elseif($currentStepName === 'company')
                        @include('livewire.installation.steps.company-details')
                    @elseif($currentStepName === 'demo')
                        @include('livewire.installation.demo-selection')
                    @elseif($currentStepName === 'settings')
                        @include('livewire.installation.steps.site-settings')
                    @elseif($currentStepName === 'admin')
                        @include('livewire.installation.steps.admin-user')
                    @elseif($currentStepName === 'finish')
                        @include('livewire.installation.steps.finish')
                    @endif
                </div>
            </div>

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-xl">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                Please fix the following errors:
                                <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Navigation buttons --}}
            <div class="mt-8 flex justify-between">
                @if($currentStep > 1 && $this->steps[$currentStep - 1] !== 'finish')
                    <button
                        wire:click="previousStep"
                        class="inline-flex items-center px-6 py-3 border border-orange-300 rounded-xl text-sm font-semibold text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-sm hover:shadow-md"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        {{ __('Previous') }}
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < count($this->steps))
                    <button
                        wire:click="nextStep"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                    >
                        {{ __('Next') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <button
                        wire:click="completeInstallation"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                    >
                        {{ __('Complete Installation') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        </div>
    @endif
</div>
