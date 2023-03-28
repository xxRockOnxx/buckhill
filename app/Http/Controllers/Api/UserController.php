<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Jwt\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;

class UserController
{
    public function getUserOrders(Request $request)
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

    public function getUser(Request $request)
    {
        return response()->success(200, $request->user());
    }

    public function createUser(CreateUserRequest $request, JwtService $jwtService)
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

        return response()->success(200, $response);
    }

    public function loginUser(LoginRequest $request, Configuration $config)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->validate($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /** @var User */
        $user = auth()->user();
        $user->last_login_at = now();
        $user->save();

        $token = $config
            ->builder()
            ->expiresAt(now()->addMinutes(config('jwt.ttl'))->toDateTimeImmutable())
            ->issuedBy(config('app.url'))
            ->withClaim('user_uuid', $user->uuid)
            ->getToken($config->signer(), $config->signingKey());

        return response()->success(200, [
            'token' => $token->toString(),
        ]);
    }
}
