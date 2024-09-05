<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Faker\Factory;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testStore(): void
    {
        $faker = Factory::create();

        $data = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $faker->password()
        ];

        Mockery::mock('alias:' . User::class)
            ->shouldReceive('create')
            ->once()
            ->andReturn(
                new User($data)
            );

        $userService = new UserService();
        $user = $userService->store($data);

        $this->assertInstanceOf(User::class, $user);
    }
}
