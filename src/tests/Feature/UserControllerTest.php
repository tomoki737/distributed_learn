<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Question;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->another_user = User::factory()->create();
        $this->question = Question::factory()->create(['user_id' => $this->user->id]);
        $this->another_question = Question::factory()->create(['user_id' => $this->another_user->id]);
    }

    public function test_index_user()
    {
        $this->question->download_users()->attach($this->another_user);
        $this->another_question->download_users()->attach($this->user);
        $this->another_question->likes()->attach($this->user);
        $this->question->likes()->attach($this->user);
        $response = $this->actingAs($this->user)->getJson('/api/user');
        $response->assertJson([
            'download_questions_count' => 1,
            'downloaded_questions_count' => 1,
            'likes_questions_count' => 1
        ]);
    }
}
