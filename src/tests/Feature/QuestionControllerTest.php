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
    }

    public function test_question_store()
    {
        $response = $this->actingAs($this->user)->postJson('/api/question/store', [
            'question' => 'testtest',
            'answer' => 'testtest',
        ]);
        $response->assertStatus(200);
        $question = Question::first();
    }
}
