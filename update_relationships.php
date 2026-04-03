<?php
$files = glob(__DIR__ . '/app/Models/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Find all methods
    preg_match_all('/public function ([a-zA-Z0-9_]+)\(\)\s*\{\s*return \$this->(belongsTo|hasMany|hasOne|belongsToMany|morphTo|morphMany|morphOne|morphToMany|morphedByMany)\(/', $content, $matches, PREG_SET_ORDER);
    
    $modified = false;
    foreach ($matches as $match) {
        $methodName = $match[1];
        $relType = ucfirst($match[2]);
        
        // Ensure the relationship class is imported or use full namespace
        $returnType = "\\Illuminate\\Database\\Eloquent\\Relations\\" . $relType;
        
        // Find if the method already has a return type
        $pattern = '/public function ' . $methodName . '\(\)(\s*:\s*[a-zA-Z0-9_\\\\]+)?\s*\{/';
        
        if (preg_match($pattern, $content, $m)) {
            if (empty($m[1])) {
                // No return type, let's add it
                $content = preg_replace('/public function ' . $methodName . '\(\)\s*\{/', 'public function ' . $methodName . '(): ' . $returnType . "\n    {", $content);
                $modified = true;
            }
        }
    }
    
    if ($modified) {
        file_put_contents($file, $content);
        echo "Updated relationships in " . basename($file) . "\n";
    }
}
