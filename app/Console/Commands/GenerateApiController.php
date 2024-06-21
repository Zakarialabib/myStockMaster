<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateApiController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:api-controller
        {controller? : The controller name without namespace, e.g., ProductController}
        {model? : The model name, e.g., Product}
        {resource? : The resource name, e.g., ProductResource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an API controller';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->controller = $this->getControllerName();
        $this->model = $this->getModelName();
        $this->resource = $this->getResourceName();

        // Generate the API Controller class file
        $this->generateApiControllerClassFile();
    }

    /**
     * Get the controller name from the user.
     *
     * @return string
     */
    protected function getControllerName()
    {
        $controllerName = $this->argument('controller') ?: $this->ask('Enter controller name (e.g., ProductController)');

        // Add the default namespace if not provided
        if ( ! Str::contains($controllerName, '\\')) {
            $controllerName = 'Http\Controllers\\Api\\'.$controllerName;
        }

        return $controllerName;
    }

    /**
     * Get the model name from the user.
     *
     * @return string
     */
    protected function getModelName()
    {
        return $this->argument('model') ?: $this->ask('Enter model name');
    }

    /**
     * Get the resource name from the user.
     *
     * @return string
     */
    protected function getResourceName()
    {
        return $this->argument('resource') ?: $this->ask('Enter resource name');
    }

    /**
     * Generates the API Controller class file.
     *
     * @return void
     */
    protected function generateApiControllerClassFile()
    {
        // Path to the API Controller class stub file
        $stubPath = base_path('stubs/Api.controller.stub');
        $namespace = $this->getNamespace($this->controller);
        $className = class_basename($this->controller);
        $path = app_path(str_replace('\\', '/', $this->controller).'.php');

        // Create the directory if it doesn't exist
        $this->createDirectoryIfNeeded(dirname($path));

        // Initialize the $file property
        $file = new Filesystem();

        // Check if the class file already exists
        if ($file->exists($path)) {
            $this->info('Class file already exists: '.$this->controller);
            $this->info('Skipping class file creation.');

            return;
        }

        $stubContents = file_get_contents($stubPath);

        // Adjusted namespace and component replacement in the stub
        $stubContents = str_replace(
            ['{{namespace}}', '{{model}}', '{{resource}}', '{{class}}'],
            [$namespace, $this->model, $this->resource, $className],
            $stubContents
        );

        // Write the API Controller class file
        $file->put($path, $stubContents);

        $this->info('API Controller class file generated successfully: '.$path);
    }

    /**
     * Creates a directory if it doesn't exist.
     *
     * @param string $directory
     * @return void
     */
    protected function createDirectoryIfNeeded($directory)
    {
        if ( ! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Gets the namespace from a fully qualified class name.
     *
     * @param string $controller
     * @return string
     */
    protected function getNamespace($controller)
    {
        return implode('\\', array_slice(explode('\\', $controller), 0, -1));
    }
}
