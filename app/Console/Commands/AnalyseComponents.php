<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AnalyseComponents extends Command
{
    protected $signature = 'components:analyse 
        {--path=resources/views/components : Path to scan} 
        {--output=storage/app/components-analysis.md : Markdown output file}';

    protected $description = 'Analyse Blade/Livewire components usage, structure, and quality';

    public function handle(): void
    {
        $path = base_path($this->option('path'));
        $outputFile = base_path($this->option('output'));

        if (! File::isDirectory($path)) {
            $this->error(sprintf('Path %s not found.', $path));

            return;
        }

        $components = File::allFiles($path);

        $report = "# Component Analysis Report\n\n";
        $report .= 'Generated: ' . now()->toDateTimeString() . "\n\n";

        $stats = [];

        foreach ($components as $component) {
            $relative = Str::after($component->getPathname(), base_path() . '/');
            $content = File::get($component->getPathname());
            $lines = substr_count($content, "\n") + 1;

            // Check usage in project (naive search for <x-ComponentName>)
            $componentName = Str::of($component->getFilenameWithoutExtension())
                ->replace('.', '-')
                ->lower();

            $usageCount = $this->countUsage($componentName);

            $stats[] = [
                'file' => $relative,
                'lines' => $lines,
                'used' => $usageCount,
                'theming_ready' => Str::contains($content, 'class="') ? '✅' : '⚠️',
                'dusk_ready' => Str::contains($content, 'dusk=') || Str::contains($content, 'id=') ? '✅' : '⚠️',
            ];
        }

        // Summary
        $report .= "## Summary\n\n";
        $report .= '- Total components: **' . count($stats) . "**\n";
        $report .= '- Most used: **' . collect($stats)->sortByDesc('used')->first()['file'] . "**\n";
        $report .= '- Unused: **' . collect($stats)->where('used', 0)->count() . "**\n\n";

        // Detailed table
        $report .= "## Components Detail\n\n";
        $report .= "| File | LOC | Usage | Theme | Dusk/ID |\n";
        $report .= "|------|-----|-------|-------|---------|\n";

        foreach ($stats as $stat) {
            $report .= "| {$stat['file']} | {$stat['lines']} | {$stat['used']} | {$stat['theming_ready']} | {$stat['dusk_ready']} |\n";
        }

        File::put($outputFile, $report);

        $this->info('Analysis saved to ' . $outputFile);
    }

    private function countUsage(string $componentName): int
    {
        $viewsPath = base_path('resources/views');
        $views = File::allFiles($viewsPath);

        $count = 0;

        foreach ($views as $view) {
            $content = File::get($view->getPathname());
            $count += substr_count($content, '<x-' . $componentName);
        }

        return $count;
    }
}
