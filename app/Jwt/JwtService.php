<?php

namespace App\Jwt;

interface JwtService
{
    public function issueToken(array $claims): string;

    public function parseToken(string $token): array;
}
