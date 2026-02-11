<div class="text-center">
    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 shadow-lg">
        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    
    <h3 class="mt-6 text-3xl font-bold text-orange-900 font-display">🎉 Installation Complete!</h3>
    <p class="mt-3 text-lg text-orange-600 max-w-md mx-auto">
        Congratulations! Your Stock Management system is now ready. You can now manage your inventory, add products, and start processing orders.
    </p>
    
    <div class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6 max-w-sm mx-auto">
        <h4 class="font-semibold text-green-800 mb-2">What's next?</h4>
        <ul class="text-sm text-green-700 space-y-1 text-left">
            <li>• Set up your product categories</li>
            <li>• Add your inventory items</li>
            <li>• Configure suppliers and customers</li>
            <li>• Start managing your stock!</li>
        </ul>
    </div>
    
    <div class="mt-8">
        <button wire:click="completeInstallation" 
                class="inline-flex items-center px-8 py-4 border border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Launch Dashboard
            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</div>