<?php

namespace Database\Seeders;

use App\Models\Partner;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        $partners = [
            [
                'name' => 'شركة مايكروسوفت',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة جوجل',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة أبل',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة أمازون',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة فيسبوك',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة نيتفليكس',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة أوبر',
                'image' => 'images/asta.png',
            ],
            [
                'name' => 'شركة إير بي إن بي',
                'image' => 'images/asta.png',
            ],
        ];

        foreach ($partners as $partnerData) {
            Partner::firstOrCreate([
                'name' => $partnerData['name'],
            ], [
                'image' => $partnerData['image'],
                'is_active' => true,
                'sort_order' => rand(1, 100),
            ]);
        }

        $this->command->info('Partners seeded successfully!');
    }
}
