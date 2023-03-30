<?php

namespace App\Jwt;

interface JwtService
{
    /**
     * @param array<non-empty-string, mixed> $claims
     */
    public function issueToken(array $claims): string;

    /**
     * @return array<non-empty-string, mixed>
     */
    public function parseToken(string $token): array;
}
