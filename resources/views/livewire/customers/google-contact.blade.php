<div>
    <h2>Google Contacts</h2>

    <button wire:click="showContacts" type="button">Fetch Contacts</button>

    @dump($contacts)

    @if (!empty($contacts))
        <ul>
            @foreach ($contacts as $contact)
                <li>
                    <strong>Name:</strong> {{ $contact['names'][0]['displayName'] ?? 'N/A' }}<br>
                    <strong>Email:</strong> {{ $contact['emailAddresses'][0]['value'] ?? 'N/A' }}<br>
                    <strong>Phone:</strong> {{ $contact['phoneNumbers'][0]['value'] ?? 'N/A' }}<br>
                    <strong>Birthday:</strong> {{ $contact['birthdays'][0]['date']['year'] ?? 'N/A' }}<br>

                    <!-- Button to convert contact to customer -->
                    <button wire:click="convertToCustomer({{ $contact['resourceName'] }})">Convert to Customer</button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
