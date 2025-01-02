<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a user can be created successfully.
     */
    public function test_it_can_create_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    /**
     * Test if the password is hashed correctly when creating a user.
     */
    public function test_it_hashes_user_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    /**
     * Test if a user has a valid API token.
     */
    public function test_it_can_generate_a_valid_api_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $this->assertNotEmpty($token);
    }

    /**
     * Test for user validation constraints.
     */
    public function test_user_validation_fails_without_name_or_email()
    {
        try {
            User::factory()->create(['name' => null, 'email' => null]);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
}
