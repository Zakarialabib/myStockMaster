<section class="py-5 px-6 bg-white shadow">
    <nav class="flex items-center justify-between flex-shrink-0 px-3">
         <!-- Logo -->
        <a href="{{ route('home') }}" class="text-xl font-semibold">
            <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
            <span class="sr-only">{{ config('settings.site_title') }}</span>
        </a>
      <x-button primary :href="route('home')" :active="request()->routeIs('home')">
        {{ __('Dashboard') }}
        </x-button>

        <x-button primary type="button" onclick="Livewire.emit('createProduct', 'show')">
            {{ __('Create Product') }}
        </x-button>

        <x-button primary type="button" onclick="Livewire.emit('createCustomer', 'show')">
            {{ __('Create Customer') }}
        </x-button>
        
        <x-button primary type="button" onclick="Livewire.emit('recentSales', 'show')">
            {{ __('Recent Sales') }}
        </x-button>
    </nav>
  </section>