<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(String $name)
    {
        $tag = Tag::where('name', $name)->with(['questions.user','questions.download_users', 'questions.category'])->first();
        return ['tag' => $tag];
    }
}
