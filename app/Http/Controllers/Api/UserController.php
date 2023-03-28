<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;

class UserController
{
    public function getUser(Request $request)
    {
        return $request->user();
    }

    public function createUser(CreateUserRequest $request)
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
        $user->is_marketing = $request->is_marketing;
        $user->save();

        return $user;
    }

    public function loginUser(LoginRequest $request, Configuration $config)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->validate($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $config
            ->builder()
            ->expiresAt(now()->addMinutes(config('jwt.ttl'))->toDateTimeImmutable())
            ->issuedBy(config('app.url'))
            ->withClaim('user_uuid', auth()->user()->uuid)
            ->getToken($config->signer(), $config->signingKey());

        return response()->json([
            'token' => $token->toString(),
        ]);
    }
}