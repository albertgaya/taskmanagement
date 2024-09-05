<?php

namespace App\Services;
use App\Models\User;

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
}