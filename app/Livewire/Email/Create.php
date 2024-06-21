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

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $createModal;

    public EmailTemplate $email_setting;

    public $description;

    public $message;

    protected $rules = [
        'email_setting.name'         => ['required', 'max:255'],
        'description'                => ['required'],
        'message'                    => ['required'],
        'email_setting.default'      => ['required'],
        'email_setting.placeholders' => ['required'],
        'email_setting.type'         => ['required'],
        'email_setting.subject'      => ['required'],
        'email_setting.status'       => ['required'],
    ];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('email_create'), 403);

        return view('livewire.email.create');
    }

    #[On('createModal')]
    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->description = '';
        $this->message = '';
        $this->createModal = true;
    }

    public function create(): void
    {
        $this->validate();

        $this->email_setting->description = $this->description;
        $this->email_setting->message = $this->message;

        EmailTemplate::create($this->all());

        $this->alert('success', __('Email template created successfully.'));

        $this->createModal = false;
    }
}
