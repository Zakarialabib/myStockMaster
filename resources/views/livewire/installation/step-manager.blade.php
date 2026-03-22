<div>
    @if($isInstalled && !config('installation.force', false))
        <!-- Installation is already completed -->
        <div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
            <div class="flex items-center justify-center min-h-screen">
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
        <!-- Show installation process -->
        <div class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-4xl w-full">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-orange-900 font-display sm:text-5xl uppercase">
                    {{ config('app.name', 'MYSTOCKMASTER') }} {{(__('Installation'))}}
                </h1>
                <p class="mt-3 text-lg text-orange-600 font-medium">
                    {{ $this->stepTitle }}
                </p>
            </div>

            <div class="mb-8">
                <div class="w-full bg-orange-100 rounded-full h-3">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                         style="width: {{ ($currentStep / count($steps)) * 100 }}%"></div>
                </div>
                <div class="mt-3 flex justify-between text-xs font-medium text-orange-400">
                    @foreach($steps as $index => $step)
                        <span class="{{ ($index + 1) <= $currentStep ? 'text-orange-600' : '' }} capitalize">
                            {{ $step }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="bg-white shadow-xl rounded-2xl border border-orange-100 overflow-hidden min-h-[400px]">
                <div class="px-8 py-8">
                    @php $stepName = $steps[$currentStep - 1]; @endphp

                    @if($stepName === 'persona')
                        @include('livewire.installation.steps.persona')
                    @elseif($stepName === 'requirements')
                        @include('livewire.installation.steps.requirements')
                    @elseif($stepName === 'database')
                        @include('livewire.installation.steps.database')
                    @elseif($stepName === 'company')
                        @include('livewire.installation.steps.company-details')
                    @elseif($stepName === 'admin')
                        @include('livewire.installation.steps.admin-user')
                    @elseif($stepName === 'demo')
                        @include('livewire.installation.demo-selection')
                    @elseif($stepName === 'finish')
                        @include('livewire.installation.steps.finish')
                    @endif
                </div>
            </div>

            <!-- Validation errors -->
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

            <div class="mt-8 flex justify-between">
                @if($currentStep > 1 && $steps[$currentStep - 1] !== 'finish')
                    <button
                        wire:click="previousStep"
                        class="inline-flex items-center px-6 py-3 border border-orange-300 rounded-xl text-sm font-semibold text-orange-700 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-sm hover:shadow-md"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < count($steps))
                    <button
                        wire:click="nextStep"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                    >
                        Next
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        </div>
    @endif
</div>
