<?php

declare(strict_types=1);

namespace App\Livewire\Customers;

use App\Models\Customer;
use Google\Client;
use Google\Service\PeopleService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Throwable;

class GoogleContact extends Component
{
    public $contacts = [];

    public $contactService;

    public $showContacts = false;

    public function showContacts(): void
    {
        $this->showContacts = true;
        // Fetch Google Contacts
        $this->fetchContacts();
    }

    public function fetchContacts(): void
    {
        // Initialize the Google API Client
        $client = new Client();
        $client->setApplicationName('Laravel');
        $client->setDeveloperKey(env('GOOGLE_SERVER_KEY'));
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        // $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $client->setAccessToken(Cache::get('google_access_token'));

        // Initialize the People API Service
        $this->contactService = new PeopleService($client);

        // Fetch Google Contacts
        $this->contacts = $this->listContacts();
    }

    public function listContacts(): array
    {
        $connections = [];

        // Get the first page of contacts
        $contactList = $this->contactService->people_connections->listPeopleConnections('people/me', [
            'personFields' => 'names,emailAddresses,phoneNumbers,birthdays',
        ]);

        // Add the contacts from the first page to the list
        $connections = $contactList->getConnections();

        // Check if there is a next page of contacts
        while ($contactList->getNextPageToken()) {
            // Get the next page of contacts
            $contactList = $this->contactService->people_connections->listPeopleConnections('people/me', [
                'personFields' => 'names,emailAddresses,phoneNumbers,birthdays',
                'pageToken'    => $contactList->getNextPageToken(),
            ]);

            // Add the contacts from the next page to the list
            $connections = array_merge($connections, $contactList->getConnections());
        }

        // Return the list of contacts
        return collect($connections)
            ->sortBy(static function ($person, $index) {
                return $person->names[0]->displayName ?? ' ';
            })
            ->values()
            ->toArray();
    }

    public function convertToCustomer(string $resourceName): void
    {
        try {
            // Get the contact details from the API
            $contact = $this->contactService->people->get($resourceName);

            // Create a new customer object
            $customer = new Customer();
            $customer->name = $contact->names[0]->displayName;
            $customer->email = $contact->emailAddresses[0]->value;
            $customer->phone = $contact->phoneNumbers[0]->value;

            // Save the customer object to the database
            $customer->save();
        } catch (Throwable) {
            //throw $th;
        }

        // Display a success message
        // dd('Customer created successfully!');
    }

    public function render()
    {
        return view('livewire.customers.google-contact');
    }
}
