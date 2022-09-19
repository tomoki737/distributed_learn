<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class MeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_currentUser()
    {
        $response = $this->actingAs($this->user)->getJson("/api/me");
        $response->assertStatus(200);
    }
    public function test_guestUser()
    {
        $response = $this->getJson("/api/me");
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
