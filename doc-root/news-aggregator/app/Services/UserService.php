<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function registerUser(array $data) {

        // Create the user
        $user = $this->user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function login(array $credentials)
    {
        // Attempt to authenticate
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
            ];
        }

        // Fetch authenticated user
        $user = Auth::user();


        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

}
