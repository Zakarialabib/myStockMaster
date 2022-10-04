<x-app-layout>

    @section('title', 'Print Barcode')

    @section('breadcrumb')
        <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Print Barcode') }}</li>
        </ol>
    @endsection

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <livewire:search-product />
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <livewire:products.barcode-page />
            </div>
        </div>
    </div>
</x-app-layout>
