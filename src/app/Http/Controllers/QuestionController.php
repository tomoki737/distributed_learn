<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\QuestionRequest;

class QuestionController extends Controller
{
    use RefreshDatabase;

    public function index()
    {
       $questions = Question::all();
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
        $question->save();
        return ['question' => $question];
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all())->save();
        return ['question' => $question];
    }

    public function destroy(Question $question)
    {
        $question->delete();
    }
}
