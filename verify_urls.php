<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFYING URL STRUCTURE IN COURSE CONTROLLER ===\n\n";

// Check the specific lines where URLs are constructed
$controllerFile = 'app/Http/Controllers/Api/CourseController.php';

if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);

    // Check for video URL construction
    if (strpos($content, "asset('storage/'") !== false) {
        echo "⚠️  WARNING: Found 'storage/' in asset() calls:\n";

        $lines = explode("\n", $content);
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, "asset('storage/'") !== false) {
                echo '  Line '.($lineNum + 1).': '.trim($line)."\n";
            }
        }
    } else {
        echo "✓ SUCCESS: No 'storage/' found in asset() calls\n";
    }

    // Check for video URL patterns
    if (strpos($content, 'asset($material->file_path)') !== false) {
        echo "✓ SUCCESS: Found correct asset() pattern for materials\n";
    } else {
        echo "⚠️  WARNING: Correct asset() pattern not found\n";
    }

    // Check for specific video URL construction
    if (strpos($content, "'url' => \$material->file_path ? asset(\$material->file_path) : null") !== false) {
        echo "✓ SUCCESS: Video URL construction is correct\n";
    } else {
        echo "⚠️  WARNING: Video URL construction may not be updated\n";
    }

} else {
    echo "✗ ERROR: CourseController file not found\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
