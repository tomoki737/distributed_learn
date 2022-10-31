<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('id', $id)->first();
        $download_questions_count = $user->download_questions->count();

        $downloaded_questions_count = $this->getCount($user, "download_users");

        $likes_questions_count = $this->getCount($user, "likes");

        return ['user' => $user, 'download_questions_count' => $download_questions_count, 'downloaded_questions_count' => $downloaded_questions_count, 'likes_questions_count' => $likes_questions_count];
    }

    private function getCount($user, $method)
    {
        $collection = $user->questions->map(function ($question) use ($method) {
            return $question->$method->count();
        });
        return $collection->sum();
    }
}
