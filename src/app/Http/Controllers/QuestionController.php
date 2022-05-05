<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\QuestionRequest;
use Carbon\Carbon;
use App\Models\Tag;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $questions = Question::where("user_id", $user_id)->with("tags")->get();

        $allTagNames =  Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return ['questions' => $questions, 'allTagNames' => $allTagNames];
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

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $query = Question::query();
        if ($keyword === null) {
            $query->when($keyword, function ($query, $keyword) {
                return $query->where('question', 'or', 'answer', '%' . $keyword . '%');
            });
        }
        $questions = $query->with("tags")->get();
        return ['questions' => $questions];
    }
}
