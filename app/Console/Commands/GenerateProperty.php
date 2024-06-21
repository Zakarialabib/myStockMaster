<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateProperty extends Command
{
    protected $signature = 'generate:properties';

    protected $description = 'Generate Markdown file with table properties';

    public function handle(): void
    {
        $migrations = $this->getMigrationFiles();
        $output = $this->output;

        $progressBar = new ProgressBar($output, count($migrations));
        $progressBar->start();

        $content = "# Property Documentation\n\n";

        foreach ($migrations as $migration) {
            $tableName = $this->getTableName($migration);
            $columns = $this->getTableColumns($migration);

            $properties = $this->formatProperties($columns);
            $propertyTypes = $this->getPropertyTypes($columns);

            $content .= "## Table: `{$tableName}`\n\n";
            $content .= "| Property | Type |\n";
            $content .= "| --- | --- |\n";

            foreach ($columns as $name => $type) {
                $content .= "| `{$name}` | `{$type}` |\n";
            }

            $content .= "\n";
            $progressBar->advance();
        }

        $this->generateMarkdownFile($content);
        $progressBar->finish();
        $this->info("\nProperties generated successfully!");
    }

    protected function getMigrationFiles(): array
    {
        $migrationPath = database_path('migrations');

        return File::glob($migrationPath.'/*.php');
    }

    protected function getTableName(string $migrationFile): string
    {
        $migrationContent = File::get($migrationFile);
        preg_match('/Schema::create\s*\(\s*[\'"](\w+)[\'"]/', $migrationContent, $matches);

        return $matches[1] ?? '';
    }

    protected function getTableColumns(string $migrationFile): array
    {
        $migrationContent = File::get($migrationFile);
        preg_match_all('/\$table->(\w+)\(\'(\w+)\'/', $migrationContent, $matches);

        return array_combine($matches[2], $matches[1]);
    }

    protected function formatProperties(array $columns): string
    {
        $properties = array_map(static function ($type, $name): string {
            return sprintf("'%s'", $name);
        }, $columns, array_keys($columns));

        return implode(', ', $properties);
    }

    protected function getPropertyTypes(array $columns): string
    {
        $propertyTypes = array_map(static function ($type, $name): string {
            return sprintf('protected %s $%s;', $type, $name);
        }, $columns, array_keys($columns));

        return implode("\n", $propertyTypes);
    }

    protected function generateMarkdownFile(string $content): void
    {
        $filePath = base_path('docs/guide/models.md');
        File::put($filePath, $content);
    }
}
