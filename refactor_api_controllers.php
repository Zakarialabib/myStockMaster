<?php

declare(strict_types=1);

$dir = __DIR__ . '/app/Http/Controllers/Api';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $basename = basename($file);

    if ($basename === 'BaseController.php') {
        continue;
    }

    $modelName = str_replace('Controller.php', '', $basename);
    $lowerModel = strtolower($modelName);
    if ($lowerModel === 'auth' || $lowerModel === 'sync') {
        // Handle separately
        continue;
    }
    $pluralModel = $lowerModel === 'category' ? 'categories' : $lowerModel . 's';

    $content = str_replace('extends BaseController', 'extends Controller', $content);

    if (! str_contains($content, 'use App\Http\Controllers\Controller;')) {
        $content = str_replace('namespace App\Http\Controllers\Api;', "namespace App\Http\Controllers\Api;\n\nuse App\Http\Controllers\Controller;\nuse Illuminate\Http\JsonResponse;\nuse Illuminate\Http\Resources\Json\AnonymousResourceCollection;\nuse Illuminate\Routing\Attributes\Delete;\nuse Illuminate\Routing\Attributes\Get;\nuse Illuminate\Routing\Attributes\Middleware;\nuse Illuminate\Routing\Attributes\Post;\nuse Illuminate\Routing\Attributes\Put;", $content);
    }

    // Add Resource import if not present
    if (! str_contains($content, "use App\Http\Resources\\{$modelName}Resource;")) {
        $content = str_replace("use App\Models\\{$modelName};", "use App\Models\\{$modelName};\nuse App\Http\Resources\\{$modelName}Resource;", $content);
    }

    // Create FormRequests
    $storeReq = "Store{$modelName}Request";
    $updateReq = "Update{$modelName}Request";

    // Ensure Requests are imported
    if (! str_contains($content, "use App\Http\Requests\\{$storeReq};")) {
        $content = str_replace("use Illuminate\Http\Request;", "use App\Http\Requests\\{$storeReq};\nuse App\Http\Requests\\{$updateReq};\nuse Illuminate\Http\Request;", $content);
    }

    // index
    $content = preg_replace(
        '/public function index\(Request \$request\)\s*\{(.*?)\s*try\s*\{(.*?)return \$this->sendResponse\(\$([^,]+),\s*[^)]+\);(.*?)catch\s*\([^)]+\)\s*\{(.*?)\}\s*\}/s',
        "#[Get('/api/{$pluralModel}', name: 'api.{$pluralModel}.index')]\n    #[Middleware('api')]\n    public function index(Request \$request): AnonymousResourceCollection\n    {\n\$2return {$modelName}Resource::collection($$3);\n    }",
        $content
    );

    // show
    $content = preg_replace(
        '/public function show\(\$id\)\s*\{(.*?)\s*try\s*\{(.*?)\$([a-zA-Z0-9_]+) = ([a-zA-Z0-9_]+)::find\(\$id\);(.*?)return \$this->sendResponse\(\$([a-zA-Z0-9_]+),\s*[^)]+\);(.*?)catch\s*\([^)]+\)\s*\{(.*?)\}\s*\}/s',
        "#[Get('/api/{$pluralModel}/{id}', name: 'api.{$pluralModel}.show')]\n    #[Middleware('api')]\n    public function show(int \$id): {$modelName}Resource|JsonResponse\n    {\n        \$$3 = $4::find(\$id);\n\n        if (is_null(\$$3)) {\n            return response()->json(['message' => '{$modelName} not found'], 404);\n        }\n\n        return new {$modelName}Resource(\$$3);\n    }",
        $content
    );

    // store
    $content = preg_replace(
        '/public function store\(Request \$request\)\s*\{(.*?)\s*DB::beginTransaction\(\);(.*?)try\s*\{(.*?)\$input = \$request->all\(\);(.*?)\$([a-zA-Z0-9_]+) = ([a-zA-Z0-9_]+)::create\(\$input\);(.*?)DB::commit\(\);(.*?)return \$this->sendResponse\(\$([a-zA-Z0-9_]+),\s*[^)]+\);(.*?)catch\s*\([^)]+\)\s*\{(.*?)\}\s*\}/s',
        "#[Post('/api/{$pluralModel}', name: 'api.{$pluralModel}.store')]\n    #[Middleware('api')]\n    public function store({$storeReq} \$request): {$modelName}Resource\n    {\n        \$$5 = $6::create(\$request->validated());\n\n        return new {$modelName}Resource(\$$5);\n    }",
        $content
    );

    // update
    $content = preg_replace(
        '/public function update\(Request \$request, \$id\)\s*\{(.*?)\$([a-zA-Z0-9_]+) = ([a-zA-Z0-9_]+)::findOrFail\(\$id\);\s*\$([a-zA-Z0-9_]+)->update\(\$request->all\(\)\);\s*return new [a-zA-Z0-9_]+Resource\(\$([a-zA-Z0-9_]+)\);\s*\}/s',
        "#[Put('/api/{$pluralModel}/{id}', name: 'api.{$pluralModel}.update')]\n    #[Middleware('api')]\n    public function update({$updateReq} \$request, int \$id): {$modelName}Resource\n    {\n        \$$2 = $3::findOrFail(\$id);\n        \$$4->update(\$request->validated());\n\n        return new {$modelName}Resource(\$$5);\n    }",
        $content
    );

    // destroy
    $content = preg_replace(
        '/public function destroy\(\$id\)\s*\{(.*?)\s*try\s*\{(.*?)\$([a-zA-Z0-9_]+) = ([a-zA-Z0-9_]+)::findorFail\(\$id\);\s*\$([a-zA-Z0-9_]+)->delete\(\);(.*?)return \$this->sendResponse\(\$([a-zA-Z0-9_]+),\s*[^)]+\);(.*?)catch\s*\([^)]+\)\s*\{(.*?)\}\s*\}/s',
        "#[Delete('/api/{$pluralModel}/{id}', name: 'api.{$pluralModel}.destroy')]\n    #[Middleware('api')]\n    public function destroy(int \$id): JsonResponse\n    {\n        \$$3 = $4::findOrFail(\$id);\n        \$$5->delete();\n\n        return response()->json(['message' => '{$modelName} deleted successfully']);\n    }",
        $content
    );

    file_put_contents($file, $content);
}

echo "Done API controllers\n";
