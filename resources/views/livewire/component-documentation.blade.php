<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Component Documentation</h1>
                        <p class="mt-2 text-gray-600">Interactive examples and usage guide for MyStockMaster components</p>
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="exportData" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Guide
                        </button>
                        <button wire:click="refreshData" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-x-8" aria-label="Tabs">
                @php
                    $tabs = [
                        'overview' => 'Overview',
                        'tables' => 'Tables & Data',
                        'forms' => 'Forms & Inputs',
                        'modals' => 'Modals & Dialogs',
                        'navigation' => 'Navigation',
                        'feedback' => 'Feedback & Status',
                        'layout' => 'Layout & Structure',
                        'interactive' => 'Interactive Elements'
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <button wire:click="setActiveTab('{{ $key }}')"
                            class="{{ $activeTab === $key ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="ml-3 text-sm text-green-700">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        {{-- Overview Tab --}}
        @if($activeTab === 'overview')
            <div class="space-y-8">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Component Library Overview</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 mb-6">This documentation showcases all available Blade components in MyStockMaster with interactive examples and usage patterns.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="text-blue-600 font-semibold text-lg">{{ count(glob(resource_path('views/components/*.blade.php'))) }}</div>
                                <div class="text-sm text-blue-800">Core Components</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="text-green-600 font-semibold text-lg">{{ count(glob(resource_path('views/components/datatable/*.blade.php'))) }}</div>
                                <div class="text-sm text-green-800">Datatable Components</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="text-purple-600 font-semibold text-lg">{{ count(glob(resource_path('views/components/input/*.blade.php'))) }}</div>
                                <div class="text-sm text-purple-800">Input Components</div>
                            </div>
                            <div class="bg-orange-50 rounded-lg p-4">
                                <div class="text-orange-600 font-semibold text-lg">{{ count(glob(resource_path('views/components/icons/*.blade.php'))) }}</div>
                                <div class="text-sm text-orange-800">Icon Components</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Start Guide --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Quick Start Guide</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Basic Usage</h4>
                            <pre class="text-sm text-gray-700 bg-white rounded border p-3 overflow-x-auto"><code>&lt;x-button color="primary" size="md"&gt;
    Click Me
&lt;/x-button&gt;</code></pre>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">With Props</h4>
                            <pre class="text-sm text-gray-700 bg-white rounded border p-3 overflow-x-auto"><code>&lt;x-table 
    :headers="$headers"
    :rows="$data"
    :selectable="true"
    :sortable="true"
/&gt;</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tables & Data Tab --}}
        @if($activeTab === 'tables')
            <div class="space-y-8">
                {{-- Basic Table Example --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Basic Table Component</h3>
                    <p class="text-gray-600 mb-6">Responsive table with sorting, selection, and custom styling.</p>
                    
                    {{-- Table Filters --}}
                    <x-datatable.filters 
                        :per-page="$perPage"
                        :pagination-options="[5, 10, 25, 50]"
                        :selected-count="count($selectedItems)"
                        :search="$search"
                        wire:model.live.debounce.300ms="search"
                        wire:model.live="perPage"
                    />

                    {{-- Users Table --}}
                    <div class="mt-6">
                        <x-table class="w-full">
                            <x-slot name="header">
                                <x-table.th class="w-12">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </x-table.th>
                                <x-table.th sortable="true">Name</x-table.th>
                                <x-table.th sortable="true">Email</x-table.th>
                                <x-table.th>Role</x-table.th>
                                <x-table.th>Status</x-table.th>
                                <x-table.th>Created</x-table.th>
                                <x-table.th class="w-24">Actions</x-table.th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($sampleUsers->take($perPage) as $user)
                                    <x-table.tr>
                                        <x-table.td>
                                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $user->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </x-table.td>
                                        <x-table.td>
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        </x-table.td>
                                        <x-table.td>
                                            <div class="text-gray-500">{{ $user->email }}</div>
                                        </x-table.td>
                                        <x-table.td>
                                            <x-badge color="blue">{{ $user->role }}</x-badge>
                                        </x-table.td>
                                        <x-table.td>
                                            <x-status-badge :status="$user->status" />
                                        </x-table.td>
                                        <x-table.td>
                                            <div class="text-sm text-gray-500">{{ $user->created_at->format('M j, Y') }}</div>
                                        </x-table.td>
                                        <x-table.td>
                                            <x-datatable.actions :actions="[
                                                [
                                                    'type' => 'button',
                                                    'label' => 'Edit',
                                                    'icon' => 'heroicon-o-pencil',
                                                    'color' => 'primary',
                                                    'wire:click' => 'editItem(' . $user->id . ')'
                                                ],
                                                [
                                                    'type' => 'button',
                                                    'label' => 'Delete',
                                                    'icon' => 'heroicon-o-trash',
                                                    'color' => 'danger',
                                                    'wire:click' => 'deleteItem(' . $user->id . ')'
                                                ]
                                            ]" />
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>

                    {{-- Code Example --}}
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Code Example</h4>
                        <pre class="text-sm text-gray-700 bg-white rounded border p-3 overflow-x-auto"><code>&lt;x-datatable.filters 
    :per-page="$perPage"
    :pagination-options="[5, 10, 25, 50]"
    :selected-count="count($selectedItems)"
    wire:model.live="perPage"
/&gt;

&lt;x-table&gt;
    &lt;x-slot name="header"&gt;
        &lt;x-table.th&gt;Name&lt;/x-table.th&gt;
        &lt;x-table.th&gt;Email&lt;/x-table.th&gt;
        &lt;x-table.th&gt;Status&lt;/x-table.th&gt;
    &lt;/x-slot&gt;
    &lt;x-slot name="body"&gt;
        @foreach($users as $user)
            &lt;x-table.tr&gt;
                &lt;x-table.td&gt;{{ $user->name }}&lt;/x-table.td&gt;
                &lt;x-table.td&gt;{{ $user->email }}&lt;/x-table.td&gt;
                &lt;x-table.td&gt;
                    &lt;x-status-badge :status="$user->status" /&gt;
                &lt;/x-table.td&gt;
            &lt;/x-table.tr&gt;
        @endforeach
    &lt;/x-slot&gt;
&lt;/x-table&gt;</code></pre>
                    </div>
                </div>

                {{-- Product Filters Example --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Product Datatable Filters</h3>
                    <p class="text-gray-600 mb-6">Specialized filters for product listings with category and availability options.</p>
                    
                    <x-datatable.product-filters 
                        :per-page="$perPage"
                        :pagination-options="[6, 12, 24, 48]"
                        :search="$search"
                        :categories="$sampleCategories"
                        :selected-category="$selectedCategory"
                        :availability="$availability"
                        :seasonality="$seasonality"
                        :selected-count="count($selectedItems)"
                        :can-delete="true"
                        wire:model.live.debounce.300ms="search"
                        wire:model.live="perPage"
                        wire:model.live="selectedCategory"
                        wire:model.live="availability"
                        wire:model.live="seasonality"
                    />

                    {{-- Products Grid --}}
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($sampleProducts->take(6) as $product)
                            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-900 mb-2">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-500 mb-2">SKU: {{ $product->sku }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    <x-status-badge :status="$product->status" />
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                                    <x-badge color="gray">{{ $product->category }}</x-badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Forms & Inputs Tab --}}
        @if($activeTab === 'forms')
            <div class="space-y-8">
                {{-- Input Components --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Input Components</h3>
                    <p class="text-gray-600 mb-6">Various input types with validation and styling.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Text Input --}}
                        <div>
                            <x-input.text 
                                label="Full Name"
                                placeholder="Enter your full name"
                                wire:model="search"
                                required
                            />
                        </div>
                        
                        {{-- Email Input --}}
                        <div>
                            <x-input.text 
                                type="email"
                                label="Email Address"
                                placeholder="Enter your email"
                                wire:model="search"
                            />
                        </div>
                        
                        {{-- Select Input --}}
                        <div>
                            <x-input.select 
                                label="Category"
                                wire:model="selectedCategory"
                                :options="$sampleCategories->pluck('name', 'id')->toArray()"
                                placeholder="Select a category"
                            />
                        </div>
                        
                        {{-- Date Input --}}
                        <div>
                            <x-input.date 
                                label="Date of Birth"
                                wire:model="search"
                            />
                        </div>
                        
                        {{-- Textarea --}}
                        <div class="md:col-span-2">
                            <x-input.textarea 
                                label="Description"
                                placeholder="Enter description..."
                                rows="4"
                                wire:model="search"
                            />
                        </div>
                        
                        {{-- Money Input --}}
                        <div>
                            <x-input.money 
                                label="Price"
                                placeholder="0.00"
                                wire:model="search"
                            />
                        </div>
                        
                        {{-- File Upload --}}
                        <div>
                            <x-input.file-upload 
                                label="Profile Image"
                                accept="image/*"
                                wire:model="search"
                            />
                        </div>
                    </div>
                    
                    {{-- Checkbox and Toggle --}}
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center space-x-4">
                            <x-input.checkbox 
                                label="I agree to the terms and conditions"
                                wire:model="search"
                            />
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <x-toggle-switch 
                                label="Enable notifications"
                                wire:model="search"
                            />
                        </div>
                    </div>
                </div>

                {{-- Form Example --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Complete Form Example</h3>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-input.text label="First Name" placeholder="John" required />
                            <x-input.text label="Last Name" placeholder="Doe" required />
                        </div>
                        
                        <x-input.text type="email" label="Email" placeholder="john@example.com" required />
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-input.select 
                                label="Department"
                                :options="['sales' => 'Sales', 'marketing' => 'Marketing', 'support' => 'Support']"
                                placeholder="Select department"
                            />
                            <x-input.date label="Start Date" />
                        </div>
                        
                        <x-input.textarea label="Bio" placeholder="Tell us about yourself..." rows="3" />
                        
                        <div class="flex items-center justify-between pt-4">
                            <x-input.checkbox label="Send welcome email" />
                            <div class="flex space-x-3">
                                <x-button color="secondary">Cancel</x-button>
                                <x-button color="primary" type="submit">Save User</x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Modals & Dialogs Tab --}}
        @if($activeTab === 'modals')
            <div class="space-y-8">
                {{-- Modal Examples --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Modal Components</h3>
                    <p class="text-gray-600 mb-6">Various modal types for different use cases.</p>
                    
                    <div class="flex flex-wrap gap-4">
                        <x-button wire:click="toggleModal" color="primary">
                            Open Standard Modal
                        </x-button>
                        
                        <x-button wire:click="toggleDeleteModal" color="danger">
                            Open Confirmation Modal
                        </x-button>
                        
                        <x-button wire:click="simulateLoading" color="secondary">
                            Simulate Loading
                        </x-button>
                    </div>
                </div>

                {{-- Dialog Examples --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Dialog Components</h3>
                    <p class="text-gray-600 mb-6">Simple dialogs for quick interactions.</p>
                    
                    <div class="space-y-4">
                        <x-dialog>
                            <x-slot name="trigger">
                                <x-button color="primary">Open Dialog</x-button>
                            </x-slot>
                            
                            <x-slot name="content">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dialog Title</h3>
                                    <p class="text-gray-600">This is a simple dialog content example.</p>
                                </div>
                            </x-slot>
                        </x-dialog>
                    </div>
                </div>
            </div>
        @endif

        {{-- Navigation Tab --}}
        @if($activeTab === 'navigation')
            <div class="space-y-8">
                {{-- Breadcrumb Example --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Breadcrumb Navigation</h3>
                    <x-breadcrumb :items="[
                        ['label' => 'Home', 'url' => '#'],
                        ['label' => 'Documentation', 'url' => '#'],
                        ['label' => 'Components', 'url' => '#'],
                        ['label' => 'Navigation']
                    ]" />
                </div>

                {{-- Tabs Example --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Tab Navigation</h3>
                    <x-tabs :tabs="[
                        'overview' => 'Overview',
                        'details' => 'Details',
                        'settings' => 'Settings'
                    ]" active="overview">
                        <x-slot name="overview">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p>Overview content goes here...</p>
                            </div>
                        </x-slot>
                        <x-slot name="details">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p>Details content goes here...</p>
                            </div>
                        </x-slot>
                        <x-slot name="settings">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p>Settings content goes here...</p>
                            </div>
                        </x-slot>
                    </x-tabs>
                </div>

                {{-- Dropdown Example --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Dropdown Navigation</h3>
                    <div class="flex space-x-4">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <x-button color="secondary">
                                    Options
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </x-button>
                            </x-slot>
                            
                            <x-slot name="content">
                                <x-dropdown.item href="#">Profile</x-dropdown.item>
                                <x-dropdown.item href="#">Settings</x-dropdown.item>
                                <x-dropdown.item href="#" class="text-red-600">Logout</x-dropdown.item>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        @endif

        {{-- Feedback & Status Tab --}}
        @if($activeTab === 'feedback')
            <div class="space-y-8">
                {{-- Status Badges --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Status Badges</h3>
                    <p class="text-gray-600 mb-6">Various status indicators and badges.</p>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Basic Status Badges</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-status-badge status="active" />
                                <x-status-badge status="inactive" />
                                <x-status-badge status="pending" />
                                <x-status-badge status="completed" />
                                <x-status-badge status="cancelled" />
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Colored Badges</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-badge color="blue">Information</x-badge>
                                <x-badge color="green">Success</x-badge>
                                <x-badge color="yellow">Warning</x-badge>
                                <x-badge color="red">Error</x-badge>
                                <x-badge color="gray">Neutral</x-badge>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Count Badges</h4>
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700">Messages</span>
                                    <x-badge color="red">5</x-badge>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700">Notifications</span>
                                    <x-badge color="blue">12</x-badge>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700">Tasks</span>
                                    <x-badge color="green">3</x-badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Alert Components</h3>
                    <div class="space-y-4">
                        <x-alert type="info" title="Information">
                            This is an informational alert message.
                        </x-alert>
                        
                        <x-alert type="success" title="Success">
                            Your action was completed successfully!
                        </x-alert>
                        
                        <x-alert type="warning" title="Warning">
                            Please review the following information carefully.
                        </x-alert>
                        
                        <x-alert type="error" title="Error">
                            An error occurred while processing your request.
                        </x-alert>
                    </div>
                </div>

                {{-- Loading States --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Loading Components</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Spinners</h4>
                            <div class="flex items-center space-x-6">
                                <x-spinner.index size="sm" />
                                <x-spinner.index size="md" />
                                <x-spinner.index size="lg" />
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Loading Mask</h4>
                            <div class="relative bg-gray-50 rounded-lg p-8">
                                <p class="text-gray-600">Content area with loading overlay</p>
                                @if($loading)
                                    <x-loading-mask />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Layout & Structure Tab --}}
        @if($activeTab === 'layout')
            <div class="space-y-8">
                {{-- Card Components --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Card Components</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <x-card>
                            <x-slot name="header">
                                <h4 class="font-medium text-gray-900">Basic Card</h4>
                            </x-slot>
                            <p class="text-gray-600">This is a basic card component with header and content.</p>
                        </x-card>
                        
                        <x-card>
                            <x-slot name="header">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-900">Card with Actions</h4>
                                    <x-button size="sm" color="secondary">Edit</x-button>
                                </div>
                            </x-slot>
                            <p class="text-gray-600">Card with action button in header.</p>
                            <x-slot name="footer">
                                <div class="flex justify-end space-x-2">
                                    <x-button size="sm" color="secondary">Cancel</x-button>
                                    <x-button size="sm" color="primary">Save</x-button>
                                </div>
                            </x-slot>
                        </x-card>
                        
                        <x-counter-card 
                            title="Total Users"
                            :count="$sampleUsers->count()"
                            icon="heroicon-o-users"
                            color="blue"
                        />
                    </div>
                </div>

                {{-- Page Container --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Page Container</h3>
                    <p class="text-gray-600 mb-6">Standardized page layout with proper spacing and responsive design.</p>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <x-page-container>
                            <x-slot name="header">
                                <div class="flex items-center justify-between">
                                    <h1 class="text-2xl font-bold text-gray-900">Page Title</h1>
                                    <x-button color="primary">Primary Action</x-button>
                                </div>
                            </x-slot>
                            
                            <div class="bg-white rounded-lg border p-6">
                                <p class="text-gray-600">Page content goes here...</p>
                            </div>
                        </x-page-container>
                    </div>
                </div>
            </div>
        @endif

        {{-- Interactive Elements Tab --}}
        @if($activeTab === 'interactive')
            <div class="space-y-8">
                {{-- Button Components --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Button Components</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Button Colors</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-button color="primary">Primary</x-button>
                                <x-button color="secondary">Secondary</x-button>
                                <x-button color="success">Success</x-button>
                                <x-button color="warning">Warning</x-button>
                                <x-button color="danger">Danger</x-button>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Button Sizes</h4>
                            <div class="flex items-center gap-3">
                                <x-button size="xs" color="primary">Extra Small</x-button>
                                <x-button size="sm" color="primary">Small</x-button>
                                <x-button size="md" color="primary">Medium</x-button>
                                <x-button size="lg" color="primary">Large</x-button>
                                <x-button size="xl" color="primary">Extra Large</x-button>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Button Variants</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-button.icon icon="heroicon-o-plus" color="primary">With Icon</x-button.icon>
                                <x-button.link href="#" color="primary">Link Button</x-button.link>
                                <x-button color="primary" disabled>Disabled</x-button>
                                <x-button color="primary" loading>Loading</x-button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Interactive Elements --}}
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Interactive Elements</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Accordion</h4>
                            <x-accordion>
                                <x-slot name="items">
                                    @php
                                        $accordionItems = [
                                            ['title' => 'What is MyStockMaster?', 'content' => 'MyStockMaster is a comprehensive inventory management system.'],
                                            ['title' => 'How do I get started?', 'content' => 'You can get started by creating your first product and category.'],
                                            ['title' => 'Is there a mobile app?', 'content' => 'Currently, MyStockMaster is a web-based application optimized for mobile browsers.']
                                        ];
                                    @endphp
                                    @foreach($accordionItems as $item)
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ $item['title'] }}</h5>
                                            <p class="text-gray-600 mt-2">{{ $item['content'] }}</p>
                                        </div>
                                    @endforeach
                                </x-slot>
                            </x-accordion>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Chips/Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                <x-chips>Electronics</x-chips>
                                <x-chips>Kitchen</x-chips>
                                <x-chips>Office</x-chips>
                                <x-chips removable>Removable Tag</x-chips>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Standard Modal --}}
    <x-modal :show="$showModal" max-width="lg">
        <x-slot name="title">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Standard Modal Example</span>
            </div>
        </x-slot>
        
        <x-slot name="content">
            <div class="space-y-4">
                <p class="text-gray-600">This is a standard modal component with customizable content, header, and footer.</p>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Modal Features:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Responsive design</li>
                        <li>• Focus management</li>
                        <li>• Keyboard navigation</li>
                        <li>• Backdrop click to close</li>
                        <li>• Customizable animations</li>
                    </ul>
                </div>
                
                <x-input.text label="Sample Input" placeholder="Type something..." />
            </div>
        </x-slot>
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-button wire:click="toggleModal" color="secondary">Close</x-button>
                <x-button color="primary">Save Changes</x-button>
            </div>
        </x-slot>
    </x-modal>

    {{-- Confirmation Modal --}}
    <x-modal.confirmation 
        :show="$showDeleteModal"
        title="Delete Confirmation"
        message="Are you sure you want to delete this item? This action cannot be undone."
        confirmText="Delete"
        cancelText="Cancel"
        wire:confirm="deleteItem"
        wire:cancel="toggleDeleteModal"
    />
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('loading-complete', () => {
            setTimeout(() => {
                @this.set('loading', false);
            }, 2000);
        });
    });
</script>
@endpush