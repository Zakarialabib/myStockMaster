<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OpenAi;
use Illuminate\Support\Facades\File;

class GenerateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Laravel project using AI';

    /** Execute the console command. */
    public function handle(OpenAi $openAi)
    {
        $prompt = $this->ask('Provide a prompt for the project details');

        // Step 2: Enhance OpenAI Interaction
        $generatedCode = $openAi->execute($prompt, ['max_tokens' => 8000]);

        // Step 3: Interactive Generation
        $this->info('Generated Code:');
        $this->line($generatedCode);

        if ($this->confirm('Do you want to modify the generated code?')) {
            $modifiedCode = $this->ask('Enter your modifications');
            $generatedCode = $modifiedCode;
        }

        // Step 4: Project Structure and Files
        $this->generateProjectFiles($generatedCode);

        // Step 5: Configuration Options
        $this->applyConfigurationOptions($generatedCode);

        // Step 6: Dependency Injection and Service Providers
        // Implement DI and service providers based on the generated code

        $this->info('Project generated successfully!');
    }

    protected function generateProjectFiles(string $generatedCode)
    {
        // Parse $generatedCode and generate files based on the content
        // For example, create controllers, models, migrations, routes, views, etc.

        // Dummy example: Create a controller with the generated code
        $controllerContent = "<?php\n\n{$generatedCode}";
        File::put(app_path('Http/Controllers/GeneratedController.php'), $controllerContent);

        $this->info('Project files generated successfully!');
    }

    protected function applyConfigurationOptions(string $generatedCode)
    {
        // Parse $generatedCode and apply configuration options
        // For example, update database configuration, add service providers, etc.

        // Dummy example: Update the .env file with configuration details
        File::append(base_path('.env'), "\n{$generatedCode}");

        $this->info('Configuration options applied successfully!');
    }
}
