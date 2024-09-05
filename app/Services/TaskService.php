<?php

namespace App\Services;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    /**
     * @param array $data
     * @return Task
     */
    public function store(array $data, User $user): Task
    {
        return Task::create([...$data, 'user_id' => $user->id]);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }
}