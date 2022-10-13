<section class="py-5 px-6 bg-white shadow">
    <nav class="space-x-3">
      <x-button primary :href="route('home')" :active="request()->routeIs('home')">
        {{ __('Dashobard') }}
        </x-button>

        <x-button primary type="submit" wire:click="$emit('createProduct')">
            {{ __('Create Product') }}
        </x-button>

        <x-button primary type="submit" wire:click="$emit('createCustomer')">
            {{ __('Create Customer') }}
        </x-button>


    </nav>
  </section>