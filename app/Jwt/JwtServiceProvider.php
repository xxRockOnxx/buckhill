<?php

namespace App\Jwt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JwtServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(config('jwt.private_key')),
            InMemory::file(config('jwt.public_key')),
        );

        $this->app->instance(Configuration::class, $config);

        $this->app->instance(JwtService::class, new LcobucciJwtService($config));

        Auth::extend('jwt', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);

            if (!$provider) {
                throw new \InvalidArgumentException("User provider [{$config['provider']}] is not defined.");
            }

            return new JwtGuard(
                $this->app->make(JwtService::class),
                $provider,
                $this->app->make(Request::class),
            );
        });
    }
}
