<?php

namespace Tests\Feature;

use App\Enums\HttpStatusEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testTaskStoreRestriction(): void
    {
        $response = $this->postJson('/api/tasks');
        $response->assertStatus(HttpStatusEnum::UNAUTHORIZED);
    }

    public function testTaskStoreSubmission(): void
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->postJson('/api/tasks', [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => TaskStatusEnum::PENDING,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d')
        ]);

        $response->assertStatus(HttpStatusEnum::OK);
        $response->assertJsonFragment(['message' => 'Task successfully created']);
        $this->assertDatabaseCount(Task::class, 1);
    }

    public function testTaskStoreValidation(): void
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->postJson('/api/tasks');
        $response->assertStatus(HttpStatusEnum::UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonCount(2, 'errors');
        $response->assertJson([
            'errors' => [
                'title' => [],
                'status' => [],
            ]
        ]);
    }
}
