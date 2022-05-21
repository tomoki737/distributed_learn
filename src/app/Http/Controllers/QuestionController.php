<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\QuestionRequest;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $questions = Question::where("user_id", $user_id)->with(["tags", 'category'])->get();

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
        $next_question =  Question::get()->where("next_study_date", ">", $dateNow)->sortBy("next_study_date")->first();
        $next_study_date = $next_question->next_study_date ?? null;
        return ['new_questions' => $new_questions, "review_questions" => $review_questions, 'next_study_date' => $next_study_date];
    }

    public function edit(Question $question)
    {
        $tagNames = $question->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames =  Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
        $question->load(['category']);
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

        $category = new Category();
        $category->name = $request->category;
        $category->question_id = $question->id;
        $category->save();
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all())->save();
        $question->tags()->detach();
        $request->tags->each(function ($tagName) use ($question) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $question->tags()->attach($tag);
        });

        Category::where('question_id', $question->id)->update(['name' => $request->category]);
    }

    public function destroy(Question $question)
    {
        $question->delete();
    }

    public function exceptQuestion(Question $question)
    {
        $question->learning = false;
        $question->save();
    }

    public function search(Request $request)
    {
        $user_id = $request->user()->id;
        $tag = $request->tag;
        $category = $request->category;
        $keyword = $request->keyword;
        $query = Question::query();

        if ($keyword !== null) {
            $query->where('question', 'like', "%" . $keyword . "%")->orWhere('answer', 'like', "%" . $keyword . "%")->where('user_id', '=', $user_id);
        }

        if ($tag !== null) {
            $query
                ->whereHas('tags', function ($query) use ($tag) {
                    $query->where('name', 'like', "%{$tag}%");
                });
        }

        if ($category !== null) {
            $query
                ->whereHas('category', function ($query) use ($category) {
                    $query->where('name', '=', $category);
                });
        }

        $questions = $query->with(["tags", "category"])->get();

        return ['questions' => $questions, 'keyword' => $keyword, 'tag' => $tag, 'category' => $category];
    }
}
