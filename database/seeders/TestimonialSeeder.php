<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some approved testimonials
        Testimonial::factory()
            ->count(15)
            ->approved()
            ->create();

        // Create some pending testimonials
        Testimonial::factory()
            ->count(5)
            ->pending()
            ->create();

        // Create some featured testimonials
        Testimonial::factory()
            ->count(3)
            ->featured()
            ->create();

        // Create some high-rated testimonials
        Testimonial::factory()
            ->count(8)
            ->highRating()
            ->approved()
            ->create();

        // Create some mixed ratings
        Testimonial::factory()
            ->count(4)
            ->lowRating()
            ->approved()
            ->create();
    }
}
