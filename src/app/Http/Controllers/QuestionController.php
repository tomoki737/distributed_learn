<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function store(Request $request, Question $question) {
        $question->fill($request->all());
        $question->user_id = $request->user()->id;
        $question->save();
        return ['question' => $question];
    }
}
