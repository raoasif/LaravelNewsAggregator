<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user' => [
                         'id',
                         'name',
                         'email',
                     ],
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
    }

    public function test_register_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '1234', // Doesn't match
        ]);

        $response->assertStatus(422) // Validation error
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login_successfully()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'access_token',
                    'token_type',
                ]);
    }

    /* public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        // Ensure it returns a 401 status code
        $response->assertStatus(401);

        // Verify the structure and content of the response JSON
        $response->assertJson([
            'message' => 'Invalid credentials.',
        ]);

        // Assert that the response does not contain a token
        $response->assertJsonMissing(['access_token']);
    } */
}
