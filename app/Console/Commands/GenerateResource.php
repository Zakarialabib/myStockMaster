<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Services\OpenAi;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class GenerateResource.
 *
 * A Laravel console command to create a new resource using AI.
 */
class GenerateResource extends Command
{
    /** The name and signature of the console command. */
    protected $signature = 'generate:resource';

    /** The console command description. */
    protected $description = 'Create a new resource using AI';

    public function __construct(private readonly OpenAi $openAi)
    {
        parent::__construct();
    }

    /** Configure the command options. */
    protected function configure(): void
    {
        $this->addArgument('name', InputOption::VALUE_REQUIRED, 'The name of the resource')
            ->addOption('model', 'm', InputOption::VALUE_REQUIRED, 'The model for the resource');
    }

    /** Execute the console command. */
    public function handle(): int
    {
        $name = $this->getNameArgument();
        $model = $this->getModelOption();

        try {
            $model = $this->qualifyModel($model);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return 1;
        }

        if ( ! $this->tableExistsForModel($model)) {
            return 1;
        }

        $schema = $this->getSchemaForModel($model);
        $prompt = $this->createAiPrompt($name, $model, $schema);

        $this->info('Generating AI resource, this might take a few moments...');

        try {
            $resourceContent = $this->fetchAiGeneratedContent($prompt);
            $this->createResourceFile($name, $resourceContent);
        } catch (RequestException $e) {
            $this->error('Error fetching AI-generated content: '.$e->getMessage());
        }

        return 0;
    }

    /** Get the 'name' argument or prompt the user if it's not provided. */
    private function getNameArgument(): string
    {
        $name = $this->argument('name');

        if ( ! $name) {
            $name = $this->ask($this->promptForMissingArgumentsUsing()['name']);
        }

        return $name;
    }

    /** Get the 'model' option or prompt the user if it's not provided. */
    private function getModelOption(): string
    {
        $model = $this->option('model');

        if ( ! $model) {
            $model = $this->ask('Please provide the model for the resource');
        }

        return $model;
    }

    /**
     * Check if the table exists for the provided model.
     *
     * @return bool true if the table exists, false otherwise
     */
    private function tableExistsForModel(string $model): bool
    {
        /** @var \Illuminate\Database\Eloquent\Model $object */
        $object = new $model();
        $table = $object->getTable();

        if ( ! Schema::hasTable($table)) {
            $this->error("The table for the provided model '{$model}' does not exist.");

            return false;
        }

        return true;
    }

    /**
     * Get the schema for the provided model.
     *
     * @return string[] The schema for the model
     */
    private function getSchemaForModel(string $model): array
    {
        /** @var \Illuminate\Database\Eloquent\Model $object */
        $object = new $model();
        $table = $object->getTable();

        return Schema::getColumnListing($table);
    }

    /**
     * Qualify the model class name.
     *
     * If a class with the constructed name exists, it returns the fully qualified class name.
     * If not, it checks if a class with the original name exists. If it does, it returns the
     * original name.
     *
     * If no class is found with either the original name or the constructed name, it throws
     * an InvalidArgumentException.
     *
     * @param  string  $model  The model class name to qualify.
     * @return string The qualified model class name.
     *
     * @throws InvalidArgumentException If no model class is found with the given name.
     */
    public function qualifyModel(string $model): string
    {
        $model = ltrim($model, '\\');

        $namespaceModel = $this->laravel->getNamespace().'Models\\'.$model;

        if (class_exists($namespaceModel)) {
            return $namespaceModel;
        }

        if (class_exists($model)) {
            return $model;
        }

        throw new InvalidArgumentException("Model '{$model}' does not exist.");
    }

    /**
     * Create an AI prompt using the provided information.
     *
     * @param  string[]  $schema The schema of the model as an array of column names
     */
    private function createAiPrompt(string $name, string $model, array $schema): string
    {
        $prompt = "Generate a one laravel class for api resource named '{$name}' for the '{$model}' model.";
        $prompt .= "\nThe current schema of the table is as follows:\n".implode(', ', $schema);
        $prompt .= "\nProvide only class resource '{$name}' code , (start with <?php)";
        $prompt .= "\nConsider generating relation related to table schema and include all logic inside method toArray.";
        $prompt .= "\nBe strict in the response, No need for instructions, usage, explanations or any other additional information..";

        return $prompt;
    }

    /**
     * Fetch AI-generated content using the provided prompt.
     *
     * @param  string  $prompt  The AI prompt
     * @return string The AI-generated content
     *
     * @throws RequestException
     */
    private function fetchAiGeneratedContent(string $prompt): string
    {
        return $this->openAi->execute($prompt);
    }

    /**
     * Create a resource file using the provided name and content.
     *
     * @param  string  $name  The resource name
     * @param  string  $content  The resource content
     */
    private function createResourceFile(string $name, string $content): void
    {
        $path = app_path('Http/Resources');

        if ( ! Str::endsWith($name, 'Resource')) {
            $name .= 'Resource';
        }

        $name = "{$name}.php";
        $filepath = "{$path}/{$name}";

        if ( ! file_exists($path)) {
            mkdir($path, 0755, true);
        }

        file_put_contents($filepath, $content);

        $this->info(sprintf('Resource [%s] created successfully.', $name));
    }

    /**
     * Prompt for missing arguments.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => 'What should the resource be named?',
        ];
    }
}
