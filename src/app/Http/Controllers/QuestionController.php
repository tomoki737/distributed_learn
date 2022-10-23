<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\QuestionRequest;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\Tag;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $questions = Question::where("user_id", $user_id)->with(["tags", 'category', 'user', "download_users"])->get();

        $allTagNames =  $this->getAllTagNames();

        return ['questions' => $questions, 'allTagNames' => $allTagNames];
    }

    public function create()
    {
        $allTagNames =  Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return ['allTagNames' => $allTagNames];
    }
    public function indexSearch(Request $request)
    {
        $request->user() ?  $user_id = $request->user()->id : 0;
        $questions = Question::where("share", true)->with(["tags", 'category', 'user', "download_users"])->get();

        if ($request->user()) {
            $questions->where('user_id', '!=', $user_id)->get();
        }


        $allTagNames =  $this->getAllTagNames();
        return ['questions' => $questions, 'allTagNames' => $allTagNames];
    }


    public function indexHome(Request $request)
    {
        $user_id = $request->user()->id;
        $new_questions = Question::where([
            ["user_id", $user_id],
            ["answer_times", 0]
        ])->get();

        $dateNow = new Carbon();
        $review_questions = Question::where([
            ["user_id", $user_id],
            ["answer_times", ">", 0],
            ["next_study_date", "<", $dateNow]
        ])->get();

        $next_question =  Question::get()->where("next_study_date", ">", $dateNow)->sortBy("next_study_date")->first();
        $next_study_date = $next_question->next_study_date ?? null;
        return ['new_questions' => $new_questions, "review_questions" => $review_questions, 'next_study_date' => $next_study_date];
    }

    public function edit(Question $question)
    {
        $tagNames = $question->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });
        $allTagNames =  $this->getAllTagNames();

        $question->load(['category']);
        return ['question' => $question, 'tagNames' => $tagNames, 'allTagNames' => $allTagNames];
    }

    public function store(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all());
        $this->createQuestion($request, $question);

        $this->createTags($request, $question);

        $this->createCategory($request->category, $question);
    }



    public function update(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all())->save();
        $question->tags()->detach();
        $this->createTags($request, $question);

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

    public function downloadQuestion(Request $request, Question $question)
    {
        $download_question = Question::where('id', $request->question_id)->with(["tags", "category"])->first();
        $question->question = $download_question->question;
        $question->answer = $download_question->answer;
        $question->share = false;
        $this->createQuestion($request, $question);

        $download_question->download_users()->detach($request->user()->id);
        $download_question->download_users()->attach($request->user()->id);

        $download_question->tags->each(function ($tag) use ($question) {
            $question->tags()->attach($tag);
        });

        $this->createCategory($download_question->category->name, $question);
    }

    public function search(Request $request)
    {
        $user_id = $request->user() ? $request->user()->id : 0;
        $tag = $request->tag;
        $category = $request->category;
        $keyword = $request->keyword;
        $query = Question::query();
        $learning = $request->learning;

        if ($keyword !== null) {
            $query->where(function ($query) use ($keyword) {
                $query->where('question', 'like', "%" . $keyword . "%")
                    ->orWhere('answer', 'like', "%" . $keyword . "%");
            });
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
                    $query->where('name', $category);
                });
        }

        if ($request->is_my_question_search) {
            $query->where([
                ['learning', $learning],
                ['user_id', $user_id]
            ]);
        } else {
            $query->where('user_id', '!=', $user_id);
        }

        $query->where("share", true);

        $questions = $query->with(["tags", "category", "user", "download_users"])->get();

        return ['questions' => $questions, 'keyword' => $keyword, 'tag' => $tag, 'category' => $category];
    }

    private function createQuestion($request, $question)
    {
        $question->user_id = $request->user()->id;
        $question->next_study_date = new Carbon();
        $question->save();
    }

    private function createCategory($request_category, $question)
    {
        $category = new Category();
        $category->name = $request_category;
        $category->question_id = $question->id;
        $category->save();
    }

    private function createTags($request, $question)
    {
        $request->tags->each(function ($tagName) use ($question) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $question->tags()->attach($tag);
        });
    }

    private function getAllTagNames()
    {
        return Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
    }
}
