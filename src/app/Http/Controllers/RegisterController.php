<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        return new JsonResponse(['message' => '登録に成功しました']);
    }
}
