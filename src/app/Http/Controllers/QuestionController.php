<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\QuestionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $questions = Question::where("user_id", $user_id)->get();
        return ['questions' => $questions];
    }

    public function answerIndex(Request $request)
    {
        $user_id = $request->user()->id;
        $dateNow = new Carbon();
        $questions = Question::where("user_id", $user_id)->where("next_study_date", "<" , $dateNow)->get();
        return ['questions' => $questions];
    }

    public function edit(Question $question)
    {
        return ['question' => $question];
    }

    public function store(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all());
        $question->user_id = $request->user()->id;
        $question->next_study_date = new Carbon();
        $question->save();
    }

    public function update(Questionfequest $request, Question $question)
    {
        $question->fill($request->all())->save();
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

    public function show() {
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
