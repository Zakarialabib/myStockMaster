<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Messaging extends Component
{
    use WithAlert;

    public ?string $botToken = null;

    #[Validate('required|numeric')]
    public ?string $chatId = null;

    #[Validate('required|min:1|max:1000')]
    public ?string $message = null;

    public ?string $type = null;

    public ?int $sale_id = null;

    public ?int $product_id = null;

    public ?string $whatsapp_custom_message = null;

    public ?string $selectedProduct = null;

    public bool $openTemplate = false;

    public bool $openProductModal = false;

    public bool $openClientModal = false;

    public function mount(): void
    {
        $this->botToken = settings()?->telegram_channel;
        $this->whatsapp_custom_message = settings()?->whatsapp_custom_message;
    }

    public function updatedWhatsappCustomMessage(): void
    {
        \App\Models\Setting::set('whatsapp_custom_message', $this->whatsapp_custom_message);
        $this->alert('success', __('Settings updated successfully!'));
    }

    #[Computed]
    public function products()
    {
        return Product::query()->select('id', 'name', 'image')->take(10)->get();
    }

    #[Computed]
    public function customers()
    {
        return Customer::query()->select('id', 'name', 'phone')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    #[Computed]
    public function sales()
    {
        return Sale::query()->select('id', 'customer_id', 'due_amount')
            ->where('due_amount', '>', 0)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    #[On('type-updated')]
    public function updatedType(): void
    {
        $this->chatId = '';
    }

    public function fillMessage(mixed $template): void
    {
        switch ($template) {
            case 'productMessage':
                // Fill in product information message
                $this->message = 'Information: ...'; // replace ... with actual product information
                $this->openTemplate = false;
                $this->openProductModal = true;

                break;
            case 'clientMessage':
                // Fill in sale due amount message
                $this->message = 'Sale Due Amount: ...'; // replace ... with actual sale due amount
                $this->openTemplate = false;

                break;
            default:
                // Empty message template
                $this->message = '';
                $this->openTemplate = false;

                break;
        }
    }

    public function sendDueAmount(mixed $saleId): void
    {
        $sale = Sale::query()->findOrFail($saleId);

        $message = sprintf('Due Amount for Sale %s: ', $sale->id) . format_currency($sale->due_amount);

        $this->chatId = settings()->telegram_channel;
        $this->message = $message;
        $this->type = 'telegram';

        $this->sendMessage();
    }

    public function openProductModal(): void
    {
        $this->openProductModal = true;
    }

    public function openClientModal(): void
    {
        $this->openClientModal = true;
    }

    public function openTemplate(): void
    {
        $this->openTemplate = true;
    }

    public function insertProduct(mixed $id): void
    {
        $product = Product::query()->findOrFail($id);

        $this->message .= ' ' . $product->name . ' : ' . format_currency($product->price);
        $this->openProductModal = false;
    }

    public function insertSale(mixed $id): void
    {
        $sale = Sale::query()->findOrFail($id);

        $this->message .= ' ' . $sale->id . ' : ' . format_currency($sale->due_amount);
        $this->openProductModal = false;
    }

    public function selectCustomer(mixed $customerId): void
    {
        $customer = Customer::query()->findOrFail($customerId);

        $phone = $customer->phone;

        // Delete the leading zero from the phone number, if it exists.
        if (str_starts_with((string) $phone, '0')) {
            $phone = substr((string) $phone, 1);
        }

        $this->chatId = $phone;

        $this->openClientModal = false;
    }

    public function sendMessage(): void
    {
        $message = urlencode((string) $this->message);

        // Construct the WhatsApp API endpoint URL or the Telegram API endpoint URL
        if ($this->type == 'whatsapp') {
            $url = sprintf('https://web.whatsapp.com/send/?phone=%s&text=%s', $this->chatId, $message);
            // emit url to the view in order to open the link in a new tab
            $this->dispatch('openUrl', $url);
        } elseif ($this->type == 'telegram') {
            $url = sprintf('https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s', $this->botToken, $this->chatId, $message);

            $response = Http::post($url);

            // Check if the API call was successful
            if (! $response->ok()) {
                // Handle error if the message couldn't be sent
                $this->alert('error', __('Failed to send message to channel'));
            }
        }

        // Clear the inputs after sending the message
        $this->reset(['message', 'type', 'product_id', 'sale_id']);

        $this->alert('success', __('Message sent successfully'));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.settings.messaging');
    }
}
