<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Question;
class AnswerController extends Controller
{
    public function answer(Request $request, Question $question)
    {
        $question->answer_times += 1;
        $next_date = $this->next_date($question->answer_times);
        $question->next_study_date = $next_date;
        $question->fill($request->all())->save();
        if($question->answer_times === null) {
            $question->learning = false;
        }
        return $question;
    }

    public function answerIndex(Request $request)
    {
        $user_id = $request->user()->id;
        $dateNow = new Carbon();
        $questions = Question::where("user_id", $user_id)->where("next_study_date", "<", $dateNow)->get();
        return ['questions' => $questions];
    }

    public function selectAnswerIndex(Request $request)
    {
        $user_id = $request->user()->id;
        $dateNow = new Carbon();
        $questions = Question::where("user_id", $user_id)->where("next_study_date", "<", $dateNow)->get();
        $all_questions = Question::where("user_id", $user_id)->get();
        return ['questions' => $questions, 'all_questions' => $all_questions];
    }

    private function next_date($answer_times)
    {
        if ($answer_times === 1) {
            $date = "1";
        } else if ($answer_times === 2) {
            $date = "7";
        } else if ($answer_times === 3) {
            $date = "14";
        } else if ($answer_times === 4) {
            $date = "30";
        } else {
            return null;
        }
        return new Carbon("+" . $date . " day");
    }
}
