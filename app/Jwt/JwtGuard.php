<?php

namespace App\Jwt;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Exception;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var JwtService
     */
    protected $service;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(JwtService $service, UserProvider $provider, Request $request)
    {
        $this->service = $service;
        $this->provider = $provider;
        $this->request = $request;
    }

    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if (! $user) {
            return false;
        }

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }

        return false;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = $this->request->bearerToken();

        if (! $token) {
            return null;
        }

        $payload = $this->parseTokenFromRequest();

        if (! $payload) {
            return null;
        }

        $exp = $payload['exp'];

        if (now()->greaterThanOrEqualTo($exp)) {
            return null;
        }

        $uuid = $payload['user_uuid'];

        return $this->provider->retrieveByCredentials(['uuid' => $uuid]);
    }

    protected function parseTokenFromRequest(): array|null
    {
        $token = $this->request->bearerToken();

        if (! $token) {
            return null;
        }

        try {
            return $this->service->parseToken($token);
        } catch (Exception $e) {
            return null;
        }
    }
}
