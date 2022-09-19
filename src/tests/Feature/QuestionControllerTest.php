<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Question;
use App\Models\Category;
use Tests\TestCase;


class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->question = Question::factory()->create(['user_id' => $this->user->id]);
        $this->category = Category::factory()->create(['question_id' => $this->question->id]);
    }

    public function test_store_question()
    {
        $response = $this->actingAs($this->user)->postJson('/api/question', [
            'question' => 'testtest',
            'answer' => 'testtest',
            'category' => '学問',
            'share' => false,
        ]);
        $this->assertDatabaseHas('questions', [
            'question' => 'testtest',
            'answer' => 'testtest',
            'share' => false
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => '学問',
        ]);

        $response->assertStatus(200);
    }

    public function test_search_question()
    {
        $response = $this->actingAs($this->user)->postJson('/api/question/search', [
            'keyword' => $this->question->question,
        ]);
        $response->assertStatus(200);
    }

    public function test_download_question()
    {
        $response = $this->actingAs($this->user)->postJson('/api/question/download',['question_id' => $this->question->id]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('question_download', [
            'question_id' => $this->question->id,
            'user_id' => $this->user->id,
        ]);
    }
}
