<?php

require_once 'vendor/autoload.php';

use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Storage;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Update the welcome video material
    $material = CourseMaterial::find(221);
    if ($material) {
        // Delete the old file if it exists
        if (Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        // Update to use the direct video path
        $material->update([
            'file_path' => 'video/Ta-Hamp4.mp4'
        ]);

        echo "✅ Successfully updated welcome video path\n";
    } else {
        echo "❌ Material with ID 221 not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error updating welcome video: " . $e->getMessage() . "\n";
}


