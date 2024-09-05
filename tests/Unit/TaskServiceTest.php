<?php

namespace Tests\Unit;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Faker\Factory;
use Mockery;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    public function testStore()
    {
        $faker = Factory::create();

        $user = new User();
        $user->id = $faker->randomNumber();

        $data = [
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'status' => TaskStatusEnum::PENDING,
            'due_date' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d')
        ];

        $taskMock = Mockery::mock('alias:' . Task::class);

        $expectedTask = new Task();
        $expectedTask->title = $data['title'];
        $expectedTask->description = $data['description'];
        $expectedTask->status = $data['status'];
        $expectedTask->due_date = $data['due_date'];
        $expectedTask->user_id = $user->id;

        $taskMock->shouldReceive('create')
            ->andReturn($expectedTask);

        $taskService = new TaskService();
        $task = $taskService->store($data, $user);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($task->title, $data['title']);
        $this->assertEquals($task->description, $data['description']);
        $this->assertEquals($task->status, $data['status']);
        $this->assertEquals($task->due_date, $data['due_date']);
        $this->assertEquals($task->user_id, $user->id);
    }
}
