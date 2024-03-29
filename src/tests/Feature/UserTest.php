<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();
    }

    public function testIsFollowedByTheUser()
    {
        $this->user->followings()->attach($this->anotherUser);
        $result = $this->anotherUser->isFollowedBy($this->user);
        $this->assertTrue($result);
    }
    public function testIsFollowedByAnotherUser()
    {
        $this->user->followings()->attach($this->anotherUser);
        $result = $this->anotherUser->isFollowedBy($this->anotherUser);
        $this->assertFalse($result);
    }

    public function testIsFollowedByNull()
    {
        $result = $this->user->isFollowedBy(null);
        $this->assertFalse($result);
    }
}
