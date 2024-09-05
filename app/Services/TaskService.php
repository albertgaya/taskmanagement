<?php

namespace App\Services;
use App\Models\Task;

class TaskService
{
    /**
     * @param array $data
     * @return Task
     */
    public function store(array $data): Task
    {
        return Task::create($data);
    }
}