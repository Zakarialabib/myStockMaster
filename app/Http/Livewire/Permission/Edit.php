<?php

namespace App\Http\Livewire\Permission;

use Livewire\Component;
use App\Models\Permission;

class Edit extends Component
{
    public Permission $permission;
    
    protected $listeners = [
        'submit',
    ];

    public function mount(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function render()
    {
        return view('livewire.permission.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->permission->save();

        $this->alert('success', __('Permission updated successfully!') );

        return redirect()->route('permissions.index');
    }

    protected function rules(): array
    {
        return [
            'permission.title' => [
                'string',
                'required',
            ],
        ];
    }
}
