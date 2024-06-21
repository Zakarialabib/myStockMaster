<?php

declare(strict_types=1);

namespace App\Livewire\Sync;

use App\Models\Integration;
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

    public $type;

    protected $rules = [
        'email'     => 'required|email',
        'password'  => 'required',
        'store_url' => 'required',
        'type'      => 'required',
    ];

    public function loginModal(): void
    {
        $this->loginModal = true;
    }

    public function authenticate(): void
    {
        $this->validate();

        $client = new Client();

        $response = $client->request('POST', $this->store_url.'/api/login', [
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
            $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $ecommerceToken = $data['api_token'];

            $integration = Integration::firstOrNew(['type' => $this->type]);
            $integration->fill([
                'store_url'  => $this->store_url,
                'api_key'    => $ecommerceToken,
                'api_secret' => $ecommerceToken,
                'last_sync'  => null, // set to null initially
                'products'   => null, // set to null initially
                'status'     => true, // or any other default status
            ])->save();

            $this->alert('success', __('Authentication successful !'));

            // $this->dispatch('refreshIndex')->to(Index::class);

            $this->loginModal = false;
        }
    }

    public function render()
    {
        return view('livewire.sync.login');
    }
}
