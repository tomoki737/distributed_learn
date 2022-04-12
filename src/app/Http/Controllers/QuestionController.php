<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\QuestionRequest;
use Carbon\Carbon;
use App\Models\Tag;
use Illuminate\Support\Facades\Date;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $questions = Question::where("user_id", $user_id)->with("tags")->get();
        return ['questions' => $questions];
    }

    public function create()
    {
        $allTagNames =  Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return ['allTagNames' => $allTagNames];
    }

    public function homeIndex(Request $request)
    {
        $user_id = $request->user()->id;
        $new_questions = Question::where("user_id", $user_id)->where("answer_times", 0)->get();
        $dateNow = new Carbon();
        $review_questions = Question::where("user_id", $user_id)->where("answer_times", ">", 0)->where("next_study_date", "<", $dateNow)->get();
        return ['new_questions' => $new_questions, "review_questions" => $review_questions];
    }

    public function answerIndex(Request $request)
    {
        $user_id = $request->user()->id;
        $dateNow = new Carbon();
        $questions = Question::where("user_id", $user_id)->where("next_study_date", "<", $dateNow)->get();
        return ['questions' => $questions];
    }

    public function edit(Question $question)
    {
        $tagNames = $question->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames =  Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return ['question' => $question, 'tagNames' => $tagNames, 'allTagNames' => $allTagNames];
    }

    public function store(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all());
        $question->user_id = $request->user()->id;
        $question->next_study_date = new Carbon();
        $question->save();
        $request->tags->each(function ($tagName) use ($question) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $question->tags()->attach($tag);
        });
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all())->save();
        $question->tags()->detach();
        $request->tags->each(function ($tagName) use ($question) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $question->tags()->attach($tag);
        });
    }

    public function destroy(Question $question)
    {
        $question->delete();
    }

    public function answer(Request $request, Question $question)
    {
        $question->answer_times += 1;
        $next_date = $this->next_date($question->answer_times);
        $question->next_study_date = $next_date;
        $question->correct_answer = $request->correct_answer;
        $question->save();
    }

    public function show()
    {
        return "show";
    }

    private function next_date($answer_times)
    {
        $date = "0";
        if ($answer_times === 1) {
            $date = "1";
        } else if ($answer_times === 2) {
            $date = "7";
        } else if ($answer_times === 3) {
            $date = "14";
        } else if ($answer_times === 4) {
            $date = "30";
        }
        return new Carbon("+" . $date . " day");
    }
}
