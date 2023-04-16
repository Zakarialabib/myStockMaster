<?php

declare(strict_types=1);

namespace App\Http\Livewire\Settings;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Str;

class Index extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $settings;

    /** @var array<string> */
    public $listeners = ['update'];

    public $listsForFields = [];

    public $company_logo;

    public $invoice_header;
    public $invoice_footer;

    public $image;

    /** @var array */
    protected $rules = [
        'settings.company_name'              => 'required|string|min:1|max:255',
        'settings.company_email'             => 'required|string|min:1|max:255',
        'settings.company_phone'             => 'required|string|min:1|max:255',
        'settings.company_logo'              => 'nullable|string|min:0|max:255',
        'settings.company_address'           => 'required|string|min:1|max:255',
        'settings.company_tax'               => 'nullable|string|min:0|max:255',
        'settings.telegram_channel'          => 'nullable|string|min:0|max:255',
        'settings.default_currency_id'       => 'required|integer|min:0|max:192',
        'settings.default_currency_position' => 'required|string|min:1|max:255',
        'settings.default_date_format'       => 'required|string|min:1|max:255',
        'settings.default_client_id'         => 'nullable|integer|min:0|max:192',
        'settings.default_warehouse_id'      => 'nullable|integer|min:0|max:192',
        'settings.default_language'          => 'required|string|min:1|max:255',
        'settings.invoice_footer_text'       => 'nullable',
        'settings.invoice_header'            => 'nullable|string|min:0|max:255',
        'settings.invoice_footer'            => 'nullable|string|min:0|max:255',
        'settings.sale_prefix'               => 'nullable',
        'settings.saleReturn_prefix'         => 'nullable',
        'settings.purchase_prefix'           => 'nullable',
        'settings.purchaseReturn_prefix'     => 'nullable',
        'settings.quotation_prefix'          => 'nullable',
        'settings.salePayment_prefix'        => 'nullable',
        'settings.purchasePayment_prefix'    => 'nullable',
        'settings.is_rtl'                    => 'boolean',
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
        abort_if(Gate::denies('setting_access'), 403);

        $settings = Setting::firstOrFail();

        $this->settings = $settings;

        $this->initListsForFields();
    }

    public function update(): void
    {
        $this->validate();

        if ($this->company_logo) {
            $imageName = Str::slug($this->settings->company_name).'.'.$this->company_logo->extension();
            $this->company_logo->storeAs('uploads', $imageName, 'public');
            $this->company_logo = $imageName;
        }

        if ($this->invoice_header) {
            $imageName = 'invoice-header';
            $this->invoice_header->storeAs('uploads', $imageName, 'public');
            $this->createHTMLfile($this->invoice_header, $imageName);
            $this->settings->invoice_header = $imageName;
        }

        if ($this->invoice_footer) {
            $imageName = 'invoice-footer';
            $this->invoice_footer->storeAs('uploads', $imageName, 'public');
            $this->createHTMLfile($this->invoice_footer, $imageName);
            $this->settings->invoice_footer = $imageName;
        }

        $this->settings->save();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully !'));
    }

    protected function createHTMLfile($file, $name)
    {
        $extension = $file->extension();
        $data = File::get($file->getRealPath());
        $base64 = 'data:image/'.$extension.';base64,'.base64_encode($data);

        $html = sprintf(
            '<div><img style="width: 100%%; display: block;" src="%s"></div>',
            $base64
        );

        $path = public_path('print/'.$name.'.html');
        File::put($path, $html);

        return $base64;
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['currencies'] = Currency::pluck('name', 'id')->toArray();
        $this->listsForFields['warehouses'] = Warehouse::pluck('name', 'id')->toArray();
        $this->listsForFields['customers'] = Customer::pluck('name', 'id')->toArray();
    }
}
