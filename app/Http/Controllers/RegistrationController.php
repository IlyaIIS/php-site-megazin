<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class RegistrationController extends Controller
{
    public function tryRegistrate(Request $request)
    {
        $request->validate([
            'inputEmail' => 'required|email|max:200',
            'inputPassword' => 'required|min:6|max:200',
            'inputPasswordRepeat' => 'required|same:inputPassword',
            'inputNickname' => 'required|max:200',
            'inputFirstName' => 'required|max:200',
            'inputLastName' => 'required|max:200',
            'inputBirthday' => 'required|date|before:-13 years',
            'inputCity' => 'required|max:200',
            'inputStreet' => 'required|max:200',
            'inputHouse' => 'required|max:200',
        ]);

        $email = $request->input('inputEmail');
        $password = $request->input('inputPassword');

        $user = DB::table('users')->where('email', $email)->first();
        $userWithSameNick = DB::table('users')->
            where('nickname', $request->
            input('inputNickname'))->
            first();

        if ($user) {
            return response()->json(['message' => 'Данный email уже зарегистрирован!'], 409);
        } else if ($userWithSameNick) {
            return response()->json(['message' => 'Данный псевданим уже занят!'], 409);
        } else {
            $hash = password_hash($email . $password, PASSWORD_BCRYPT);

            try {
                DB::table('users')->insert([
                    'nickname' => $request->input('inputNickname'),
                    'email' => $email,
                    'password_hash' => $hash,
                    'first_name' => $request->input('inputFirstName'),
                    'last_name' => $request->input('inputLastName'),
                    'birthday' => $request->input('inputBirthday'),
                    'city' => $request->input('inputCity'),
                    'street' => $request->input('inputStreet'),
                    'house' => $request->input('inputHouse'),
                    'apartment' => $request->input('inputApartment'),
                    'is_seller' => false,
                    'is_admin' => DB::table('users')->count() == 0
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Не удалось произвести регистрацию: какие-то неполадки на сервере :('
                ], 500);
            }

            $user = DB::table('users')->where('email', $email)->first();

            if ($user) {
                return response()->json([
                    'message' => "Вы успешно зарегистрированы!\n" .
                        "Перед входом подтвердите email, перейдя по ссылке в письме, отправленном по указанному адресу."
                ], 200);//response(status: 200);//redirect(status: 200)->route($_SERVER['REQUEST_URI']); //авторизация успешна, задавать куку, обновить страницу
            } else {
                return response()->json([
                    'message' => 'Не удалось произвести регистрацию: какие-то неполадки на сервере :('
                ], 502);
            }
        }
    }
}
