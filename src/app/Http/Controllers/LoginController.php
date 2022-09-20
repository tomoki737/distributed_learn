<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Contracts\Auth\Factory as FactoryAuth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;

final class LoginController extends Controller
{
    /**
     * CookieAuthenticationController constructor.
     * @param Auth $auth
     */
    public function __construct(
        private FactoryAuth $auth,
    ){}
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255', 'string'],
            'password' =>  ['required', 'min:8', 'string'],
        ]);

        if ($this->getGuard()->attempt($credentials,$remember =true)) {
            $request->session()->regenerate();

            return['user' => $request->user()];
        }

        throw new Exception('ログインに失敗しました。再度お試しください');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->getGuard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return new JsonResponse(['message' => 'ログアウトしました']);
    }

    private const GUEST_USER_ID = 1;

    public function guestLogin()
    {
       $guest_user = User::where('id', self::GUEST_USER_ID)->first();

        if(FacadesAuth::loginUsingId(self::GUEST_USER_ID)){
            return ['user' => $guest_user];
        }

        throw new Exception('ログインに失敗しました。再度お試しください');
    }

    /**
     * @return StatefulGuard
     */
    private function getGuard(): StatefulGuard
    {
        return $this->auth->guard(config('auth.defaults.guard'));
    }
}
