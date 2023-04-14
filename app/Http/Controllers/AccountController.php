<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AccountController extends Controller
{
    use ErrorsTrait, ImageTrait;
    public function getAccountPage(Request $request) : View
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $avatar = DB::table('images')->find($user->image_id);
        if ($avatar)
            $avatarPath = $avatar->path . $avatar->name;
        else
            $avatarPath = 'images/user-avatar-placeholder.png';

        return view('account_cabinet', [
            'user' => $user,
            'avatarPath' => $avatarPath
        ]);
    }

    public function modifyUser(Request $request)
    {
        $request->validate([
            'inputEmail' => 'email|max:200',
            'inputPassword' => 'min:6|max:200',
            'inputPasswordRepeat' => 'same:inputPassword',
            'inputNickname' => 'max:200',
            'inputFirstName' => 'max:200',
            'inputLastName' => 'max:200',
            'inputBirthday' => 'date|before:-13 years',
            'inputCity' => 'max:200',
            'inputStreet' => 'max:200',
            'inputHouse' => 'max:200',
        ]);
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $updateData = [];

        if ($input = $request->input('inputFirstName'))
            $updateData['first_name'] = $input;
        if ($input = $request->input('inputLastName'))
            $updateData['last_name'] = $input;

        if ($input = $request->input('inputNickname'))
            $updateData['nickname'] = $input;
        if ($input = $request->input('inputBirthday'))
            $updateData['birthday'] = $input;

        if ($input = $request->input('inputCity'))
            $updateData['city'] = $input;
        if ($input = $request->input('inputStreet'))
            $updateData['street'] = $input;
        if ($input = $request->input('inputHouse'))
            $updateData['house'] = $input;
        if ($input = $request->input('inputApartment'))
            $updateData['apartment'] = $input;
        elseif ($user->apartment)
            $updateData['apartment'] = null;

        //todo: добавить проверку отправкой кода на email после выхода с localhost на публичный ip
        if ($input = $request->input('inputPassword'))
            $updateData['password_hash'] = password_hash($user->email . $input, PASSWORD_BCRYPT);

        if (count($updateData) == 0)
            return response()->json(['message' => 'Нечего изменять: поля пустые!'], 400);

        try {
            DB::table('users')->update($updateData);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не удалось применить изменения: какие-то неполадки на сервере :('
            ], 500);
        }

        return response(status: 200);
    }

    public function modifyUserAvatar(Request $request)
    {
        $request->validate([
            'inputImage' => 'image',
        ]);
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $image = $request->file('inputImage');
        $imageId = $this->saveImageAndGetId($image, $user, 'images/avatars/');

        DB::table('users')->where('id', $user->id)->update(['image_id' => $imageId]);

        return response(status: 200);
    }
}
