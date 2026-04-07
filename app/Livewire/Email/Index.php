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
use Livewire\Component;

#[Layout('layouts.app')]

class Index extends Component
{
    use Datatable;
    use WithAlert;

    public mixed $email;

    public string $model = EmailTemplate::class;

    public function render(): View|Factory
    {
        abort_if(Gate::denies('email_access'), 403);

        $query = EmailTemplate::query()->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $lengthAwarePaginator = $query->paginate($this->perPage);

        return view('livewire.email.index', ['emails' => $lengthAwarePaginator]);
    }

    // Blog Category  Delete
    public function delete(EmailTemplate $emailTemplate): void
    {
        abort_if(Gate::denies('email_delete'), 403);

        $emailTemplate->delete();
    }
}
