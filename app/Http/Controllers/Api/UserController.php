<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $this->userService->store($request->all());

        return response()->json(['message' => 'User successfully registered']);
    }

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $token = $this->userService->login($request->get('email'), $request->get('password'));

        if ($token) {
            return response()->json([
                'message' => 'User successfully logged in',
                'token' => $token
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], HttpStatusEnum::UNAUTHORIZED);
    }
}
