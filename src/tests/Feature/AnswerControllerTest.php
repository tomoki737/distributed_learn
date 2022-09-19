<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Question;

class AnswerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->question = Question::factory()->create(["answer_times" => 4]);

    }

    //次回の学習をしないよう設定する機能についてのテスト
    public function test_next_learning_false()
    {

        $response = $this->actingAs($this->user)->putJson("/api/question/" . $this->question->id . "/answer",[
            "correct_answer" => true,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas("questions", ["learning" => false]);
    }
}
