<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UpdateLearningPathsImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing learning paths to have the asta.png image
        \App\Models\LearningPath::query()->update([
            'image' => 'asta.png',
        ]);

        $this->command->info('Updated all learning paths with asta.png image');
    }
}
