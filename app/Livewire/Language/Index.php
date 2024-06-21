<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use Livewire\Component;
use App\Models\Language;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use LivewireAlert;

    public $language;

    #[Computed]
    public function languages()
    {
        return Language::query()->get();
    }

    public function render()
    {
        abort_if(Gate::denies('language_access'), 403);

        return view('livewire.language.index');
    }

    public function onSetDefault($id): void
    {
        Language::where('is_default', '=', true)->update(['is_default' => false]);

        $this->language = Language::findOrFail($id);

        $this->language->is_default = true;

        $this->language->save();

        $this->alert('success', __('Language updated successfully!'));
    }

    public function sync($id): void
    {
        $languages = Language::findOrFail($id);

        Artisan::call('translatable:export', ['lang' => $languages->code]);

        $this->alert('success', __('Translation updated successfully!'));
    }

    public function delete(Language $language): void
    {
        abort_if(Gate::denies('language_delete'), 403);

        $language->delete();

        $this->alert('warning', __('Language deleted successfully!'));
    }
}
