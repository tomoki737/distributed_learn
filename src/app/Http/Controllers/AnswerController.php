<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Question;

class AnswerController extends Controller
{

    public function indexAnswer(Request $request)
    {
        $user_id = $request->user()->id;
        $dateNow = new Carbon();

        $questions = Question::where([
            ["user_id", $user_id],
            ["next_study_date", "<", $dateNow],
            ['learning', true]
        ])->get();

        return ['questions' => $questions];
    }

    public function answer(Request $request, Question $question)
    {
        $question->answer_times += 1;
        $next_date = $this->next_date($question->answer_times);
        $question->next_study_date = $next_date;

        if ($question->next_study_date === null) {
            $question->learning = false;
        }

        $question->fill($request->all())->save();
        return $question;
    }


    public function indexSelectAnswer(Request $request)
    {
        $user_id = $request->user()->id;
        $dateNow = new Carbon();

        $questions = Question::where([
            ["user_id", $user_id],
            ["next_study_date", "<", $dateNow]
        ])->with(["category", "tags"])->inRandomOrder()->get();

        $all_questions = Question::where("user_id", $user_id)->with(["category", "tags"])->get();
        return ['questions' => $questions, 'all_questions' => $all_questions];
    }

    private function next_date($answer_times)
    {
        $to_date = [
            1 => "1",
            2 => "7",
            3 => "14",
            4 => "30",
            5 => null,
        ];

        $date = $to_date[$answer_times];
        
        if ($date === null) {
            return;
        }

        return new Carbon("+" . $date . " day");
    }
}
