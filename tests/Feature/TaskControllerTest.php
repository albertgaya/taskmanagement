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
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/tasks', [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => TaskStatusEnum::PENDING,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d')
        ]);

        $response->assertStatus(HttpStatusEnum::OK);
        $response->assertJsonFragment(['message' => 'Task successfully created']);
        $this->assertDatabaseCount(Task::class, 1);

        $task = Task::first();
        $this->assertEquals($task->user_id, $user->id);
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

    public function testTaskUpdateRestriction(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}");
        $response->assertStatus(HttpStatusEnum::UNAUTHORIZED);
    }

    public function testTaskUpdateSubmission(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user, ['*']);

        $data = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => TaskStatusEnum::IN_PROGRESS,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d')
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $data);

        $response->assertStatus(HttpStatusEnum::OK);
        $response->assertJsonFragment(['message' => 'Task successfully updated']);
        $this->assertDatabaseCount(Task::class, 1);

        $task->refresh();
        $this->assertEquals($task->title, $data['title']);
        $this->assertEquals($task->description, $data['description']);
        $this->assertEquals($task->status, $data['status']);
        $this->assertEquals($task->due_date->format('Y-m-d'), $data['due_date']);
        $this->assertEquals($task->user_id, $user->id);
    }

    public function testTaskUpdateInvalidParameter(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => false,
            'description' => [],
            'status' => 'invalid-status',
            'due_date' => '12345',
            'invalid-key' => 'invalid-value'
        ]);

        $response->assertStatus(HttpStatusEnum::UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
        $response->assertJsonCount(4, 'errors');
        $response->assertJson([
            'errors' => [
                'title' => [],
                'description' => [],
                'status' => [],
                'due_date' => [],
            ]
        ]);
    }

    public function testTaskUpdateNotOwner(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->putJson("/api/tasks/{$task->id}");

        $response->assertStatus(HttpStatusEnum::NOT_FOUND);
        $response->assertJsonFragment(['message' => 'Task doesn\'t exist']);
    }

    public function testTaskUpdateInvalidTaskId(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->putJson("/api/tasks/123");

        $response->assertStatus(HttpStatusEnum::NOT_FOUND);
        $response->assertJsonFragment(['message' => 'Task doesn\'t exist']);
    }
}
