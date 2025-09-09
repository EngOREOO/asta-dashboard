<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicNames = [
            'أحمد محمد',
            'فاطمة علي',
            'محمد حسن',
            'نور الدين',
            'سارة أحمد',
            'عبدالله خالد',
            'مريم سالم',
            'يوسف إبراهيم',
            'زينب محمود',
            'عمر عبدالرحمن',
            'هند محمد',
            'خالد أحمد',
            'نورا سعد',
            'عبدالرحمن علي',
            'ريم عبدالله',
        ];

        $arabicComments = [
            'تجربة رائعة جداً! الدورات ممتازة والمحتوى مفيد جداً.',
            'منصة تعليمية ممتازة، المحاضرون محترفون والمحتوى عالي الجودة.',
            'أفضل منصة تعليمية استخدمتها، أنصح الجميع بها.',
            'المحتوى منظم بشكل ممتاز، التعلم أصبح أسهل بكثير.',
            'دورات مفيدة جداً، ساعدتني في تطوير مهاراتي بشكل كبير.',
            'منصة رائعة للمتعلمين، المحتوى متنوع ومفيد.',
            'تجربة تعليمية استثنائية، المحاضرون متميزون.',
            'أفضل استثمار في التعليم، أنصح الجميع بالتسجيل.',
            'منصة تعليمية متطورة، المحتوى حديث ومفيد.',
            'تجربة ممتازة، الدورات شاملة ومفيدة جداً.',
            'منصة رائعة للتعلم، المحتوى عالي الجودة.',
            'أفضل منصة تعليمية، المحاضرون محترفون جداً.',
            'تجربة تعليمية ممتازة، أنصح بها بشدة.',
            'منصة مفيدة جداً، المحتوى منظم ومفهوم.',
            'أفضل مكان للتعلم، الدورات شاملة ومفيدة.',
        ];

        return [
            'user_name' => $this->faker->randomElement($arabicNames),
            'user_image' => null, // We'll keep it null for now
            'rating' => $this->faker->numberBetween(4, 5), // Mostly positive ratings
            'comment' => $this->faker->randomElement($arabicComments),
            'is_approved' => $this->faker->boolean(80), // 80% approved
            'is_featured' => $this->faker->boolean(20), // 20% featured
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the testimonial is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the testimonial is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the testimonial is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the testimonial has a high rating.
     */
    public function highRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(4, 5),
        ]);
    }

    /**
     * Indicate that the testimonial has a low rating.
     */
    public function lowRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(1, 3),
        ]);
    }
}
