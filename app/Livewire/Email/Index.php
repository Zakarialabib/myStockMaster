<?php

declare(strict_types=1);

namespace App\Livewire\Email;

use App\Livewire\Utils\Datatable;
use App\Models\EmailTemplate;
use App\Traits\WithAlert;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use Datatable;
    use WithAlert;

    public $email;

    public $model = EmailTemplate::class;

    public function render(): View|Factory
    {
        abort_if(Gate::denies('email_access'), 403);

        $query = EmailTemplate::advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $emails = $query->paginate($this->perPage);

        return view('livewire.email.index', ['emails' => $emails]);
    }

    // Blog Category  Delete
    public function delete(EmailTemplate $email): void
    {
        abort_if(Gate::denies('email_delete'), 403);

        $email->delete();
    }
}
