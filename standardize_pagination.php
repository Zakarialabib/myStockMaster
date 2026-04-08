<?php

$files = [
    'resources/views/livewire/brands/index.blade.php' => 'brands',
    'resources/views/livewire/customers/index.blade.php' => 'customers',
    'resources/views/livewire/expense/index.blade.php' => 'expenses',
    'resources/views/livewire/expense-categories/index.blade.php' => 'expenseCategories',
    'resources/views/livewire/purchase/index.blade.php' => 'purchases',
    'resources/views/livewire/sales/index.blade.php' => 'sales',
    'resources/views/livewire/suppliers/index.blade.php' => 'suppliers',
    // 'resources/views/livewire/products/index.blade.php' => 'products', // products uses a different bottom block
    'resources/views/livewire/warehouses/index.blade.php' => 'warehouses',
    'resources/views/livewire/users/index.blade.php' => 'users',
    'resources/views/livewire/role/index.blade.php' => 'roles',
    'resources/views/livewire/permission/index.blade.php' => 'permissions',
    'resources/views/livewire/printer/index.blade.php' => 'printers',
    'resources/views/livewire/adjustment/index.blade.php' => 'adjustments',
    'resources/views/livewire/cash-register/index.blade.php' => 'cashRegisters',
    'resources/views/livewire/currency/index.blade.php' => 'currencies',
    'resources/views/livewire/customer-group/index.blade.php' => 'customergroups',
];

foreach ($files as $bladeFile => $varName) {
    if (!file_exists($bladeFile)) continue;
    $content = file_get_contents($bladeFile);
    
    // We want to find the section right after </x-table> up to the </x-page-container> or whatever follows it.
    // Or we can just find the block containing {{ $varName->links() }} and replace it.
    // Let's use a regex to match </x-table> and everything after it until </x-page-container>, 
    // BUT we need to be careful not to replace modals.
    // Most files have `{{ $varName->links() }}` inside some div.
    
    // So let's look for `<div ...> ... {{ $varName->links() }} ... </div>`
    // It's hard to match nested divs reliably with regex.
    // Alternatively, match from `</x-table>` to the first `@livewire` or `<x-modal` or `</x-page-container>`
    
    $pattern = '/<\/x-table>\s*(.*?)\{\{\s*\$' . $varName . '->links\(\)\s*\}\}(.*?)(?=@livewire|<x-modal|<\/x-page-container>|<!--)/s';
    
    $replacement = '</x-table>

        <!-- Pagination Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4 mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                @if ($this->selectedCount)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-blue-500 dark:text-blue-400"></i>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $this->selectedCount }}</span>
                            {{ __(\'of\') }} {{ $'.$varName.'->total() }} {{ __(\'entries selected\') }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __(\'Showing\') }} {{ $'.$varName.'->firstItem() ?? 0 }} {{ __(\'to\') }}
                        {{ $'.$varName.'->lastItem() ?? 0 }} {{ __(\'of\') }} {{ $'.$varName.'->total() }}
                        {{ __(\'results\') }}
                    </p>
                @endif
                <div class="flex justify-center sm:justify-end">
                    {{ $'.$varName.'->links() }}
                </div>
            </div>
        </div>
        ';
        
    $newContent = preg_replace($pattern, $replacement, $content);
    if ($newContent && $newContent !== $content) {
        file_put_contents($bladeFile, $newContent);
        echo "Updated pagination in $bladeFile\n";
    } else {
        echo "Failed or no change in $bladeFile\n";
    }
}
