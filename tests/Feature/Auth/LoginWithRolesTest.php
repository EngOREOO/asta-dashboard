<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginWithRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run the roles seeder to create the instructor role
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_login_returns_user_roles()
    {
        // Create a user with instructor role
        $user = User::factory()->create([
            'email' => 'instructor@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->assignRole('instructor');

        $response = $this->postJson('/api/login', [
            'email' => 'instructor@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'role',
                ],
                'token',
            ])
            ->assertJson([
                'user' => [
                    'email' => 'instructor@example.com',
                    'role' => ['instructor'],
                ],
            ]);

        $this->assertNotEmpty($response->json('token'));
        $this->assertContains('instructor', $response->json('user.role'));
    }

    public function test_login_returns_empty_roles_for_user_without_roles()
    {
        // Create a user without any roles
        $user = User::factory()->create([
            'email' => 'student@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'student@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
                'token',
            ])
            ->assertJson([
                'user' => [
                    'email' => 'student@example.com',
                    'role' => [],
                ],
            ]);

        $this->assertEmpty($response->json('user.role'));
    }

    public function test_register_returns_user_roles()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'role',
                ],
                'token',
            ])
            ->assertJson([
                'user' => [
                    'name' => 'New User',
                    'email' => 'newuser@example.com',
                    'role' => [], // New users typically have no roles initially
                ],
            ]);

        $this->assertNotEmpty($response->json('token'));
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_validation_requires_email_and_password()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
