<?php

namespace App\Services;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

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

    /**
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function destroy(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * @param User $user
     * @param int $taskId
     * @return ?Task
     */
    public function getUserSingleTask(User $user, int $taskId): ?Task
    {
        return Task::where('user_id', $user->id)->where('id', $taskId)->first();
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserTasks(User $user): Collection
    {
        return Task::where('user_id', $user->id)->get();
    }
}