<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function indexUser(Request $request)
    {
        $user_id = $request->user()->id;
        $user = User::with(['questions.download_users'])->where('id', $user_id)->first();
        $download_questions_count = $user->download_questions->count();

        $downloaded_questions_count_collection = $user->questions->map(function ($question) {
            return $question->download_users->count();
        });
        $downloaded_questions_count = $downloaded_questions_count_collection->sum();

        return ['download_questions_count' => $download_questions_count, 'downloaded_questions_count' => $downloaded_questions_count];
    }
}
