<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        return User::create($data);
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     */
    public function login(string $email, string $password): string
    {
        $token = '';

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = User::where('email', $email)->firstOrFail();

            $token = $user->createToken('API Token')->plainTextToken;
        }

        return $token;
    }
}