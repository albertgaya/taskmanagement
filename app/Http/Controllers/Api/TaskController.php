<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * @param TaskService $taskService
     */
    public function __construct(private TaskService $taskService)
    {
    }

    /**
     * @param TaskStoreRequest $request
     * @return JsonResponse
     */
    public function store(TaskStoreRequest $request): JsonResponse
    {
        /** @var User */
        $user = Auth::user();

        $this->taskService->store($request->all(), $user);

        return response()->json(['message' => 'Task successfully created']);
    }

    /**
     * @param int $taskId
     * @param TaskUpdateRequest $request
     * @return JsonResponse
     */
    public function update(int $taskId, TaskUpdateRequest $request): JsonResponse
    {
        /** @var User */
        $user = Auth::user();

        $task = $this->taskService->getUserSingleTask($user, $taskId);

        if (!$task) {
            return response()->json(['message' => 'Task doesn\'t exist'], HttpStatusEnum::NOT_FOUND);
        }

        $this->taskService->update($task, $request->all());

        return response()->json(['message' => 'Task successfully updated']);
    }

    /**
     * @param int $taskId
     * @return JsonResponse
     */
    public function destroy(int $taskId): JsonResponse
    {
        /** @var User */
        $user = Auth::user();

        $task = $this->taskService->getUserSingleTask($user, $taskId);

        if (!$task) {
            return response()->json(['message' => 'Task doesn\'t exist'], HttpStatusEnum::NOT_FOUND);
        }

        $this->taskService->destroy($task);

        return response()->json(['message' => 'Task successfully deleted']);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var User */
        $user = Auth::user();

        $tasks = $this->taskService->getUserTasks($user);

        return response()->json(['data' => TaskResource::collection($tasks)]);
    }
}
