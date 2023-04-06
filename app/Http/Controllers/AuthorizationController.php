<?php

namespace App\Http\Controllers;


use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AuthorizationController extends Controller
{
    public function tryAuthorize(Request $request)
    {
        $request->validate([
            'inputEmail' => 'required',
            'inputPassword' => 'required',
        ]);

        $email = $request->input('inputEmail');
        $password = $request->input('inputPassword');

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Пользователя с таким email не существует!'], 404);
        } else {
            if (password_verify($email . $password, $user->password_hash)) {
                $token = bin2hex(random_bytes(60 / 2));
                DB::table('sessions')->insert(['user_id' => $user->id, 'token' => $token]);
                $cookie = cookie('SESSION_TOKEN', $token, 30);
                return response()->json(['token' => $token])->cookie($cookie); //авторизация успешна, задавать куку
            } else {
                return response()->json(['message' => 'Пароль не верный!'], 404);
            }
        }
    }

    public function logout(Request $request) : string
    {
        $token = $request->cookie('SESSION_TOKEN');

        if ($token) {
            $session = DB::table('sessions')->where('token', $token)->first();
            DB::table('sessions')->where('user_id', $session->user_id)->delete();
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
