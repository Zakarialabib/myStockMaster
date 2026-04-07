<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use App\Models\Language;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class Index extends Component
{
    use WithAlert;

    public mixed $language;

    #[Computed]
    public function languages()
    {
        return Language::query()->get();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        abort_if(Gate::denies('language_access'), 403);

        return view('livewire.language.index');
    }

    public function onSetDefault(mixed $id): void
    {
        Language::query()->where('is_default', '=', true)->update(['is_default' => false]);

        $this->language = Language::query()->findOrFail($id);

        $this->language->is_default = true;

        $this->language->save();

        $this->alert('success', __('Language updated successfully!'));
    }

    public function sync(mixed $id): void
    {
        $languages = Language::query()->findOrFail($id);

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
