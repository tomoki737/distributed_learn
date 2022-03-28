<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email', 'unique:users', 'max:255', 'string'],
            'password' =>  ['required', 'min:8', 'string'],
        ]);

        User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        return new JsonResponse(['message' => '登録に成功しました']);
    }
}
