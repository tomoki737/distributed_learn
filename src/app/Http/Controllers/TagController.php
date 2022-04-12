<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(String $name)
    {
        $tag = Tag::where('name', $name)->with('questions')->first();
        return ['tag' => $tag];
    }
}
