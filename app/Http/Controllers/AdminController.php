<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\LoginAdminRequest;
use App\Jwt\JwtService;
use App\Models\User;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function createAdmin(CreateAdminRequest $request, JwtService $jwtService)
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
        $user->is_marketing = $request->input('marketing', false);
        $user->is_admin = true;
        $user->save();

        $token = $jwtService->issueToken([
            'user_uuid' => $user->uuid,
        ]);

        $response = $user->only([
            'uuid',
            'first_name',
            'last_name',
            'email',
            'address',
            'phone_number',
            'updated_at',
            'created_at',
        ]);

        $response['token'] = $token;

        return response()->success(200, $response);
    }

    public function loginAdmin(LoginAdminRequest $request, JwtService $jwtService)
    {
        $credentials = $request->only(['email', 'password']);
        $credentials['is_admin'] = true;

        if (!auth()->validate($credentials)) {
            return response()->error(422, 'Failed to authenticate user');
        }

        /** @var User */
        $user = auth()->user();
        $user->last_login_at = now();
        $user->save();

        $token = $jwtService->issueToken([
            'user_uuid' => $user->uuid,
        ]);

        return response()->success(200, [
            'token' => $token,
        ]);
    }
}
