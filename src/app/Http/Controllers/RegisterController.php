<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Factory as Auth;

class RegisterController extends Controller
{
    public function __construct(
        private Auth $auth,
    ) {
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email', 'unique:users', 'max:255', 'string'],
            'password' =>  ['required', 'min:8', 'string'],
        ]);

        $user =  User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        if ($this->getGuard()->attempt($credentials,$remember =true)) {
            $request->session()->regenerate();
        }

        return new JsonResponse(['user' => $user, 'message' => '登録に成功しました']);
    }

    private function getGuard(): StatefulGuard
    {
        return $this->auth->guard(config('auth.defaults.guard'));
    }
}
