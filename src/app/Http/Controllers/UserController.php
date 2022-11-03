<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('id', $id)->with('followers')->withCount(['followers', 'followings'])->first();
        $download_questions_count = $user->download_questions->count();

        $downloaded_questions_count = $this->getCount($user, "download_users");

        $likes_questions_count = $this->getCount($user, "likes");

        return ['user' => $user, 'download_questions_count' => $download_questions_count, 'downloaded_questions_count' => $downloaded_questions_count, 'likes_questions_count' => $likes_questions_count];
    }

    public function follow(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself');
        }
        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return ['name' => $user->name];
    }

    public function unfollow(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $user->name];
    }

    public function followers(string $id)
    {
        $user = User::where('id', $id)->with(['followers'])->first();
        $followers = $user->followers;

        return ['followers' => $followers];
    }

    public function followings(String $id)
    {
        $user = User::where('id', $id)->first();

        $followings = $user->followings->sortByDesc('created_at');

        return ['followings' => $followings];
    }

    private function getCount($user, $method)
    {
        $collection = $user->questions->map(function ($question) use ($method) {
            return $question->$method->count();
        });
        return $collection->sum();
    }
}
