<?php

declare(strict_types=1);

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Messaging extends Component
{
    use LivewireAlert;

    public $botToken;
    public $chatId;
    public $message;
    public $type;
    public $sale_id;
    public $product_id;
    public $selectedProduct;
    public $openTemplate;
    public $openProductModal;
    public $openClientModal;

    protected $rules = [
        'chatId'  => 'required|numeric',
        'message' => 'required',
    ];

    public function mount()
    {
        $this->botToken = settings()->telegram_channel;
    }

    public function getProductsProperty()
    {
        return Product::select('id', 'name', 'price', 'image')->take(10)->get();
    }

    public function getCustomersProperty()
    {
        return Customer::select('id', 'name', 'phone')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    public function getSalesProperty()
    {
        return Sale::select('id', 'customer_id', 'due_amount')
            ->where('due_amount', '>', 0)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    public function updatedType()
    {
        $this->chatId = '';
    }

    public function fillMessage($template)
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

    public function sendDueAmount($saleId)
    {
        $sale = Sale::find($saleId);

        if ( ! $sale) {
            $this->alert('error', __('Sale not found'));

            return;
        }

        $message = "Due Amount for Sale {$sale->id}: ".format_currency($sale->due_amount);

        $this->chatId = settings()->telegram_channel; // Use your Telegram channel chat ID here
        $this->message = $message;
        $this->type = 'telegram';

        // Send the message using the existing sendMessage method
        $this->sendMessage();
    }

    public function openProductModal()
    {
        $this->openProductModal = true;
    }

    public function openClientModal()
    {
        $this->openClientModal = true;
    }

    public function openTemplate()
    {
        $this->openTemplate = true;
    }

    public function insertProduct($id)
    {
        $product = Product::find($id);

        if ( ! $product) {
            $this->alert('error', __('Product not found'));

            return;
        }
        $this->message .= ' '.$product->name.' : '.format_currency($product->price);
        $this->openProductModal = false;
    }

    public function insertSale($id)
    {
        $sale = Sale::find($id);

        if ( ! $sale) {
            $this->alert('error', __('Sale not found'));

            return;
        }
        $this->message .= ' '.$sale->id.' : '.format_currency($sale->due_amount);
        $this->openProductModal = false;
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);

        // Get the customer's phone number
        $phone = $customer->phone;

        // Delete the leading zero from the phone number, if it exists.
        if (strpos($phone, '0') === 0) {
            $phone = substr($phone, 1);
        }

        $this->chatId = $phone;

        $this->openClientModal = false;
    }

    public function sendMessage()
    {
        $message = urlencode($this->message);

        // Construct the WhatsApp API endpoint URL or the Telegram API endpoint URL
        if ($this->type == 'whatsapp') {
            $url = "https://web.whatsapp.com/send/?phone={$this->chatId}&text={$message}";
            // emit url to the view in order to open the link in a new tab
            $this->emit('openUrl', $url);
        } elseif ($this->type == 'telegram') {
            $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage?chat_id={$this->chatId}&text={$message}";

            $response = Http::post($url);

            dd($response);

            // Check if the API call was successful
            if ( ! $response->ok()) {
                // Handle error if the message couldn't be sent
                $this->alert('error', __('Failed to send message to channel'));
            }
        }

        // Clear the inputs after sending the message
        $this->reset(['message', 'type', 'product_id', 'sale_id']);

        $this->alert('success', __('Message sent successfully'));
    }

    public function render()
    {
        return view('livewire.settings.messaging');
    }
}
