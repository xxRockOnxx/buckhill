<?php

namespace App\Jwt;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;

class LcobucciJwtService implements JwtService
{
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function issueToken(array $claims): string
    {
        $builder = $this->configuration
            ->builder()
            ->issuedBy(config('app.url'))
            ->expiresAt(now()->addMinutes(config('jwt.ttl'))->toDateTimeImmutable());

        foreach ($claims as $name => $value) {
            $builder = $builder->withClaim($name, $value);
        }

        $token = $builder->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }

    public function parseToken(string $token): array
    {
        $token = $this->configuration->parser()->parse($token);

        // Default Lcobucci implementation returns a Plain token.
        // If a different implementation is needed, this method should be
        // updated to handle it.
        if (!$token instanceof Plain) {
            throw new \Exception('Cannot handle token');
        }

        return $token->claims()->all();
    }
}
