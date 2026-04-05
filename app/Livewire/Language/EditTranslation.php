<?php

declare(strict_types=1);

namespace App\Livewire\Language;

use App\Models\Language;
use App\Traits\WithAlert;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class EditTranslation extends Component
{
    use WithAlert;
    use WithPagination;

    public $language;

    public $search = '';

    #[Validate([
        'translations.*.value' => 'required',
    ])]
    public $translations;

    public function mount($id): void
    {
        $this->language = Language::where('code', $id)->firstOrFail();
        // dd($this->all());
        $this->translations = $this->getTranslations();
        $this->translations = collect($this->translations)->map(static fn ($item, $key): array => [
            'key' => $key,
            'value' => $item,
        ])->toArray();
    }

    private function getTranslations()
    {
        $path = base_path(sprintf('lang/%s.json', $this->language->code));
        $content = file_get_contents($path);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function update(): void
    {
        $this->validate();

        $path = base_path(sprintf('lang/%s.json', $this->language->code));

        $data = file_get_contents($path);
        $translations = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        foreach ($this->translations as $translation) {
            $translations[$translation['key']] = $translation['value'];
        }

        file_put_contents($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->alert('success', __('Data updated successfully!'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $filtered = collect($this->translations)->filter(function ($translation) {
            if (empty($this->search)) {
                return true;
            }

            $search = strtolower($this->search);
            $keyMatch = str_contains(strtolower((string) $translation['key']), $search);
            $valMatch = str_contains(strtolower((string) $translation['value']), $search);

            return $keyMatch || $valMatch;
        });

        $page = $this->getPage();
        $perPage = 50;

        $paginator = new LengthAwarePaginator(
            $filtered->slice(($page - 1) * $perPage, $perPage, true)->all(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.language.edit-translation', [
            'paginator' => $paginator,
        ]);
    }
}
