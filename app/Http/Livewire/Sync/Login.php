<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sync;

use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Login extends Component
{
    use LivewireAlert;

    public $loginModal = false;

    public $email;
    public $password;

    public $url;

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required',
    ];

    public function mount()
    {
        $this->url = settings()->custom_store_url;
    }

    public function loginModal()
    {
        $this->loginModal = true;
    }

    public function authenticate()
    {
        $this->validate();

        $client = new Client();

        $response = $client->request('POST', $this->url.'/api/login', [
            'headers' => [
                'Accept'           => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            'json' => [
                'email'    => $this->email,
                'password' => $this->password,
            ],
        ]);

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $data = json_decode($response->getBody(), true);
            $ecommerceToken = $data['api_token'];

            settings()->update([
                'custom_store_url'  => $this->url,
                'custom_api_key'    => $ecommerceToken,
                'custom_api_secret' => 'your-secret-value', // replace with your own secret value
                'custom_last_sync'  => null, // set to null initially
                'custom_products'   => null, // set to null initially
            ]);

            $this->alert('success', __('Authentication successful !'));
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
