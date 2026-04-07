<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Language;
use App\Traits\WithAlert;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;

class Languages extends Component
{
    use WithAlert;
    use WithPagination;

    public array $languages = [];

    public function mount(): void
    {
        $this->languages = Language::all()->toArray();
    }

    public function render(): View|Factory
    {
        return view('livewire.translations', [
            'languages' => Language::query()->paginate(10),
        ]);
    }

    public function onSetDefault(mixed $id): void
    {
        try {
            Language::query()->where('is_default', '=', true)->update(['is_default' => false]);
            $trans = Language::query()->findOrFail($id);
            $trans->is_default = true;
            $trans->updated_at = now();
            $trans->save();

            $this->alert('success', __('Language updated successfully!'));
        } catch (Exception $exception) {
            $this->alert('error', __($exception->getMessage()));
        }
    }

    /**
     * -------------------------------------------------------------------------------
     *  Sync Translations
     * -------------------------------------------------------------------------------
     */
    public function sync(mixed $id): void
    {
        $languages = Language::query()->findOrFail($id);

        Artisan::call('translatable:export', ['lang' => $languages->code]);

        $this->alert('success', __('Translation updated successfully!'));
    }

    public function delete(Language $language): void
    {
        $language->delete();

        $this->alert('warning', __('Language deleted successfully!'));
    }
}
