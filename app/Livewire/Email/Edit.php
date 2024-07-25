<?php

declare(strict_types=1);

namespace App\Livewire\Email;

use App\Models\EmailTemplate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $openModal = false;

    public $email_setting;

    public $description;

    public $message;

    public function updatedMessage($value): void
    {
        $this->message = $value;
    }

    protected $rules = [
        'email_setting.name'         => ['required', 'max:255'],
        'description'                => ['required'],
        'message'                    => ['required'],
        'email_setting.default'      => ['required'],
        'email_setting.placeholders' => ['required'],
        'email_setting.type'         => ['required'],
        'email_setting.subject'      => ['required'],
    ];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('email update'), 403);

        return view('livewire.email.edit');
    }

    #[On('editModal')]
    public function editModal($id): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->email_setting = EmailTemplate::whereId($id)->firstOrFail();
        $this->description = $this->email_setting->description;
        $this->message = $this->email_setting->message;
        $this->openModal = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->email_setting->update($this->all());

        $this->alert('success', __('Email template created successfully.'));

        $this->openModal = false;
    }
}
