<section class="py-5 px-6 bg-white shadow">
    <nav class="space-x-3">
      <x-button primary :href="route('home')" :active="request()->routeIs('home')">
        {{ __('Dashobard') }}
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