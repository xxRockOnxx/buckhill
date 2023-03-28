<?php

namespace Tests\Feature;

use App\Jwt\JwtService;
use Tests\TestCase;

class JwtServiceTest extends TestCase
{
    /**
     * @var JwtService
     */
    private $jwtService;

    public function setUp(): void
    {
        parent::setUp();
        $this->jwtService = app(JwtService::class);
    }

    public function test_can_issue_token()
    {
        $this->freezeTime();

        $timeIssued = now();

        $token = $this->jwtService->issueToken(['foo' => 'bar']);

        $this->assertIsString($token);

        $claims = $this->jwtService->parseToken($token);

        $this->assertIsArray($claims);
        $this->assertArrayHasKey('foo', $claims);
        $this->assertArrayHasKey('iss', $claims);
        $this->assertArrayHasKey('exp', $claims);

        $this->assertEquals('bar', $claims['foo']);
        $this->assertEquals(config('app.url'), $claims['iss']);
        $this->assertEquals($timeIssued->addMinutes(config('jwt.ttl')), $claims['exp']);
    }
}
