<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->question = Question::factory()->make();
    }

    public function test_question_store()
    {
        $response = $this->actingAs($this->user)->postJson('/api/question', [
            'question' => 'testtest',
            'answer' => 'testtest',
            'share' => false,
        ]);
        $this->assertDatabaseHas('questions', [
            'question' => 'testtest',
            'answer' => 'testtest',
            'share' => false
        ]);
        $response->assertStatus(200);
    }
}
