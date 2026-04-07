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

    public mixed $language;

    public $search = '';

    #[Validate([
        'translations.*.value' => 'required',
    ])]
    public mixed $translations;

    public function mount(int|string $id): void
    {
        $this->language = Language::query()->where('code', $id)->firstOrFail();
        // dd($this->all());
        $this->translations = $this->getTranslations();
        $this->translations = collect($this->translations)->map(static fn ($item, $key): array => [
            'key' => $key,
            'value' => $item,
        ])->all();
    }

    private function getTranslations(): mixed
    {
        $path = lang_path($this->language->code . '.json');
        $content = file_get_contents($path);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    public function update(): void
    {
        $this->validate();

        $path = lang_path($this->language->code . '.json');

        $data = file_get_contents($path);
        $translations = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        foreach ($this->translations as $translation) {
            $translations[$translation['key']] = $translation['value'];
        }

        file_put_contents($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->alert('success', __('Data updated successfully!'));
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $filtered = collect($this->translations)->filter(function (array $translation): bool {
            if (blank($this->search)) {
                return true;
            }

            $search = strtolower((string) $this->search);
            $keyMatch = str_contains(strtolower((string) $translation['key']), $search);
            $valMatch = str_contains(strtolower((string) $translation['value']), $search);

            return $keyMatch || $valMatch;
        });

        $page = $this->getPage();
        $perPage = 50;

        $lengthAwarePaginator = new LengthAwarePaginator(
            $filtered->slice(($page - 1) * $perPage, $perPage)->all(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.language.edit-translation', [
            'paginator' => $lengthAwarePaginator,
        ]);
    }
}
