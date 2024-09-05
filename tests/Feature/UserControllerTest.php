<?php

namespace Tests\Feature;

use App\Enums\HttpStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testUserRegisterSubmission(): void
    {
        $password = $this->faker->password();

        $response = $this->postJson('/api/users/register', [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $response->assertStatus(HttpStatusEnum::OK);
        $response->assertJsonFragment(['message' => 'User successfully registered']);
        $this->assertDatabaseCount(User::class, 1);
    }

    public function testUserRegisterValidation(): void
    {
        $response = $this->postJson('/api/users/register');
        $response->assertStatus(HttpStatusEnum::UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonCount(3, 'errors');
        $response->assertJson([
            'errors' => [
                'name' => [],
                'email' => [],
                'password' => [],
            ]
        ]);
    }

    public function testUserRegisterUniqueEmail(): void
    {
        $email = $this->faker->email();

        User::factory()->create(['email' => $email]);

        $password = $this->faker->password();

        $response = $this->postJson('/api/users/register', [
            'name' => $this->faker->name(),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ]);
        $response->assertStatus(HttpStatusEnum::UNPROCESSABLE_ENTITY);
        $response->assertJsonCount(1, 'errors');
        $response->assertJson(['errors' => ['email' => []]]);
    }

    public function testUserRegisterConfirmPassword(): void
    {
        $response = $this->postJson('/api/users/register', [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password()
        ]);

        $response->assertStatus(HttpStatusEnum::UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonCount(1, 'errors');
        $response->assertJson(['errors' => ['password' => []]]);
    }

    public function testUserLoginSubmission(): void
    {
        $email = $this->faker->email();
        $password = $this->faker->password();

        User::factory()->create([
            'email' => $email,
            'password' => $password
        ]);

        $response = $this->postJson('/api/users/login', [
            'email' => $email,
            'password' => $password
        ]);
        $response->assertStatus(HttpStatusEnum::OK);
        $response->assertJsonStructure(['message', 'token']);
        $response->assertJsonFragment(['message' => 'User successfully logged in']);
        $this->assertNotEmpty($response->json('token'));
    }

    public function testUserLoginValidation(): void
    {
        $response = $this->postJson('/api/users/login');
        $response->assertStatus(HttpStatusEnum::UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonCount(2, 'errors');
        $response->assertJson([
            'errors' => [
                'email' => [],
                'password' => [],
            ]
        ]);
    }

    public function testUserLoginInvalidCredential(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/users/login', [
            'email' => $user->email,
            'password' => 'invalidpassword'
        ]);
        $response->assertStatus(HttpStatusEnum::UNAUTHORIZED);
        $response->assertJsonFragment(['message' => 'Invalid credentials']);
    }
}
