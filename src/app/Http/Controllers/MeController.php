<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    use RefreshDatabase;

    public function me(Request $request)
    {
        $user = $request->user();

            return ["user" => $user];
    }
}
