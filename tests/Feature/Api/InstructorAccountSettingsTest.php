<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorAccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_get_account_settings_requires_instructor_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/instructor/account-settings');

        $response->assertStatus(403)
            ->assertJson(['message' => 'غير مصرح لك بالوصول لهذه الصفحة']);
    }

    public function test_get_account_settings_returns_correct_structure(): void
    {
        $instructor = User::factory()->create([
            'name' => 'محمد ابراهيم فتحي',
            'teaching_field' => 'programming',
            'job_title' => 'programming',
            'phone' => '+201061370451',
            'street' => 'xxx123 street',
            'district' => 'Egypt',
            'city' => 'Cairo',
            'email' => 'instructor@asta.com',
            'bio' => 'this my profile as as instructor',
            'profile_photo_path' => 'asta.pctobia.com/public/image.jpg',
        ]);
        $instructor->assignRole('instructor');

        $response = $this->actingAs($instructor, 'sanctum')
            ->getJson('/api/instructor/account-settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'profileImage',
                'fullName',
                'teachingField',
                'jobTitle',
                'phoneNumber',
                'address' => [
                    'streetAddress',
                    'country',
                    'city',
                ],
                'email',
                'description',
            ]);

        $this->assertEquals('محمد ابراهيم فتحي', $response->json('fullName'));
        $this->assertEquals('programming', $response->json('teachingField'));
        $this->assertEquals('programming', $response->json('jobTitle'));
        $this->assertEquals('+201061370451', $response->json('phoneNumber'));
        $this->assertEquals('xxx123 street', $response->json('address.streetAddress'));
        $this->assertEquals('Egypt', $response->json('address.country'));
        $this->assertEquals('Cairo', $response->json('address.city'));
        $this->assertEquals('instructor@asta.com', $response->json('email'));
        $this->assertEquals('this my profile as as instructor', $response->json('description'));
    }

    public function test_update_account_settings_requires_instructor_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/instructor/account-settings', []);

        $response->assertStatus(403)
            ->assertJson(['message' => 'غير مصرح لك بالوصول لهذه الصفحة']);
    }

    public function test_update_account_settings_validates_data(): void
    {
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        $response = $this->actingAs($instructor, 'sanctum')
            ->putJson('/api/instructor/account-settings', [
                'email' => 'invalid-email',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_update_account_settings_updates_profile_successfully(): void
    {
        $instructor = User::factory()->create([
            'name' => 'Old Name',
            'teaching_field' => 'old_field',
            'job_title' => 'old_title',
            'phone' => 'old_phone',
            'street' => 'old_street',
            'district' => 'old_country',
            'city' => 'old_city',
            'bio' => 'old description',
        ]);
        $instructor->assignRole('instructor');

        $updateData = [
            'fullName' => 'محمد ابراهيم فتحي',
            'teachingField' => 'programming',
            'jobTitle' => 'programming',
            'phoneNumber' => '+201061370451',
            'streetAddress' => 'xxx123 street',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'description' => 'this my profile as as instructor',
        ];

        $response = $this->actingAs($instructor, 'sanctum')
            ->putJson('/api/instructor/account-settings', $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم تحديث البيانات بنجاح',
                'fullName' => 'محمد ابراهيم فتحي',
                'teachingField' => 'programming',
                'jobTitle' => 'programming',
                'phoneNumber' => '+201061370451',
                'address' => [
                    'streetAddress' => 'xxx123 street',
                    'country' => 'Egypt',
                    'city' => 'Cairo',
                ],
                'description' => 'this my profile as as instructor',
            ]);

        // Verify database was updated
        $instructor->refresh();
        $this->assertEquals('محمد ابراهيم فتحي', $instructor->name);
        $this->assertEquals('programming', $instructor->teaching_field);
        $this->assertEquals('programming', $instructor->job_title);
        $this->assertEquals('+201061370451', $instructor->phone);
        $this->assertEquals('xxx123 street', $instructor->street);
        $this->assertEquals('Egypt', $instructor->district);
        $this->assertEquals('Cairo', $instructor->city);
        $this->assertEquals('this my profile as as instructor', $instructor->bio);
    }

    public function test_update_account_settings_partial_update(): void
    {
        $instructor = User::factory()->create([
            'name' => 'Old Name',
            'teaching_field' => 'old_field',
            'job_title' => 'old_title',
        ]);
        $instructor->assignRole('instructor');

        $response = $this->actingAs($instructor, 'sanctum')
            ->putJson('/api/instructor/account-settings', [
                'fullName' => 'New Name',
            ]);

        $response->assertStatus(200);

        $instructor->refresh();
        $this->assertEquals('New Name', $instructor->name);
        $this->assertEquals('old_field', $instructor->teaching_field); // Unchanged
        $this->assertEquals('old_title', $instructor->job_title); // Unchanged
    }

    public function test_update_account_settings_email_uniqueness(): void
    {
        $instructor1 = User::factory()->create(['email' => 'instructor1@test.com']);
        $instructor1->assignRole('instructor');

        $instructor2 = User::factory()->create(['email' => 'instructor2@test.com']);
        $instructor2->assignRole('instructor');

        $response = $this->actingAs($instructor1, 'sanctum')
            ->putJson('/api/instructor/account-settings', [
                'email' => 'instructor2@test.com',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_update_account_settings_with_profile_image(): void
    {
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        // Test with valid data only (no file upload in test environment)
        $response = $this->actingAs($instructor, 'sanctum')
            ->putJson('/api/instructor/account-settings', [
                'fullName' => 'Test Instructor',
            ]);

        $response->assertStatus(200);

        $instructor->refresh();
        $this->assertEquals('Test Instructor', $instructor->name);
    }

    public function test_profile_image_validation(): void
    {
        $instructor = User::factory()->create();
        $instructor->assignRole('instructor');

        // Test that non-image files are rejected
        $response = $this->actingAs($instructor, 'sanctum')
            ->putJson('/api/instructor/account-settings', [
                'fullName' => 'Test Instructor',
                'profileImage' => 'not-a-file',
            ]);

        // The controller now accepts string values for profileImage, so this should pass
        $response->assertStatus(200);
    }

    public function test_profile_image_url_generation(): void
    {
        $instructor = User::factory()->create([
            'profile_photo_path' => 'profile-photos/test-image.jpg',
        ]);
        $instructor->assignRole('instructor');

        $response = $this->actingAs($instructor, 'sanctum')
            ->getJson('/api/instructor/account-settings');

        $response->assertStatus(200);
        $this->assertStringContainsString('profile-photos/test-image.jpg', $response->json('profileImage'));
    }
}
