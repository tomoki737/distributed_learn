<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Question;
use App\Models\Category;
use App\Models\Tag;
use Tests\TestCase;


class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->another_user = User::factory()->create();
        $this->my_question = Question::factory()->create(['user_id' => $this->user->id]);
        $this->question = Question::factory()->create(['user_id' => $this->user->id]);
        $this->another_question = Question::factory()->create(['user_id' => $this->another_user->id]);
        $this->category = Category::factory()->create(['question_id' => $this->question->id]);
        $this->another_category = Category::factory()->create(['question_id' => $this->another_question->id]);
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

    public function test_search_all_question()
    {
        $tag = Tag::factory()->create();
        $this->another_question->tags()->attach($tag);

        $tagNames = $this->another_question->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $tagName = $tagNames[0]['text'];

        $response = $this->actingAs($this->user)->postJson('/api/question/search', [
            'keyword' => $this->another_question->question,
            'is_my_question_search' => false,
            'category' => $this->another_category->name,
            'tag' => $tagName
        ]);

        $response->assertJson(['questions' => [
            [
                'id' => $this->another_question->id
            ]
        ]]);
        $response->assertStatus(200);
    }

    //他ユーザーを含む全ての問題を検索する画面で自身が作成した問題が表示されないかのテスト
    public function test_search_my_question_empty()
    {
        $response = $this->actingAs($this->another_user)->postJson('/api/question/search', [
            'keyword' => $this->my_question->question,
            'is_my_question_search' => true
        ]);

        $response->assertJson(['questions' => []]);
        $response->assertStatus(200);
    }

    public function test_download_question()
    {
        $response = $this->actingAs($this->user)->postJson('/api/question/download', ['question_id' => $this->question->id]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('question_download', [
            'question_id' => $this->question->id,
            'user_id' => $this->user->id,
        ]);
    }
}
