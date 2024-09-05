<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @param UserRegisterRequest $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request, UserService $userService): JsonResponse
    {
        $userService->store($request->all());

        return response()->json(['message' => 'User successfully registered']);
    }
}
