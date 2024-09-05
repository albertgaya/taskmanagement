<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Faker\Factory;
use Illuminate\Support\Facades\Auth;
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

        $userMock = Mockery::mock('alias:' . User::class);

        $expectedUser = new User();
        $expectedUser->name = $data['name'];
        $expectedUser->email = $data['email'];

        $userMock->shouldReceive('create')
            ->andReturn($expectedUser);

        $userService = new UserService();
        $user = $userService->store($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->name, $data['name']);
        $this->assertEquals($user->email, $data['email']);
    }

    public function testLogin(): void
    {
        $faker = Factory::create();

        $email = $faker->email();
        $password = $faker->password();
        $expectedToken = '123';

        Auth::shouldReceive('attempt')
            ->andReturn(true);

        $userMock = Mockery::mock('alias:' . User::class);

        $userMock
            ->shouldReceive('where')
            ->andReturn($userMock);

        $userMock->shouldReceive('firstOrFail')
            ->andReturn($userMock);

        $tokenObject = new \stdClass();
        $tokenObject->plainTextToken = $expectedToken;

        $userMock->shouldReceive('createToken')
            ->andReturn($tokenObject);

        $userService = new UserService();
        $token = $userService->login($email, $password);

        $this->assertNotEmpty($token);
        $this->assertEquals($expectedToken, $token);
    }
}
