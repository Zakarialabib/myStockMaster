<?php

declare(strict_types=1);

namespace App\Http\Livewire\Settings;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Str;

class Index extends Component
{
    use LivewireAlert;

    public $settings;

    /** @var string[] $listeners */
    public $listeners = ['update'];

    public array $listsForFields = [];

    public $site_logo;

    public array $rules = [
        'settings.company_name'              => 'required|string|min:1|max:255',
        'settings.company_email'             => 'required|string|min:1|max:255',
        'settings.company_phone'             => 'required|string|min:1|max:255',
        'settings.site_logo'                 => 'nullable|string|min:0|max:255',
        'settings.default_currency_id'       => 'required|integer|min:0|max:4294967295',
        'settings.default_currency_position' => 'required|string|min:1|max:255',
        'settings.notification_email'        => 'required|string|min:1|max:255',
        'settings.company_address'           => 'required|string|min:1|max:255',
        'settings.default_client_id'         => 'nullable|integer|min:0|max:4294967295',
        'settings.default_warehouse_id'      => 'nullable|integer|min:0|max:4294967295',
        'settings.default_language'          => 'required|string|min:1|max:255',
        'settings.is_invoice_footer'         => 'boolean',
        'settings.invoice_footer'            => 'nullable|string|min:0|max:255',
        'settings.company_tax'               => 'nullable|string|min:0|max:255',
        'settings.sale_prefix'               => 'nullable',
        'settings.purchase_prefix'           => 'nullable',
        'settings.quotation_prefix'          => 'nullable',
        'settings.salepayment_prefix'        => 'nullable',
        'settings.purchasepayment_prefix'    => 'nullable',
        'settings.is_rtl'                    => 'boolean',
        'settings.invoice_prefix'            => 'required|string|min:1|max:255',
        'settings.show_email'                => 'boolean',
        'settings.show_address'              => 'boolean',
        'settings.show_order_tax'            => 'boolean',
        'settings.show_discount'             => 'boolean',
        'settings.show_shipping'             => 'boolean',
    ];

    public function render()
    {
        return view('livewire.settings.index');
    }

    public function mount(): void
    {
        abort_if(Gate::denies('access_settings'), 403);

        $settings = Setting::firstOrFail();

        $this->settings = $settings;

        $this->initListsForFields();
    }

    public function update(): void
    {
        $this->validate();

        if ($this->site_logo != null) {
            $imageName = Str::slug($this->company_name).'.'.$this->image->extension();
            $this->image->storeAs('settings', $imageName);
            $this->site_logo = $imageName;
        }

        $this->settings->save();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully !'));
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['currencies'] = Currency::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
        $this->listsForFields['customers'] = Customer::pluck('name', 'id')->toArray();
    }
}
