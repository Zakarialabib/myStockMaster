<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use App\Services\OpenAi;
use Symfony\Component\Console\Input\InputOption;

class GenerateModel extends Command
{
    protected $signature = 'generate:model';
    protected $description = 'Create a new model using AI';

    public function __construct(private readonly OpenAi $openAi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputOption::VALUE_REQUIRED, 'The name of the model');
    }

    public function handle(): int
    {
        $name = $this->getNameArgument();

        $prompt = $this->createAiPrompt($name);

        $this->info('Generating AI model, this might take a few moments...');

        try {
            $modelContent = $this->fetchAiGeneratedContent($prompt);
            $this->createModelFile($name, $modelContent);
        } catch (RequestException $e) {
            $this->error('Error fetching AI-generated content: '.$e->getMessage());
        }

        return 0;
    }

    private function createAiPrompt(string $name): string
    {
        $prompt = "Generate a new Laravel model named '{$name}'.";
        $prompt .= "\nEnsure strict adherence to Laravel best practices and conventions.";
        $prompt .= "\nInclude all necessary attributes, relationships, and methods with proper documentation.";
        $prompt .= "\nUse clear and expressive names for attributes and methods.";
        $prompt .= "\nInclude type hints for attributes and method arguments.";
        $prompt .= "\nSpecify table name, primary key, timestamps, and other model properties explicitly.";
        $prompt .= "\nImplement relationships using Eloquent methods (hasOne, hasMany, belongsTo, etc.).";
        $prompt .= "\nProvide detailed and meaningful comments for complex logic or relationships.";
        $prompt .= "\nAvoid unnecessary code or comments and focus on clean, readable, and efficient code.";
        $prompt .= "\nProvide only the class code for the model '{$name}' without instructions or explanations (start with <?php).";

        return $prompt;
    }

    private function getNameArgument(): string
    {
        $name = $this->argument('name');

        if ( ! $name) {
            $name = $this->ask($this->promptForMissingArgumentsUsing()['name']);
        }

        return $name;
    }

    private function fetchAiGeneratedContent(string $prompt): string
    {
        return $this->openAi->execute($prompt, 2000);
    }

    private function createModelFile(string $name, string $content): void
    {
        $path = app_path('Models');

        $name = "{$name}.php";
        $filepath = "{$path}/{$name}";

        if ( ! file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Your custom stub model content
        $stubModelContent = file_get_contents(base_path('stubs/model.stub'));

        // Replace placeholders with actual content
        $stubModelContent = str_replace(['{{ class }}'], [$name], $stubModelContent);
        $stubModelContent .= "\n\n".$content;

        file_put_contents($filepath, $stubModelContent);

        $this->info(sprintf('Model [%s] created successfully.', $name));
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => 'What should the model be named?',
        ];
    }
}
