<?php

namespace App\Http\Livewire\Sync;

use GuzzleHttp\Client;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class Login extends Component
{
    use LivewireAlert;

    public $loginModal = false;

    /** @var array<string> */
    public $listeners = ['loginModal'];

    public $email;
    public $password;

    public $store_url;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
        'store_url' => 'required',
    ];

    public $type;

    public function updatedType(): void
    {
        if ($this->type === 'woocommerce') {
            $this->store_url = settings()->woocommerce_store_url;
        } elseif ($this->type === 'shopify') {
            $this->store_url = settings()->shopify_store_url;
        } elseif ($this->type === 'custom') {
            $this->store_url = settings()->custom_store_url;
        }
    }

    public function loginModal()
    {
        $this->loginModal = true;
    }

    public function authenticate()
    {
        $this->validate();

        $client = new Client();

        $response = $client->request('POST', $this->store_url.'/api/login', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'json' => [
                'email' => $this->email,
                'password' => $this->password,
            ],
        ]);

        if ($response->getStatusCode() === Response::HTTP_OK) {

            $data = json_decode($response->getBody(), true);
            $ecommerceToken = $data['api_token'];

            settings()->update([
                'custom_store_url' => $this->store_url,
                'custom_api_key' => $ecommerceToken,
                'custom_api_secret' => null, // replace with your own secret value
                'custom_last_sync' => null, // set to null initially
                'custom_products' => null, // set to null initially
            ]);
            $this->alert('success', __('Authentication successful !'));
            $this->emit('refreshIndex');
            $this->loginModal = false;
        } else {
            $this->alert('error', __('Authentication failed !'));
        }

    }

    public function render()
    {
        return view('livewire.sync.login');
    }
}
