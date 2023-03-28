<?php

namespace Tests\Feature;

use App\Jwt\JwtService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class JwtGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_user_from_token()
    {
        // Setup
        $user = User::factory()->create();
        $token = app(JwtService::class)->issueToken(['user_uuid' => $user->uuid]);
        app(Request::class)->headers->set('Authorization', 'Bearer ' . $token);

        // Assert
        $this->assertTrue(auth()->check());
        $this->assertNotNull(auth()->user());
        $this->assertEquals(auth()->user()->uuid, $user->uuid);
    }

    public function test_cannot_get_user_without_token()
    {
        $this->assertFalse(auth()->check());
        $this->assertNull(auth()->user());
    }

    public function test_can_validate_credentials()
    {
        // Setup
        $user = User::factory()->create();

        // Action
        $valid = auth()->validate(['email' => $user->email, 'password' => 'password']);
        $invalid = auth()->validate(['email' => $user->email, 'password' => 'password123']);

        // Assert
        $this->assertTrue($valid);
        $this->assertFalse($invalid);
    }

    // Handle invalid token

    // Handle expired token
}
