<?php

declare(strict_types=1);

namespace App\Http\Livewire\Sync;

use App\Enums\IntegrationType;
use App\Models\Integration;
use GuzzleHttp\Client;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Login extends Component
{
    use LivewireAlert;

    public $loginModal = false;

    /** @var array<string> */
    public $listeners = ['loginModal'];

    public $email;
    public $password;

    public $store_url;
    public $type;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
        'store_url' => 'required',
    ];

    public function loginModal()
    {
        $this->loginModal = true;
    }

    public function authenticate()
    {
        $this->validate();

        try {
            $client = new Client();

            if ($this->type === IntegrationType::CUSTOM) {
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
                }
            } elseif ($this->type === IntegrationType::YOUCAN) {
                $response = $client->post(
                    'https://seller-area.youcan.shop/admin/oauth/token',
                    [
                        'form_params' => [
                            'grant_type' => 'authorization_code',
                            'client_id' => 1,
                            'client_secret' => '<CLIENT SECRET>',
                            'redirect_uri' => 'https://myapp.com/callback',
                            'code' => $this->get('code'),
                        ],
                        'http_errors' => false,
                    ]
                );

                if ($response->getStatusCode() === Response::HTTP_OK) {
                    $data = json_decode($response->getBody(), true);
                    $ecommerceToken = $data['access_token'];
                }
            }

            $integration = Integration::where('type', $this->type)->first();

            if ($integration) {
                // If integration exists, update its attributes
                $integration->update([
                    'store_url' => $this->store_url,
                    'api_key' => $ecommerceToken,
                    'api_secret' => null, // replace with your own secret value
                    'last_sync' => null, // set to null initially
                    'products' => null, // set to null initially
                ]);
            } else {
                // Otherwise, create a new integration with the given attributes
                Integration::create([
                    'type' => $this->type,
                    'store_url' => $this->store_url,
                    'api_key' => $ecommerceToken,
                    'api_secret' => null, // replace with your own secret value
                    'last_sync' => null, // set to null initially
                    'products' => null, // set to null initially
                    'status' => true, // or any other default status
                ]);
            }

            $this->alert('success', __('Authentication successful !'));

            $this->emit('refreshIndex');

            $this->loginModal = false;
        } catch (Throwable $th) {
            $this->alert('success', __('Authentication failed !'.$th->getMessage()));
        }
    }

    public function render()
    {
        return view('livewire.sync.login');
    }
}
