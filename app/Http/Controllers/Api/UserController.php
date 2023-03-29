<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Jwt\JwtService;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;

class UserController
{
    /**
     * @return LengthAwarePaginator<Order>
     */
    public function getUserOrders(Request $request): LengthAwarePaginator
    {
        /** @var User */
        $user = $request->user();

        return $user->orders()
            ->with([
                'orderStatus',
                'payment',
                'user',
            ])
            ->orderBy($request->input('sort', 'created_at'), $request->boolean('desc') ? 'desc' : 'asc')
            ->paginate($request->input('limit', 10), ['*'], 'page', $request->input('page', 1));
    }

    public function getUser(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return Response::success(200, $user->toArray());
    }

    public function createUser(CreateUserRequest $request, JwtService $jwtService): JsonResponse
    {
        $user = new User();
        $user->uuid = Str::uuid();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->avatar = $request->avatar;
        $user->is_marketing = $request->input('is_marketing', false);
        $user->save();

        $token = $jwtService->issueToken([
            'user_uuid' => $user->uuid,
        ]);

        $response = $user->toArray();
        $response['token'] = $token;

        return Response::success(200, $response);
    }

    public function loginUser(LoginRequest $request, JwtService $jwtService): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = false;

        if (! auth()->validate($credentials)) {
            return Response::error(422, 'Failed to authenticate user');
        }

        /** @var User $user */
        $user = auth()->user();
        $user->last_login_at = now();
        $user->save();

        $token = $jwtService->issueToken([
            'user_uuid' => $user->uuid,
        ]);

        return Response::success(200, [
            'token' => $token,
        ]);
    }
}
