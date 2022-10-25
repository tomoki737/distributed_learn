<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\Category;
class QuestionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->another_user = User::factory()->create();
        $this->question = Question::factory()->create(['user_id' => $this->user->id]);
        $this->another_question = Question::factory()->create(['user_id' => $this->another_user->id]);
        $this->category = Category::factory()->create(['question_id' => $this->question->id]);
        $this->another_category = Category::factory()->create(['question_id' => $this->another_question->id]);
    }

    public function testIsLikedByNull()
    {
        $response = $this->question->isLikedBy(null);
        $this->assertFalse($response);
    }

    public function testIsLikedByUser()
    {
        $this->question->likes()->attach($this->user->id);
        $response = $this->question->isLikedBy($this->user);
        $this->assertTrue($response);
    }

    public function testIsLikedBy()
    {
        $this->question->likes()->attach($this->another_user->id);
        $response = $this->question->isLikedBy($this->user);
        $this->assertFalse($response);
    }
}
